<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\PermissionRepository;
use App\Transformers\PermissionTransformer;

class PermissionController extends ApiController
{
    protected $permission;

    public function __construct(PermissionRepository $permission)
    {
        parent::__construct();

        $this->permission = $permission;
    }

    public function index()
    {
        return $this->respondWithPaginator($this->permission->page(), new PermissionTransformer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->respondWithItem($this->permission->getById($id), new PermissionTransformer);
    }
}
