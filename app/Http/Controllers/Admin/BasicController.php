<?php

namespace App\Http\Controllers\Admin;


use App\Constants\SystemConstant;
use App\Exceptions\ServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function dateCenter(Request $request)
    {
        $motto = get_motto();
        $system_info = [
            [
                'key' => '操作系统',
                'value' => PHP_OS,
            ],
            [
                'key' => '剩余空间',
                'value' => human_filesize(disk_free_space('.')),
            ],
            [
                'key' => '当前设置域名',
                'value' => config('app.url'),
            ],
            [
                'key' => '运行环境',
                'value' => $request->server('SERVER_SOFTWARE'),
            ],
            [
                'key' => 'PHP版本',
                'value' => PHP_VERSION,
            ],
            [
                'key' => 'PHP运行方式',
                'value' => php_sapi_name(),
            ],
            [
                'key' => 'Mysql数据库版本',
                'value' => function_exists('mysql_get_server_info')?mysql_get_server_info():DB::select('SELECT VERSION() as mysql_version')[0]->mysql_version,
            ],
            [
                'key' => 'Laravel版本',
                'value' => app()::VERSION,
            ],
            [
                'key' => '上传附件限制',
                'value' => ini_get('upload_max_filesize'),
            ],
            [
                'key' => '执行时间限制',
                'value' => ini_get('max_execution_time').'秒',
            ],
        ];
        return View::make('admin.index', compact('motto', 'system_info'));
    }

    public function getMotto()
    {
        $motto = get_motto();
        return $this->successData(compact('motto'));
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
