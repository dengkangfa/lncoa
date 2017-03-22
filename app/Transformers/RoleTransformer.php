<?php

namespace App\Transformers;

use App\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'menus',
        'perms'
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
}
