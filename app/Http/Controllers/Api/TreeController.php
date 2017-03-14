<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use App\Repositories\TreeRepository;

class TreeController extends ApiController
{
    protected $tree;

    public function __construct(TreeRepository $tree) {
        parent::__construct();
        $this->tree = $tree;
    }

    public function me(){
        // 1.获取当前用户的角色身份
        $user = Auth::user() ?: '';
        $roles = $user ? $user->roles : 'guest';
        $tree = $this->tree->getVisibleTree($roles);
        $tree = json_encode($tree);
        // $this->
        return $tree;
        // return $this->respondWithItem($tree);
        // return view('index',compact('tree'));
    }
}
