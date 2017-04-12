<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * new user confirm email
     * @return [type] [description]
     */
    public function confirmEmail(Request $request)
    {
      $user = \App\User::where('activation_token',$request->token)->firstOrFail();
      //
      // $user->status = 0;
      // $user->activation_token = null;
      // $user->save();
      //
      // Auth::login($user);
    }
}
