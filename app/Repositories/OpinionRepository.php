<?php

namespace App\Repositories;

use DB;
use Auth;
use App\User;
use App\Opinion;
use App\Notifications\PendReview;
use App\Notifications\ReviewResults;
use App\Repositories\ApplicatRepository;

class OpinionRepository
{
    use BaseRepository;

    protected $model;

    protected $applicat;

    public function __construct(Opinion $opinion, ApplicatRepository $applicat)
    {
        $this->model = $opinion;

        $this->applicat = $applicat;
    }

    public function store($input)
    {
        // 获取申请对象
        $applicat = $this->applicat->getById($input['applicat_id']);
        if($applicat->stage === null) {
            $applicat->status = '审核通过';
        }else{
          $applicat->stage = $applicat->stage+1;
          if($input['radio'] == '通过') {
              //获取需要经过角色组的审核数
              $roles_count = $applicat->type()
                                        ->withCount('roles')
                                          ->first()
                                            ->roles_count;
              //判断当前是否是最终审核
              if($roles_count == $applicat->stage) {
                  $applicat->status = '审核通过';
              }else if($applicat->stage < $roles_count){
                  //找到下一角色组
                  $role = $applicat->type->roles()
                    ->wherePivot('priority',$applicat->stage)
                      ->first();
                  $users = null;
                  if(!is_null($role) || !is_null($role) && !is_null($role->users)){
                      //下一个审核的角色组
                      $applicat->role_id = $role->id;
                      //找出下一审核组成员，并发送邮件提示
                      $users = $role->users;
                  }else{
                      $users = User::find(1);
                  }
                  \Notification::send($users, new PendReview($applicat));

                  //如果是刚刚通过，将该申请状态调整为审核中
                  if($applicat->stage < 2) {
                      $applicat->status = '审核中';
                  }
              }else{
                  //获取添加异常类
                  return;
              }
          }else{
              $applicat->status = '审核不通过';
          }
        }
        //将当前审核人的意见添加进数据库
        unset($input['radio']);
        $input['user_id'] = Auth::id();
        $input['files'] = json_encode(array_pluck($input['fileList'],'response'));
        $this->save($this->model, $input);
        $applicat->save();
        //邮件提醒
        $this->reviewEndNotificat($applicat->user_id, $applicat);
    }

    /**
     * 转发评论
     * @param  int $applicat_id
     * @param  array $input
     */
    public function forwardOpinion($applicat_id, $input)
    {
        $input['user_id'] = Auth::id();
        $input['applicat_id'] = $applicat_id;
        $this->save($this->model, $input);
    }

    /**
     * 发送审核结束邮件
     * @param  [type] $user_id  [description]
     * @param  [type] $applicat [description]
     * @return [type]           [description]
     */
    public function reviewEndNotificat($user_id, $applicat)
    {
        $user = User::find($user_id);
        $user->notify(new ReviewResults($applicat));
    }
}
