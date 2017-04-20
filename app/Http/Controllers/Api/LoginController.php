<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends ApiController
{

    use AuthenticatesUsers;

    public function login(Request $request)
    {
        //获取用户名和密码
        $credentials = $this->credentials($request);
        \Log::info($credentials);

        //验证用户名和密码的正确性
        if ($this->guard('api')->attempt($credentials, $request->has('remember'))) {
            return $this->sendLoginResponse($request);
        }

        return \Response::json([
            'status' => "error",
            'message' => "用户名或密码有误"
        ], 403);
    }

    protected function sendLoginResponse(Request $request)
    {
        //清除登录尝试
        $this->clearLoginAttempts($request);

        return $this->authenticated($request);
    }

    /**
     * 覆盖原生的认证
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    protected function authenticated(Request $request)
    {
        return $this->authenticateClient($request);
    }

    protected function authenticateClient(Request $request)
    {
        $data = $request->all();
        if ($request->refresh_token) {
            $request->request->add([
                'grant_type' => $data['grant_type'],
                'client_id' => $data['client_id'],
                'client_secret' => $data['client_secret'],
                'username' => $data['email'],
                'password' => $data['password'],
                'refresh_token' => $data['refresh_token'],
                'scope' => ''
            ]);
        } else {
            $request->request->add([
                'grant_type' => $data['grant_type'],
                'client_id' => $data['client_id'],
                'client_secret' => $data['client_secret'],
                'username' => $data['email'],
                'password' => $data['password'],
                'scope' => ''
            ]);
        }

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        $response = \Route::dispatch($proxy);
        $token = json_decode($response->getContent());
        $token->user = $request->user();
        $token->status = 'success';

        return response()->json($token);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }
}
