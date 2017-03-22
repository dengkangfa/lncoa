<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    protected $hidden = [
        'pivot'
    ];

    public function menus() {
        return $this->belongsToMany('App\Menu')->withTimestamps();
    }
}
