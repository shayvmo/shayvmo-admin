<?php

use Illuminate\Database\Migrations\Migration;

class SeedSuperPermission extends Migration
{
    private $permissions;

    private $permissions_arr;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->permissions = config('permissions', []);

        if ($this->permissions) {
            /** @var \App\Models\Role $role */
            $role = \App\Models\Role::create([
                'name' => 'super-admin',
                'guard_name' => config('auth.defaults.guard'),
                'title' => '超级管理员',
                'desc' => '',
            ]);

            $admin = \App\Models\Admin::create([
                'username' => 'admin',
                'password' => \App\Constants\BackendConstant::DEFAULT_PASSWORD,
                'nickname' => '超级管理员',
            ]);

            $this->savePermission($this->permissions);

            $admin->assignRole($role);

            $this->permissions_arr && $role->givePermissionTo($this->permissions_arr);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->permissions = config('permissions', []);

        if ($this->permissions) {
            \App\Models\Permission::query()
                ->where('guard_name', config('auth.defaults.guard'))
                ->whereIn('name', array_column($this->permissions, 'name'))
                ->delete();

            \App\Models\Role::query()
                ->where('name', 'super-admin')
                ->where('guard_name', config('auth.defaults.guard'))
                ->delete();
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
