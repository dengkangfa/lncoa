<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MechanismController extends Controller
{
    public function index()
    {
        return \DB::table('mechanisms')->select('id', 'name')->get();
    }
}
