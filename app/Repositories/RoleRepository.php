<?php

namespace App\Repositories;

use App\Role;

class RoleRepository
{
    use BaseRepository;

    protected $model;

    public function __construct(Role $role){
        $this->model = $role;
    }

}
