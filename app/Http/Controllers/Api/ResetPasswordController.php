<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends ApiController
{
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        Mail::to($request->get('email'))->send(new OrderShipped($order));
    }
}
