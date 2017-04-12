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

    /**
     * Get number of the records
     *
     * @param  int $number
     * @param  string $sort
     * @param  string $sortColumn
     * @return Paginate
     */
    public function page($number = 10, $sort = 'desc', $sortColumn = 'created_at')
    {
        //获取当前登录的用户角色
        $roles = \Auth::user()->roles()->with('types')->get();
        $typeIds = [];
        //遍历角色获取每一个角色对应的申请类型
        foreach($roles as $role) {
            foreach( $role->types as $type) {
                // 获取该类型对应的申请
                $typeIds[] = $type->id;
            }
        }
        $applicats = $this->model->whereIn('type_id',$typeIds)->with('user', 'mechanism', 'type', 'status', 'opinions')->orderBy($sortColumn, $sort)->paginate($number);
        return $applicats;
    }

    public function getByUserId($user_id, $number = 10, $sort = 'desc', $sortColumn = 'created_at')
    {
        return $this->model->where('user_id',$user_id)->with('user', 'mechanism', 'type', 'status')->orderBy($sortColumn, $sort)->paginate($number);
    }

    /**
     * all type
     *
     * @return Eloquent
     */
    public function all()
    {
        return $this->model->get();
    }

    public function store($input)
    {
        if(!is_numeric($input['mechanism_id'])){
            $input['mechanism_id'] = \DB::table('mechanisms')->insertGetId(
                ['name' => $input['mechanism_id']]
            );
        }
        if(!$input['unite']) {
            $input['agency'] = '';
        }

        $input['files'] = json_encode(array_pluck($input['fileList'],'response'));
        $input['type_id'] = $input['type_id'][count($input['type_id'])-1];
        $input['user_id'] = \Auth::user()->id;
        return $this->save($this->model, $input);
    }
}
