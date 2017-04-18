<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\PermissionRequest;
use App\Repositories\PermissionRepository;
use App\Transformers\PermissionTransformer;

class PermissionController extends ApiController
{
    protected $permission;

    public function __construct(PermissionRepository $permission)
    {
        parent::__construct();

        $this->middleware('permission:index-permission')->only('index,show');
        $this->middleware('permission:create-permission')->only('store');
        $this->middleware('permission:delete-permission')->only('destroy');

        $this->permission = $permission;
    }

    public function index(Request $request)
    {
        if($request->has('pageSize')){
          return $this->respondWithPaginator($this->permission->page($request->pageSize), new PermissionTransformer);
        }
        return $this->respondWithPaginator($this->permission->page(), new PermissionTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TagRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        $this->permission->store($request->all());

        return $this->noContent();
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TagRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->permission->update($id, $request->all());

        return $this->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->permission->destroy($id);

        return $this->noContent();
    }
}
