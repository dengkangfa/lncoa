<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\OpinionRepository;

class OpinionController extends ApiController
{
    protected $opinion;

    public function __construct(OpinionRepository $opinion)
    {
        $this->opinion = $opinion;
    }
    public function store(Request $request)
    {
        $this->opinion->store($request->all());

        return $this->noContent();
    }
}
