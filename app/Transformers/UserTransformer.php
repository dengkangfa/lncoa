<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'roles'
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
}
