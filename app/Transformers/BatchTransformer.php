<?php

namespace App\Transformers;

use App\Models\Batch;
use League\Fractal\TransformerAbstract;

class BatchTransformer extends TransformerAbstract
{
    protected $type;

    protected $availableIncludes = ['user', 'engineering', 'company'];

    public function __construct($type = false)
    {
        $this->type = $type;
    }

    public function transform(Batch $batch)
    {
        if($this->type) {
            return [
                'id' => $batch->id,
                'name' => $batch->name,
                'user_id' => (int) $batch->user_id,
                'engineering_id' => (int) $batch->engineering_id,
                'company_id' => (int) $batch->company_id,
                'longitude' => $batch->longitude,
                'latitude' => $batch->latitude,
                'range' => $batch->range,
                'safe_distance'=> $batch->safe_distance,
                'description' => $batch->description,
                'technicians' => $batch->technicians,
                'custodians' => $batch->custodians,
                'safety_officers' => $batch->safety_officers,
                'powdermen' => $batch->powdermen,
                'manager' => $batch->manager,
                'materials' => $batch->materials,
                'finished' => $batch->finished,
                'start_at' => $batch->start_at,
                'finish_at' => $batch->finish_at,
                'created_at' => $batch->created_at->toDateTimeString(),
                'updated_at' => $batch->updated_at->toDateTimeString(),

            ];
        }
        return [
            'id' => $batch->id,
            'name' => $batch->name,
            'user_id' => (int) $batch->user_id,
            'engineering_id' => (int) $batch->engineering_id,
            'company_id' => (int) $batch->company_id,
            'longitude' => $batch->longitude,
            'latitude' => $batch->latitude,
            'range' => $batch->range,
            'safe_distance'=> $batch->safe_distance,
            'description' => $batch->description,
            'groups' => $batch->groups,
            'materials' => $batch->materials,
            'finished' => $batch->finished,
            'start_at' => $batch->start_at,
            'finish_at' => $batch->finish_at,
            'created_at' => $batch->created_at->toDateTimeString(),
            'updated_at' => $batch->updated_at->toDateTimeString(),
        ];
    }

    public function includeUser(Batch $batch)
    {
        return $this->item($batch->user, new UserTransformer());
    }

    public function includeEngineering(Batch $batch)
    {
        return $this->item($batch->engineering, new EngineeringTransformer());
    }

    public function includeCompany(Batch $batch)
    {
        return $this->item($batch->company, new CompanyTransformer());
    }
}