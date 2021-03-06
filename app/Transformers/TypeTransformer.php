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
            'date_unique' => (bool) $type->date_unique,
            'disabled' => (bool) $type->disabled,
            'parent_id' => $type->parent_id,
        ];
    }

    public function includeRoles(Type $type)
    {
        $roles = $type->roles()->orderBy('priority')->get();

        return $this->collection($roles, new RoleTransformer);
    }

}
