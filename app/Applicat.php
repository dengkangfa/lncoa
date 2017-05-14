<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicat extends Model
{
    use SoftDeletes;

    /**
     * 需要被转换成日期的属性。
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'principal', 'mobile', 'mechanism_id', 'number', 'type_id',
        'startTime', 'endTime', 'agency', 'reason', 'goods', 'files',
        'role_id', 'user_id', 'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function type()
    {
        return $this->belongsTo('App\Type');
    }

    public function mechanism()
    {
        return $this->belongsTo('App\Mechanism');
    }

    public function opinions()
    {
        return $this->hasMany('App\Opinion');
    }

    public function appraisal()
    {
        return $this->hasOne('App\Appraisal');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        //当有新的申请进入时，提醒第一审核人
        // static::creating(function ($applicat){
        //     $users = \App\Type::find($applicat->type_id)->startRole()->first()->users;
        //     \Notification::send($users, new pendReview($applicat));
        // });
    }
}
