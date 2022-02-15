<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshSuperAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rsap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refresh super admin permissions';

    protected $permissions_arr = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $permissions = config('permissions', []);

        if ($permissions) {

            Artisan::call('cache:forget spatie.permission.cache');

            /** @var \App\Models\Role $role */
            $guard = config('auth.defaults.guard');
            $role = \App\Models\Role::where([
                'name' => 'super-admin',
                'guard_name' => $guard,
            ])->first();

            // 清空权限表
            Permission::where([
                'guard_name' => $guard,
            ])->delete();
            $this->savePermission($permissions);
            $this->permissions_arr && $role->syncPermissions($this->permissions_arr);

            $this->info("重置刷新数据库表权限成功!");
        }
    }

    private function savePermission(array $permissions, int $pid = 0)
    {
        foreach ($permissions as $item) {
            $temp = [
                'pid' => $pid,
                'name' => $item['name'],
                'guard_name' => config('auth.defaults.guard'),
                'title' => $item['title'],
                'route' => $item['route'],
                'icon' => $item['icon'],
                'type' => $item['type'],
                'is_menu' => $item['is_menu'],
                'sort' => $item['sort'],
            ];
            $permission = \App\Models\Permission::create($temp);
            $permission->path = ($permission->pid > 0 ? $permission->parent->path : '0').'-'.$permission->id;
            $permission->save();
            $this->permissions_arr[] = $item['name'];
            if (!empty($item['children'])) {
                $this->savePermission($item['children'], $permission->id);
            }
        }

    }
}
