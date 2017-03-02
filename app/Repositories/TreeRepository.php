<?php

namespace App\Repositories;

use App\Tree;
use Auth;

class TreeRepository
{
    use BaseRepository;

    protected $model;

    public function __construct(Tree $tree) {
        $this->model = $tree;
    }

    /**
     * 获取当然用户可见的树
     * @param  [type] $roles [description]
     * @return [type]        [description]
     */
    public function getVisibleTree($roles) {
        $items = array();
        foreach($roles as $key=>$role) {
            $items[$key] = $role->trees()->get(['id','title','icon','parent_id','uri']);
        }
        //当用户拥有多个角色，则将其每个角色对应的树合并。
        $items = array_unique(array_flatten($items));
        $tree = $this->generateTree($items);
        return $tree;
    }


    /**
     * 生成树
     * @param  array $items 可见的菜单项
     * @return array
     */
    function generateTree($items){
        $tree = array();
        foreach($items as $item){
            $tree[$item['id']] = $item->toArray();
            $tree[$item['id']]['items'] = array();
            if(!empty($item['parent_id'])) {
                $tree[$item['parent_id']]['items'][$item['id']] = $tree[$item['id']];
            }
        }
        foreach ($tree as $k=>$v) {
            if(!empty($v['parent_id'])) {
                unset($tree[$k]);
            }
        }
        return $tree;
    }

    /**
     * 多级菜单数据查询及展示
     *
     * @return array
     */
    public function  getAllList() {
        $lists = $this->model::all();
        $list = array();
        if(!empty($lists)){
            foreach($lists as $v) {
                $list[$v['parent_id']][] = $v;
            }
        }
        return $link;
    }
}
