<?php

namespace App\Transformers;

use App\Applicat;
use League\Fractal\TransformerAbstract;

class ApplicatTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'roles',
        'opinions',
        'appraisal'
    ];

    public function transform(Applicat $applicat)
    {
        return [
            'id' => $applicat->id,
            'user' => $applicat->user->name,
            'principal' => $applicat->principal,
            'mobile' => $applicat->mobile,
            'mechanism' => $applicat->mechanism->name,
            'number' => $applicat->number,
            'type' => $applicat->type->name,
            'startTime' => $applicat->startTime,
            'endTime' => $applicat->endTime,
            'agency' => $applicat->agency,
            'reason' => $applicat->reason,
            'goods' => $applicat->goods,
            'files' => $applicat->files,
            'stage' => $applicat->stage,
            'status' => $applicat->status,
            'created_at' => $applicat->created_at->toDateTimeString(),
            'updated_at' => $applicat->updated_at->toDateTimeString(),
        ];
    }

    public function includeRoles(Applicat $applicat)
    {
        $roles = $applicat->type->roles;
        //根据中间表排序
        $roles = array_values(array_sort($roles, function ($value) {
            return $value['pivot']->priority;
        }));

        return $this->collection($roles, new RoleTransformer);
    }

    public function includeOpinions(Applicat $applicat)
    {
        $opinions = $applicat->opinions;

        return $this->collection($opinions, new OpinionTransformer);
    }

    public function includeAppraisal(Applicat $applicat)
    {
        $appraisal = $applicat->appraisal;
        if(empty($appraisal)){
          return null;
        }

        return $this->item($appraisal, new AppraisalTransformer);
    }

}
