<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\RoleRepository;
use App\Transformers\RoleTransformer;

class RoleController extends ApiController
{
    protected $role;

    public function __construct(RoleRepository $role)
    {
        parent::__construct();

        $this->role = $role;
    }

    public function index()
    {
        return $this->respondWithPaginator($this->role->page(), new RoleTransformer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->respondWithItem($this->role->getById($id), new RoleTransformer);
    }

}
