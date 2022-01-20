<?php

namespace App\Http\Controllers\Admin;

use App\Constants\SystemConstant;
use App\Exceptions\ServiceException;
use App\Http\Requests\Backend\RoleListRequest;
use App\Http\Requests\Backend\RolePermissionsPostRequest;
use App\Http\Requests\Backend\RolePostRequest;
use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Services\Base\CommonService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class RoleController extends Controller
{
    /**
     * 角色列表
     * @return \Illuminate\Contracts\View\View
     */
    public function showPage()
    {
        return view('admin.role.index');
    }

    /**
     * 角色列表接口数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RoleListRequest $roleListRequest)
    {
        [
            'page_size' => $page_size,
            'keyword' => $keyword,
        ] = $roleListRequest->fillData();
        $data = Role::query()
            ->when($keyword, function ($query) use ($keyword) {
                return $query->where('title', 'like', "%$keyword%")
                    ->orWhere('name', 'like', "%$keyword%")
                    ->orWhere('id', (int)$keyword);
            })->paginate($page_size)->toArray();

        return $this->successData(CommonService::changePageDataFormat($data));
    }


    public function store(RolePostRequest $request)
    {
        $params = $request->fillData();
        $rules = [
            'name' => [
                'unique:roles,name',
            ],
        ];
        CommonService::validate($params, $rules);
        $params['desc'] = $params['desc'] ?? '';
        $role = Role::query()->create($params);
        return $this->successData($role->toArray());
    }

    public function update(Role $role, RolePostRequest $request)
    {
        $params = $request->fillData();
        if ($role->id === 1) {
            throw new ServiceException(SystemConstant::CANT_EDIT_SYSTEM_ITEM);
        }
        if ($role->name != $params['name']) {
            $rules = [
                'name' => [
                    'unique:roles,name',
                ],
            ];
            CommonService::validate($params, $rules);
        }
        $params['desc'] = $params['desc'] ?? '';
        $role->fill($params)->save();
        return $this->successData($role->toArray());
    }


    public function destroy(Role $role)
    {
        if($role->id === 1) {
            throw new ServiceException(SystemConstant::CANT_EDIT_SYSTEM_ITEM);
        }
        try {
            $role->delete();
        } catch (QueryException $queryException) {
            throw new ServiceException(SystemConstant::DELETE_FAILED);
        }

        return $this->success();
    }


    public function getRolePermissions(Role $role)
    {
        $all_permissions = Permission::all();
        $role_permissions = $role->getAllPermissions();
        $lists = get_data_tree($all_permissions->toArray());
        $checked_key_ids = $role_permissions
            ->pluck('id')
            ->diff(
                $all_permissions
                    ->countBy('pid')
                    ->diffAssoc($role_permissions->filter(function($item) {
                        return $item->pid > 0;
                    })->countBy('pid')
                    )->keys()
            )->values()
            ->all();// 过滤没有全选的父节点后选中的节点ID
        $checked_key_names = $role_permissions->filter(function($item) use ($checked_key_ids) {
            return in_array($item->id, $checked_key_ids, true);
        })->pluck('name')->all();// 选中的节点对应的name
        return $this->successData([
            'lists'=>$lists,
            'checkedKeys'=>$checked_key_names,
        ]);
    }


    public function setRolePermissions(Role $role,RolePermissionsPostRequest $rolePermissionsPostRequest)
    {
        [
            'rules' => $rules
        ] = $rolePermissionsPostRequest->fillData();
        if ($role->id === 1) {
            throw new ServiceException(SystemConstant::CANT_EDIT_SYSTEM_ITEM);
        }
        $role->syncPermissions($rules);
        return $this->success();
    }
}
