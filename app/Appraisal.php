<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    protected $fillable = [
        'appraisal', 'score', 'user_id', 'applicat_id'
    ];

    /**
     * 获取拥有该评价的申请模型。
     */
    public function applicat()
    {
        return $this->belongsTo('App\Applicat');
    }

    /**
     * 获取拥有该评价的用户模型。
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
