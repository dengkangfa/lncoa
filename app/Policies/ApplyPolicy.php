<?php

namespace App\Policies;

use App\User;
use App\Applicat;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the applicat.
     *
     * @param  \App\User  $user
     * @param  \App\Applicat  $applicat
     * @return mixed
     */
    public function view(User $user, Applicat $applicat)
    {
        //
    }

    /**
     * Determine whether the user can create applicats.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the applicat.
     *
     * @param  \App\User  $user
     * @param  \App\Applicat  $applicat
     * @return mixed
     */
    public function update(User $user, Applicat $applicat)
    {
        //
    }

    /**
     * Determine whether the user can delete the applicat.
     *
     * @param  \App\User  $user
     * @param  \App\Applicat  $applicat
     * @return mixed
     */
    public function softDeletes(User $user, Applicat $applicat)
    {
        return $user->id === $applicat->user_id;
    }
}
