<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use App\Repositories\RoleRepository;
use App\Transformers\RoleTransformer;
use App\Transformers\UserTransformer;

class RoleController extends ApiController
{
    protected $role;

    public function __construct(RoleRepository $role)
    {
        parent::__construct();

        $this->role = $role;
    }

    public function index(Request $request)
    {
        if($request->has('pageSize')){
            return $this->respondWithPaginator($this->role->page($request->pageSize), new RoleTransformer);
        }
        return $this->respondWithPaginator($this->role->page(), new RoleTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TagRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $this->role->store($request->all());

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
        return $this->respondWithItem($this->role->getById($id), new RoleTransformer);
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
        $this->role->update($id, $request->all());

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
      $role = $this->role->getById($id);
      if ($role->name == 'admin' && $role->id == 1) {
          return $this->errorForbidden(trans('notification.delete_role_error'));
      }
      $this->role->destroy($id);

      return $this->noContent();
    }

    public function users($id)
    {
        $users = $this->role->getByIdToUsers($id);

        return $this->respondWithCollection($users, new UserTransformer);
    }

}
