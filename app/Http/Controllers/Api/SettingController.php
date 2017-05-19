<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class SettingController extends ApiController
{

    protected $user;


    public function __construct(UserRepository $user)
    {
        parent::__construct();

        $this->user = $user;

    }

    /**
     * Set the email notification.
     *
     * @param Request $request [description]
     * @return  Redirect
     */
    public function setNotification(Request $request)
    {
        $input = [
            'email_notify_enabled' => $request->get('email_notify_enabled') ? 'yes' : 'no'
        ];

        $this->user->update(\Auth::id(), $input);

        return $this->noContent();
    }
}
