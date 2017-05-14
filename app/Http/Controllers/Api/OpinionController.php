<?php

namespace App\Http\Controllers\Api;

use App\Applicat;
use Illuminate\Http\Request;
use App\Repositories\OpinionRepository;
use App\Transformers\ApplicatTransformer;

class OpinionController extends ApiController
{
    protected $opinion;

    public function __construct(OpinionRepository $opinion)
    {
        parent::__construct();

        $this->opinion = $opinion;
    }
    public function store(Request $request)
    {
        $this->opinion->store($request->all());

        return $this->noContent();
    }
}
