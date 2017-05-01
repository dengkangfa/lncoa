<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicat extends Model
{
    protected $fillable = [
        'principal', 'mobile', 'mechanism_id', 'number', 'type_id',
        'startTime', 'endTime', 'agency', 'reason', 'goods', 'files',
        'role_id', 'user_id'
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

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function opinions()
    {
        return $this->hasMany('App\Opinion');
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
