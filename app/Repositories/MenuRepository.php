<?php

namespace App\Repositories;

use App\Menu;
use Auth;

class MenuRepository
{
    use BaseRepository;

    protected $model;

    public function __construct(Menu $menu)
    {
        $this->model = $menu;
    }

    /**
     * 获取当然用户可见的树
     * @param  [type] $roles [description]
     * @return [type]        [description]
     */
    public function getVisibleTree($roles)
    {
        $items = array();
        foreach($roles as $key=>$role) {
            $items[$key] = $role->menus()->get(['id','title','icon','parent_id','uri']);
        }
        //当用户拥有多个角色，则将其每个角色对应的树合并。
        $items = array_unique(array_flatten($items));
        $menu = $this->generateTree($items);
        return $menu;
    }



    /**
     * 生成树
     * @param  array $items 可见的菜单项
     * @return array
     */
    function generateTree($items)
    {
        $menus = array();
        foreach($items as $item){
            $menus[$item['id']] = $item->toArray();
            $menus[$item['id']]['items'] = array();
            if(!empty($item['parent_id'])) {
                $menus[$item['parent_id']]['items'][$item['id']] = $menus[$item['id']];
            }
        }
        foreach ($menus as $k=>$v) {
            if(!empty($v['parent_id'])) {
                unset($menus[$k]);
            }
        }
        return $menus;
    }

    // /**
    //  * 多级菜单数据查询及展示
    //  *
    //  * @return array
    //  */
    // public function  getAllList()
    // {
    //     $lists = $this->model::all();
    //     $list = array();
    //     if(!empty($lists)){
    //         foreach($lists as $v) {
    //             $list[$v['parent_id']][] = $v;
    //         }
    //     }
    //     return $link;
    // }
    //
    //
    // /**
    //  * Get all the records
    //  *
    //  * @return array User
    //  */
    // public function all()
    // {
    //     return $this->model->get();
    // }
}
