<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogController extends ApiController
{
    public function errorLog()
    {
        $monolog = Log::getMonolog();
    }
}
