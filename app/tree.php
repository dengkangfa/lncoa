<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tree extends Model
{
    protected $fillable = [
        'title', 'description', 'uri'
    ];
}
