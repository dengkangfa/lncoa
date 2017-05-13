<?php

namespace App\Transformers;

use App\Appraisal;
use League\Fractal\TransformerAbstract;

class AppraisalTransformer extends TransformerAbstract
{

    public function transform(Appraisal $appraisal)
    {
        return [
            'id' => $appraisal->id,
            'appraisal' => $appraisal->appraisal,
            'score' => $appraisal->score,
            'user' => $appraisal->user,
            'applicat' => $appraisal->applicat,
            'created_at' => $appraisal->created_at->toDateTimeString(),
            'updated_at' => $appraisal->updated_at->toDateTimeString(),
        ];
    }

}
