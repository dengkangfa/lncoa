<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'roles',
        'menus',
        'permissions'
    ];

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'avatar' => $user->avatar,
            'name' => $user->name,
            'mobile' => $user->mobile,
            'email' => $user->email,
            'status' => $user->status,
            'nickname' => $user->nickname,
            'description' => $user->description,
            'created_at' => $user->created_at->toDateTimeString(),
        ];
    }

    public function includeRoles(User $user)
    {
        $roles = $user->roles;

        return $this->collection($roles, new RoleTransformer);
    }

    /**
     * 获取用户关联的菜单
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function includeMenus(User $user)
    {
        $menus = $user->menus();

        return $this->collection($menus, new MenuTransformer);
    }

    public function includePermissions(User $user)
    {
        $permissions = $user->permissions();

        return $this->collection($permissions, new PermissionTransformer);
    }
}
