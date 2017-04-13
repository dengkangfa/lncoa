<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;

class NotificatController extends ApiController
{
    public function index()
    {
        $user = Auth::user();
        return $user->unreadNotifications;
    }

    public function destroy($id)
    {
        \DB::table('notifications')->where('id', $id)->delete();
        // $user = Auth::user();
        // foreach ($user->unreadNotifications as $key => $value) {
        //   \Log::info($value->data['applicat_id']);
        //     if($value->data['applicat_id'] == $id) {
        //         $user->notifications()->where('id',$value->id)->delete();
        //     }
        // }
    }
}
