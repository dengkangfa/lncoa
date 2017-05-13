<?php

namespace App\Repositories;

use App\Appraisal;

class AppraisalRepository
{
    use BaseRepository;

    protected $model;

    public function __construct(Appraisal $appraisal){
        $this->model = $appraisal;
    }

}
