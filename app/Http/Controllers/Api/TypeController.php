<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\TypeRequest;
use App\Repositories\TypeRepository;
use App\Transformers\TypeTransformer;

class TypeController extends ApiController
{

    protected $type;

    /**
     * construct
     * @param typeRepository
     */
    public function __construct(TypeRepository $type)
    {
        parent::__construct();

        $this->type = $type;
    }

    /**
     * add type
     * @param  typeRequest
     * @return json
     */
    public function store(TypeRequest $request)
    {
        $this->type->store($request->all());

        // return $this->noContent();
        return get_attr($this->type->all(), null);
    }

    public function index(Request $request)
    {
        if($request->structure == 'tree'){
          return get_attr($this->type->all(), null);
        }
        return $this->respondWithCollection($this->type->all(), new typeTransformer);
    }

    public function edit($id)
    {
        return $this->respondWithItem($this->type->getById($id), new typeTransformer);
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
        $this->type->update($id, $request->all());

        return $this->noContent();
    }

    public function updateSorts(Request $request)
    {
        return $this->type->updateSorts($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->type->destroy($id);

        // return $this->noContent();
        return get_attr($this->type->all(), null);
    }

}
