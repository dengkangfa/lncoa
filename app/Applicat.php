<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicat extends Model
{
    protected $fillable = [
        'principal', 'mobile', 'mechanism_id', 'number', 'type_id',
        'startTime', 'endTime', 'agency', 'reason', 'goods', 'files', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function type()
    {
        return $this->belongsTo('App\Type');
    }

    public function menchanism()
    {
        return $this->belongsTo('App\Mechcnism');
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }
}
