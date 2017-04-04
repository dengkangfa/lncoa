<?php

namespace App\Repositories;

use App\Applicat;

class ApplicatRepository
{
    use BaseRepository;

    protected $model;

    public function __construct(Applicat $applicat) {
        $this->model = $applicat;
    }

    public function store($input)
    {
        if(!is_numeric($input['mechanism_id'])){
            $input['mechanism_id'] = \DB::table('mechanisms')->insertGetId(
                ['name' => $input['mechanism_id']]
            );
        }
        $input['files'] = json_encode($input['fileList']);
        $input['type_id'] = $input['type_id'][count($input['type_id'])-1];
        $input['user_id'] = \Auth::user()->id;
        return $this->save($this->model, $input);
    }
}
