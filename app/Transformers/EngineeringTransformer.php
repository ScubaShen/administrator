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
            'user_id' => (int) $engineering->user_id,
            'supervision_id' => (int) $engineering->supervision_id,
            'company_id' => (int) $engineering->company_id,
            'description' => $engineering->description,
            'created_at' => $engineering->created_at->toDateTimeString(),
            'updated_at' => $engineering->updated_at->toDateTimeString(),
        ];
    }
}