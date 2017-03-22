<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
        'title',
        'description',
        'uri',
        'parentId'
    ];

    protected $hidden=['pivot'];

    /**
     * 不可批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [
      'id'
    ];
}
