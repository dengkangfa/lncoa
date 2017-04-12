<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        'name', 'describe', 'parent_id', 'disabled'
    ];

    /**
     * User association
     * @return eloquent
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role')->withPivot('priority')->withTimestamps();
    }

    public function startRole()
    {
        return $this->belongsToMany('App\Role')->wherePivot('priority', 0);
    }

    public function applicats()
    {
        return $this->hasMany('App\Applicat');
    }
}
