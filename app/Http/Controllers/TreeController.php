<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Repositories\TreeRepository;

class TreeController extends Controller
{
    protected $tree;

    public function __construct(TreeRepository $tree) {
        $this->tree = $tree;
    }
    public function index(){
        // 1.获取当前用户的角色身份
        $user = Auth::user() ?: '';
        $roles = $user ? $user->roles : 'guest';
        $tree = $this->tree->getVisibleTree($roles);
        $tree = json_encode($tree);
        return view('index',compact('tree'));
    }
}
