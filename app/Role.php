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

    // protected $hidden = [
    //     'pivot'
    // ];

    //Big block of caching functionality.
    // public function cachedPermissions()
    // {
    //     $rolePrimaryKey = $this->primaryKey;
    //     $cacheKey = 'entrust_permissions_for_role_'.$this->$rolePrimaryKey;
    //     return Cache::tags(Config::get('entrust.permission_role_table'))->remember($cacheKey, Config::get('cache.ttl'), function () {
    //         return $this->perms()->get();
    //     });
    // }

    public function menus()
    {
        return $this->belongsToMany('App\Menu')->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Permission')->withTimestamps();
    }

    public function types()
    {
        return $this->belongsToMany('App\Type')->withTimestamps()->withPivot('priority');
    }

    public function applicats()
    {
        return $this->hasManyThrough('App\Applicat', 'App\Type');
    }

}
