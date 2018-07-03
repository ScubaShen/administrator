<?php

namespace App\Transformers;

use App\Models\Engineering;
use League\Fractal\TransformerAbstract;

class EngineeringTransformer extends TransformerAbstract
{
    public function transform(Engineering $engineering)
    {
        return [
            'id' => $engineering->id,
            'name' => $engineering->name,
            'user' => $engineering->user_id,
            'supervision' => $engineering->supervision_id,
            'description' => $engineering->description,
            'start_at' => $engineering->start_at,
            'finish_at' => $engineering->finish_at,
            'created_at' => $engineering->created_at->toDateTimeString(),
            'updated_at' => $engineering->updated_at->toDateTimeString(),
        ];
    }
}