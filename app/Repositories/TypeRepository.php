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
        $array = $input['approvers'];
        unset($input['approvers']);
        $this->save($this->model, $input);
        foreach($array as $key=>$value){
          if(!empty($value['id'])){
              $this->model->roles()->attach($value['id'], ['priority' => $key]);
          }
        }
    }

    public function updateSorts($input)
    {
        $this->sort($input,null,"");
        return get_attr($this->all()->toArray(),null);
    }

    public function sort($children,$pid,$sorts)
    {
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

    public function all()
    {
        return $this->model->orderBy('sorts')->get();
    }
}
