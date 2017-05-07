<?php

namespace App\Repositories;

use App\Type;

class TypeRepository
{
    use BaseRepository;

    /**
     * Site Model
     *
     * @var site
     */
    protected $model;

    /**
     * Construct
     *
     * @param Site
     */
    public function __construct(Type $type)
    {
        $this->model = $type;
    }

    /**
     * site create
     * @param  input
     * @return
     */
    public function store($input)
    {
        if(count($input['parent_id'])){
            $input['parent_id'] = $input['parent_id'][count($input['parent_id'])-1];
        }else{
            $input['parent_id'] = null;
        }
        //审核人
        $array = $input['approvers'];
        unset($input['approvers']);
        $this->save($this->model, $input);
        foreach($array as $key=>$value){
          if(!empty($value['id'])){
              //priority为优先级
              $this->model->roles()->attach($value['id'], ['priority' => $key]);
          }
        }
    }

    /**
     * update sorts
     * @param  input
     * @return 重新排序的结果
     */
    public function updateSorts($input)
    {
        $this->sort($input,null,"");
        return get_attr($this->all()->toArray(),null);
    }

    public function update($id, $input)
    {
        //获取当前id对应的资源
        $this->model = $this->model->findOrFail($id);
        $array = $input['approvers'];
        unset($input['approvers']);
        $this->save($this->model, $input);
        $temp = [];
        foreach($array as $key=>$value){
            $temp["$value"] =  ['priority' => $key];
        }
        $this->model->roles()->sync($temp);
    }

    /**
     * recursively sort
     *
     * @param  children
     * @param  pid
     * @param  sorts
     * @return
     */
    public function sort($children,$pid,$sorts)
    {
      //排序字符串，根据ascii排序
      $qsorts = $sorts!="" ? $sorts."-" : $sorts;
      foreach($children as $key => $value) {
          $sorts = $qsorts.$key;
          if(isset($value['children'])){
              $this->sort($value['children'], $value['id'],$sorts);
          }
          $input = ['parent_id' => $pid, 'sorts' => $sorts];
          $this->updateColumn($value['id'],$input);
      }
    }

    public function destroy($id)
    {
        $this->model->where('id', $id)->orWhere('parent_id', $id)->delete();
    }

    /**
     * all type
     *
     * @return Eloquent
     */
    public function all()
    {
        return $this->model->orderBy('sorts')->get();
    }
}
