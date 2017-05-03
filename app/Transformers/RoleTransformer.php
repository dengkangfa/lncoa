<?php

namespace App\Transformers;

use App\Role;
use App\Permission;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'menus',
        'permissions'
    ];

    public function transform(Role $role)
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'display_name' => $role->display_name,
            'description' => $role->description,
            'created_at' => $role->created_at->toDateTimeString(),
        ];
    }

    public function includeMenus(Role $role)
    {
        $menus = $role->menus;

        return $this->collection($menus, new MenuTransformer);
    }

    public function includePermissions(Role $role)
    {
        $perms = $role->permissions;
        \Log::info($perms);

        return $this->collection($perms, new PermissionTransformer);
    }
}
