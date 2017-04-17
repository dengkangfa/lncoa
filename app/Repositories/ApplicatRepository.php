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
        $roleId = [];
        //遍历角色获取每一个角色对应的申请类型
        $applicats = [];
        foreach($roles as $role) {
            foreach( $role->types as $type) {
                $roleId[] = $role->id;
                // 获取该类型对应的申请
                $typeIds[] = $type->id;
            }
        }
        $roleId = implode(',', $roleId);
        //获取到当前用户审核的申请
        $applicats = $this->model
                    ->whereIn('type_id',$typeIds)
                    ->where('stage',\DB::raw(
                      "(select priority from role_type r where
                        r.type_id = applicats.type_id
                        and (r.role_id in ($roleId)) limit 1)"
                      ))
                      ->with('user', 'mechanism', 'type', 'status', 'opinions')
                        ->orderBy($sortColumn, $sort)
                         ->paginate($number);
        return $applicats;
    }

    /**
     * 获取自己的申请
     * @param  intrger  $user_id
     * @param  integer $number
     * @param  string  $sort
     * @param  string  $sortColumn
     * @return
     */
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

    /**
     * 新建申请
     * @param  array
     * @return interger
     */
    public function store($input)
    {
        //判断当前结构是否是自定义，如果是则在机构表中新建
        if(!is_numeric($input['mechanism_id'])){
            //插入并返回自增id值
            $input['mechanism_id'] = \DB::table('mechanisms')->insertGetId(
                ['name' => $input['mechanism_id']]
            );
        }
        //判断是否需要物资
        if(!$input['unite']) {
            $input['agency'] = '';
        }

        $input['files'] = json_encode(array_pluck($input['fileList'],'response'));
        $input['type_id'] = $input['type_id'][count($input['type_id'])-1];
        $input['user_id'] = \Auth::user()->id;
        return $this->save($this->model, $input);
    }
}
