<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Mail\RegisterShipped;
use App\Notifications\VerifyMail;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /**
     * check Email name the unique
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function check(Request $request)
    {
        $validator = \Validator::make(
          ["{$request->rule}" => $request->{$request->rule}],
          ["{$request->rule}" => "required|unique:users"],
          ['email.required' => '我们需要知道你的 e-mail 地址！',
           'email.unique' => '邮件已被注册！']
        );

        if($validator->fails()) {
            return response()->json([
                    'success' => false,
                    'errors'  => $validator->getMessageBag()->toArray()
                ]);
        }else{
            return response()->json([
                    'success' => true,
                ]);
        }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
          $this->validator($request->all())->validate();

          $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
          ]);
          $role = \App\Role::where('name','=','owner')->first();
          $user->attachRole($role);

          $this->sendEmailConfirmationTo($user);

          // $this->login($user);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return \Validator::make($data, [
            'name' => 'required|min:2|max:16|unique:users|regex:/^[\x{4E00}-\x{9FA5A}-Za-z0-9]+$/u',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * 发送邮箱操作
     * @param  [type] $user [需要接收邮箱的对象]
     * @return [type]       [description]
     */
    protected function sendEmailConfirmationTo($user)
    {
      // $user->notify(new VerifyMail($user));
      \Mail::to($user)->send(new RegisterShipped($user));
    }

    protected function login($user)
    {
        route('login',[
          'grant_type' => 'password',
          'client_id' => '2',
          'client_secret' => 'OkABZOuxDMaiaaFJBrESpYnmIMf6eSwyU42fPVdM',
          'name' => $user->email,
          'password' => $user->password,
        ]);
    }
}
