<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opinion extends Model
{
    protected $fillable = [
        'opinion', 'applicat_id', 'user_id', 'files'
    ];

    public function applicat()
    {
        return $this->belongsTo('App\Applicat');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
