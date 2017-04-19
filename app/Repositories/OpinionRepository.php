<?php

namespace App\Repositories;

use DB;
use App\User;
use App\Opinion;
use App\Notifications\PendReview;
use App\Notifications\AuditResults;
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
        $applicat = $this->applicat->getById($input['applicat_id']);
        $applicat->stage = $applicat->stage+1;
        if($input['radio'] == '通过') {
            //获取需要经过角色组的审核数
            $roles_count = $applicat->type()
                                      ->withCount('roles')
                                        ->first()
                                          ->roles_count;
            //判断当前是否是最终审核
            if($roles_count == $applicat->stage) {
                $status = DB::table('statuses')
                  ->where('name', '审核通过')
                    ->first();
                $applicat->status_id = $status->id;
                $this->reviewEndNotificat($applicat->user_id, $applicat);
            }else if($applicat->stage < $roles_count){
                //找出下一审核组成员，并发送邮件提示
                $users = $applicat->type->roles()
                  ->wherePivot('priority',$applicat->stage)
                    ->first()
                      ->users;
                if(!$users) $users = User::find(1);
                \Notification::send($users, new PendReview($applicat));

                //如果是刚刚通过，将该申请状态调整为审核中
                if($applicat->stage < 2) {
                    $status = DB::table('statuses')->where('name', '审核中')->first();
                    $applicat->status_id = $status->id;
                }
            }else{
                //获取添加异常类
                return;
            }
        }else{
            $status = DB::table('statuses')->where('name', '审核不通过')->first();
            $applicat->status_id = $status->id;
        }
        $applicat->save();
        //将当前审核人的意见添加进数据库
        unset($input['radio']);
        $input['user_id'] = auth()->id;
        $this->save($this->model, $input);
    }

    public function reviewEndNotificat($user_id, $applicat)
    {
        $user = User::find($user_id);
        $user->notify(new AuditResults($applicat));
    }
}
