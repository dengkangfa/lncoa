<?php

namespace App\Transformers;

use App\Applicat;
use League\Fractal\TransformerAbstract;

class DateTakeUpTransformer extends TransformerAbstract
{

    public function transform(Applicat $applicat)
    {
        return [
            'startTime' => $applicat->startTime,
            'endTime' => $applicat->endTime
        ];
    }
}
