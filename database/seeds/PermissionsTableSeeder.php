<?php

use App\Role;
use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createPost = new Permission();
        $createPost->name         = 'create-user';
        $createPost->display_name = '创建用户'; // optional
        $createPost->description  = '创建用户的权限'; // optional
        $createPost->save();

        $deleteUser = new Permission();
        $deleteUser->name       = 'delete-user';
        $deleteUser->display_name = '删除用户'; // optional
        $deleteUser->description  = '删除用户的权限'; // optional
        $deleteUser->save();

        $editUser = new Permission();
        $editUser->name         = 'update-user';
        $editUser->display_name = '修改用户'; // optional
        $editUser->description  = '修改用户的权限'; // optional
        $editUser->save();

        $updateUserStatus = new Permission();
        $updateUserStatus->name       = 'update-user-status';
        $updateUserStatus->display_name = '激活用户'; // optional
        $updateUserStatus->description  = '激活用户的权限'; // optional
        $updateUserStatus->save();

        $createRole = new Permission();
        $createRole->name       = 'create-role';
        $createRole->display_name = '创建角色'; // optional
        $createRole->description  = '创建角色的权限'; // optional
        $createRole->save();

        $deleteRole = new Permission();
        $deleteRole->name       = 'delete-role';
        $deleteRole->display_name = '删除角色'; // optional
        $deleteRole->description  = '删除角色的权限'; // optional
        $deleteRole->save();

        $updateRole = new Permission();
        $updateRole->name       = 'update-role';
        $updateRole->display_name = '修改角色'; // optional
        $updateRole->description  = '修改角色的权限'; // optional
        $updateRole->save();


        $createPermission = new Permission();
        $createPermission->name       = 'create-permission';
        $createPermission->display_name = '创建权限'; // optional
        $createPermission->description  = '创建权限的权利'; // optional
        $createPermission->save();

        $updatePermission = new Permission();
        $updatePermission->name       = 'update-permission';
        $updatePermission->display_name = '修改权限'; // optional
        $updatePermission->description  = '修改权限的权利'; // optional
        $updatePermission->save();

        $deletePermission = new Permission();
        $deletePermission->name       = 'delete-permission';
        $deletePermission->display_name = '删除权限'; // optional
        $deletePermission->description  = '删除权限的权利'; // optional
        $deletePermission->save();

        $selectPermission = new Permission();
        $selectPermission->name       = 'index-permission';
        $selectPermission->display_name = '查看权限'; // optional
        $selectPermission->description  = '查看权限的权利'; // optional
        $selectPermission->save();

        $showCard = new Permission();
        $showCard->name       = 'show-card';
        $showCard->display_name = '面板卡片可见'; // optional
        $showCard->description  = '面板卡片可见'; // optional
        $showCard->save();

        $showChart = new Permission();
        $showChart->name       = 'show-chart';
        $showChart->display_name = '折线图可见'; // optional
        $showChart->description  = '折线图可见'; // optional
        $showChart->save();

        $showNotice = new Permission();
        $showNotice->name       = 'show-notice';
        $showNotice->display_name = '通告可见'; // optional
        $showNotice->description  = '通告可见'; // optional
        $showNotice->save();

        $showAccessLog = new Permission();
        $showAccessLog->name       = 'show-access-log';
        $showAccessLog->display_name = '访问历史可见'; // optional
        $showAccessLog->description  = '访问历史可见'; // optional
        $showAccessLog->save();

        $admin = Role::where('name', 'admin')->first();
        $owner = Role::where('name', 'owner')->first();

        $permissions = Permission::all();
        foreach($permissions as $permission) {
            $admin->attachPermission($permission);
        }
        // equivalent to $admin->perms()->sync(array($createPost->id));

        // $owner->attachPermissions(array($createPost, $editUser));
        // factory(Permission::class, 10)->create();
    }
}
