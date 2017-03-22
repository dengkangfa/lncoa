<?php

namespace App\Transformers;

use App\Menu;
use League\Fractal\TransformerAbstract;

class MenuTransformer extends TransformerAbstract
{

    public function transform(Menu $menu)
    {
        return [
            'id' => $menu->id,
            'title' => $menu->title,
            'description' => $menu->description,
            'icon' => $menu->icon,
            'parent_id' => $menu->parent_id,
            'uri' => $menu->uri,
            'created_at' => $menu->created_at->toDateTimeString(),
        ];
    }

}
