<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Mail\RegisterShipped;
use App\Notifications\VerifyMail;
use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;

class RegisterController extends ApiController
{
    protected $user;

    public function __construct(UserRepository $user)
    {
        parent::__construct();

        $this->user = $user;
    }
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

          $identicon = new \Identicon\Identicon();
          $data = array_merge($request->all(), [
              'avatar' => $identicon->getImageDataUri($request->name,80),
              'status' => true
          ]);

          $user = $this->user->store($data);

          $role = \App\Role::where('name','=','owner')->first();
          $user->attachRole($role);

          // $this->sendEmailConfirmationTo($user);

          return $this->login($request);
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
            'password_confirmation' => 'required',
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

    protected function login($request)
    {
        $data = $request->all();
        $request->request->add([
            'grant_type' => 'password',
            'client_id' => '1',
            'client_secret' => 'nu7fyK26YDz6w9d6uE4jRifTSxthB4RVmRHlYMD3',
            'username' => $data['name'],
            'password' => $data['password'],
            'scope' => ''
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        $response = \Route::dispatch($proxy);
        $token = json_decode($response->getContent());
        $token->status = 'success';

        return response()->json($token);
    }
}
