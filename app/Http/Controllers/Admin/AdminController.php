<?php

namespace App\Http\Controllers\Admin;

use App\Constants\BackendConstant;
use App\Constants\SystemConstant;
use App\Exceptions\ServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AdminListRequest;
use App\Http\Requests\Backend\AdminPostRequest;
use App\Models\Admin;
use App\Models\Role;
use App\Services\Base\CommonService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // 展示页
    public function showPage()
    {
        $roles = Role::select([
            'name',
            'title'
        ])->get()->toArray();
        return view('admin.admin.index', compact('roles'));
    }

    // 数据列表api
    public function index(AdminListRequest $request)
    {
        [
            'page_size' => $page_size,
            'keyword' => $keyword,
        ] = $request->fillData();
        $data = Admin::query()
            ->with('roles')
            ->when($keyword, function ($query, $keyword) {
                return $query
                    ->where('username', 'like', "$keyword%")
                    ->orWhere('nickname', 'like', "$keyword%")
                    ->orWhere('id', $keyword);
            })->paginate($page_size)->toArray();
        $data = CommonService::changePageDataFormat($data);
        $data['data'] = collect($data['data'])->transform(function ($item, $key) {
            $item['role_names'] = collect($item['roles'])->pluck('title')->all();
            $item['roles'] = collect($item['roles'])->pluck('name')->all();
            return $item;
        });
        return $this->successData($data);
    }

    // 添加api
    public function store(AdminPostRequest $request)
    {
        $params = $request->fillData();
        $rules = [
            'username' => [
                'required',
                'alpha_num',
                'chinese',
                'between:5,18',
                'unique:admins,username',
            ],
        ];
        CommonService::validate($params, $rules);
        $params['password'] = BackendConstant::DEFAULT_PASSWORD;
        $admin = Admin::query()->create($params);
        $params['roles'] && $admin->assignRole($params['roles']);
        activity()->inLog('create')->performedOn($admin)->causedBy($request->user())->withProperties($params)->log('添加数据');
        return $this->successData($admin->toArray());
    }


    // 更新api
    public function update(Admin $admin, AdminPostRequest $adminPostRequest)
    {
        $params = $adminPostRequest->fillData();
        if ($admin->id === 1) {
            throw new ServiceException(SystemConstant::CANT_EDIT_SYSTEM_ITEM);
        }
        if ($params['username'] !== $admin->username) {
            $rules = [
                'username' => [
                    'required',
                    'alpha_num',
                    'chinese',
                    'between:5,18',
                    'unique:admins,username',
                ],
            ];
            CommonService::validate($params, $rules);
        }
        $admin->fill($params)->saveOrFail();
        $admin->syncRoles($params['roles']);
        activity()->inLog('update')->performedOn($admin)->causedBy($adminPostRequest->user())->withProperties($params)->log('更新数据');
        return $this->success();
    }


    public function destroy(Admin $admin, Request $request)
    {
        if ($admin->id === 1) {
            throw new ServiceException(SystemConstant::CANT_EDIT_SYSTEM_ITEM);
        }
        $admin->delete();
        activity()->inLog('destroy')->performedOn($admin)->causedBy($request->user())->withProperties($admin->toArray())->log('删除数据');
        return $this->success();
    }

    public function resetPwd(Admin $admin, Request $request)
    {
        if($admin->id === 1) {
            throw new ServiceException(SystemConstant::CANT_EDIT_SYSTEM_ITEM);
        }
        $admin->password = BackendConstant::DEFAULT_PASSWORD;
        $admin->save();
        activity()->inLog('update')->performedOn($admin)->causedBy($request->user())->log('重置管理员密码');
        return $this->success();
    }

    public function quickForbidden(Admin $admin, Request $request)
    {
        if($admin->id === 1) {
            throw new ServiceException(SystemConstant::CANT_EDIT_SYSTEM_ITEM);
        }
        $admin->status = !$admin->status;
        $admin->save();
        activity()->inLog('update')->performedOn($admin)->causedBy($request->user())->withProperties(['status' => $admin->status])->log('快速更新管理员账号状态');
        return $this->success();
    }

}
