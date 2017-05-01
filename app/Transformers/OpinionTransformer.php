<?php

namespace App\Transformers;

use App\Opinion;
use League\Fractal\TransformerAbstract;

class OpinionTransformer extends TransformerAbstract
{
    public function transform(Opinion $opinion)
    {
        return [
            'opinion' => $opinion->opinion,
            'user' => $opinion->user,
            'files' => $opinion->files,
            'create_at' => $opinion->created_at->toDateTimeString(),
        ];
    }
}
