<?php

namespace App\Transformers;

use App\Models\Supervision;
use League\Fractal\TransformerAbstract;

class SupervisionTransformer extends TransformerAbstract
{
    public function transform(Supervision $supervision)
    {
        return [
            'id' => $supervision->id,
            'name' => $supervision->name,
            'description' => $supervision->description,
            'created_at' => $supervision->created_at->toDateTimeString(),
            'updated_at' => $supervision->updated_at->toDateTimeString(),
        ];
    }
}