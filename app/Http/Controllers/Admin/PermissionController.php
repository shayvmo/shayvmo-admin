<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Backend\PermissionPostRequest;
use App\Models\Role;
use App\Services\Base\CommonService;
use App\Http\Controllers\Controller;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function showPage()
    {
        return view('admin.permission.index');
    }


    public function index()
    {
        $permissions = Permission::query()->orderByDesc('sort')->get()->toArray();
        $permissions_tree = get_data_tree($permissions);
        return $this->successData(compact('permissions_tree', 'permissions'));
    }


    public function store(PermissionPostRequest $permissionPostRequest)
    {
        $params = $permissionPostRequest->fillData();
        $rules = [
            'name' => [
                'required',
                'string',
                'max: 100',
                'unique:permissions,name'
            ],
        ];
        CommonService::validate($params, $rules);
        $permission = Permission::create($params);
        $permission->path = ($permission->pid > 0 ? $permission->parent->path ?? '0' : '0').'-'.$permission->id;
        $permission->save();
        Role::findByName('super-admin')->givePermissionTo($permission);
        return $this->successData($permission->toArray());
    }

    public function update(Permission $permission, PermissionPostRequest $permissionPostRequest)
    {
        $params = $permissionPostRequest->fillData();
        if ($params['name'] !== $permission->name) {
            $rules = [
                'name' => [
                    'required',
                    'string',
                    'max: 100',
                    'unique:permissions,name'
                ],
            ];
            CommonService::validate($params, $rules);
        }
        $permission->fill($params)->saveOrFail();
        $permission->path = ($permission->pid > 0 ? $permission->parent->path : '0').'-'.$permission->id;
        $permission->save();
        return $this->success();
    }


    public function destroy(Permission $permission)
    {
        $parent_permission_ids = Permission::query()
            ->where('path', 'like', $permission->path.'%')
            ->pluck('id');
        Permission::destroy($parent_permission_ids);
        return $this->success();
    }
}
