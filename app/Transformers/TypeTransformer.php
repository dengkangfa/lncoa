<?php

namespace App\Transformers;

use App\Type;
use League\Fractal\TransformerAbstract;

class TypeTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'roles',
    ];

    public function transform(Type $type)
    {
        return [
            'id' => $type->id,
            'name' => $type->name,
            'describe' => $type->describe,
            'disabled' => $type->disabled,
            'parent_id' => $type->parent_id,
        ];
    }

    public function includeRoles(Type $type)
    {
        $roles = $type->roles;

        return $this->collection($roles, new RoleTransformer);
    }

}
