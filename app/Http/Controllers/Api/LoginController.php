<?php

namespace App\Http\Controllers\Api;

use App\Http\Proxy\TokenProxy;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends ApiController
{

    use AuthenticatesUsers;

    protected $proxy;

    public function __construct(TokenProxy $proxy)
    {
        $this->proxy = $proxy;
    }

    public function login(LoginRequest $request)
    {
        return $this->proxy->login($request->username, $request->password);
    }

    public function refresh()
    {
        return $this->proxy->refresh();
    }

    public function logout()
    {
        return $this->proxy->logout();
    }
}
