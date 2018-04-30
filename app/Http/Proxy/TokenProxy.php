<?php

namespace App\Http\Proxy;

use GuzzleHttp\Client;

class TokenProxy
{
    protected $http;

    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    public function login($username, $password)
    {
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (auth('web')->attempt([$field => $username, 'password' => $password])) {
            return $this->proxy('password', [
                'username' => $username,
                'password' => $password,
                'scope' => ''
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => '该账号不存在或者密码错误',
            'code' => 10421
        ], 421);
    }
    
    public function logout()
    {
        $user = auth()->guard('api')->user();
        
        if (is_null($user)) {
            app('cookie')->queue(app('cookie'))->forget('refreshToken');

            return response()->json([
                'message' => 'Logout!'
            ], 204);
        }

        $accessToken = $user->token();

        app('db')->table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        app('cookie')->queue(app('cookie'))->forget('refreshToken');

        $accessToken->revoke();

        return response()->json([
            'message' => 'Logout!'
        ], 204);
    }
    
    public function refresh()
    {
        $refreshToken = request()->cookie('refreshToken');
        
        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken
        ]);
    }

    public function proxy($grantType, array $data = [])
    {
        $data = array_merge($data, [
            'client_id' => env('PASSPORT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_CLIENT_SECRET'),
            'grant_type' => $grantType
        ]);

        $response = $this->http->post(env('APP_URL') . '/oauth/token', [
            'form_params' => $data,
            'timeout' => 20
        ]);
        
        $token = json_decode((string) $response->getBody(), true);
        
        return response()->json([
            'access_token' => $token['access_token'],
            'auth_id' => md5($token['refresh_token']),
            'expired_in' => $token['expires_in']
        ])->cookie('refreshToken', $token['refresh_token'], 14400, null, null, false, true);
    }
}