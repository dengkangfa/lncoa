<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use App\Repositories\MenuRepository;
use App\Transformers\MenuTransformer;

class MenuController extends ApiController
{
    protected $menu;

    public function __construct(MenuRepository $menu)
    {
        parent::__construct();
        $this->menu = $menu;
    }

    public function me()
    {
        // 1.获取当前用户的角色身份
        $user = Auth::user() ?: '';
        $roles = $user ? $user->roles : 'guest';
        $menu = $this->menu->getVisibleTree($roles);
        $menu = json_encode($menu);
        return $menu;
    }

    public function index()
    {
        return $this->respondWithCollection($this->menu->all(), new MenuTransformer);
    }
}
