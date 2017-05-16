<?php

namespace App\Http\Controllers\Auth;

use Germey\Geetest\Geetest;
use Germey\Geetest\GeetestController;

class GeetestCaptchaController extends GeetestController
{
    /**
     * Get geetest.
     */
    public function getGeetest()
    {
        $user_id = "lncoa";
        $status = Geetest::preProcess($user_id);
        $result = Geetest::getResponseStr();
        $array = json_decode($result,true);
        $array['gtserver'] = $status;
        $array['user_id'] = $user_id;
        return $array;
    }
}
