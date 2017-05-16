<?php

namespace App\Policies;

use App\User;
use App\Applicat;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicatPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function updateUser(User $user, Applicat $applicat)
    {
        return $user->id == $applicat->user_id;
    }

    public function end(User $user, Applicat $applicat)
    {
        $roleId = [];
        foreach($user->roles as $role) {
           $roleId[] = $role->id;
        }
        return in_array($applicat->role_id,$roleId);
    }

    public function appraisal(User $user, Applicat $applicat)
    {
        $roleId = [];
        foreach($user->roles as $role) {
           $roleId[] = $role->id;
        }
        return in_array($applicat->role_id,$roleId);
    }

    public function forward(User $user, Applicat $applicat)
    {
        $roleId = [];
        foreach($user->roles as $role) {
           $roleId[] = $role->id;
        }
        return in_array($applicat->role_id,$roleId);
    }
}
