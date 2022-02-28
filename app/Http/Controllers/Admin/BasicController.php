<?php

namespace App\Http\Controllers\Admin;


use App\Constants\SystemConstant;
use App\Exceptions\ServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ChangePasswordRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class BasicController extends Controller
{
    public function layout()
    {
        $guard = Auth::user();
        return View::make('admin.layout',compact('guard'));
    }

    public function dateCenter()
    {
        $motto = get_motto();
        return View::make('admin.index', compact('motto'));
    }

    //后台用户菜单
    public function menu()
    {
        $permissions = Auth::user()->getAllPermissions()->toArray();
        $menuPermissions = array_filter($permissions, function ($item) {
            return $item['type'] === 1 && $item['is_menu'] === 1;
        });
        $menuSort = array_column($menuPermissions, 'sort');
        array_multisort($menuSort, SORT_DESC, $menuPermissions);
        $permissions = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'pid' => $item['pid'],
                'title' => $item['title'],
                'type' => $item['route'] !== '' ? 1 : 0,
                'icon' => 'layui-icon ' . ($item['icon'] ?: 'layui-icon-face-cry'),
                'openType' => $item['route'] !== '' ? "_iframe" : "",
                'href' => $item['route'] !== '' ? route($item['route']) : '',
            ];
        }, $menuPermissions);
        return Response::json(get_data_tree($permissions));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('admin.admin.profile',compact('user'));
    }

    public function pwdPage()
    {
        return view('admin.admin.password');
    }

    public function updatePwd(ChangePasswordRequest $request)
    {
        [
            'old_password' => $old_password,
            'new_password' => $new_password
        ] = $request->fillData();
        $admin = Auth::user();
        if (!Hash::check($old_password, $admin->password)) {
            throw new ServiceException(SystemConstant::WRONG_PASSWORD);
        }
        $admin->password = $new_password;
        $admin->saveOrFail();
        return $this->success();
    }

    public function demo()
    {
        return view('admin.demo');
    }

}
