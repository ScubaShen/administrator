<?php

namespace App\Transformers;

use App\Models\Engineering;
use League\Fractal\TransformerAbstract;

class EngineeringTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'supervision', 'company'];

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

    public function includeUser(Engineering $engineering)
    {
        return $this->item($engineering->user, new UserTransformer());
    }

    public function includeSupervision(Engineering $engineering)
    {
        return $this->item($engineering->supervision, new SupervisionTransformer());
    }

    public function includeCompany(Engineering $engineering)
    {
        return $this->item($engineering->company, new CompanyTransformer());
    }
}