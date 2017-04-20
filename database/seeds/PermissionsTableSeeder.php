<?php

use App\Permission;
use App\Role;
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

        $editUser = new Permission();
        $editUser->name         = 'update-user';
        $editUser->display_name = '修改用户'; // optional
        $editUser->description  = '修改用户的权限'; // optional
        $editUser->save();

        $deleteUser = new Permission();
        $deleteUser->name       = 'delete-user';
        $deleteUser->display_name = '删除用户'; // optional
        $deleteUser->description  = '删除用户的权限'; // optional
        $deleteUser->save();

        $createRole = new Permission();
        $createRole->name       = 'create-role';
        $createRole->display_name = '创建角色'; // optional
        $createRole->description  = '创建角色的权限'; // optional
        $createRole->save();

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
        $selectPermission->name       = 'select-permission';
        $selectPermission->display_name = '查看权限'; // optional
        $selectPermission->description  = '查看权限的权利'; // optional
        $selectPermission->save();

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
