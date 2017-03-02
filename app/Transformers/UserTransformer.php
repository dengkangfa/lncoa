<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'avatar' => $user->avatar,
            'name' => $user->name,
            'status' => $user->status,
            'email' => $user->email,
            'nickname' => $user->nickname,
            'description' => $user->description,
            'created_at' => $user->created_at->toDateTimeString(),
        ];
    }
}
