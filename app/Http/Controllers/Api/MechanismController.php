<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class MechanismController extends ApiController
{
    public function index()
    {
        return \DB::table('mechanisms')->select('id', 'name')->get();
    }
}
