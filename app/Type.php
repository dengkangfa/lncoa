<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        'name', 'describe', 'parent_id'
    ];

    /**
     * User association
     * @return eloquent
     */
    public function roles() {
        return $this->belongsToMany('App\Role')->withPivot('priority')->withTimestamps();
    }
}
