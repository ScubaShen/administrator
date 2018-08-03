<?php

namespace App\Http\Controllers\Api;

use App\Models\Batch;
use App\Models\User;
use App\Transformers\BatchTransformer;
use App\Http\Requests\Api\BatchRequest;

class BatchesController extends Controller
{
    public function index(Batch $batch)
    {
        $user_ids = $this->getUserIdsByCurrentCompany();

        $batches = $batch
            ->whereIn('user_id', $user_ids)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return $this->response->paginator($batches, new BatchTransformer());
    }

    public function userIndex(User $user)
    {
        $batches = $user->batches()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return $this->response->paginator($batches, new BatchTransformer());
    }

    public function show(Batch $batch)
    {
        $this->authorize('ownCompany', $batch);

        $this->getGroupsSpelling($batch);

        return $this->response->item($batch, new BatchTransformer(true));
    }

    public function store(BatchRequest $request, Batch $batch)
    {
        $technicians = $request->technicians;
        $custodians = $request->custodians;
        $safety_officers = $request->safety_officers;
        $powdermen = $request->powdermen;
        $manager = $request->manager;
        $detonator = (int) $request->detonator;
        $dynamite = (int) $request->dynamite;

        $batch->fill($request->all());
        $batch->groups = compact('technicians', 'custodians', 'safety_officers', 'powdermen', 'manager');
        $batch->materials = compact('detonator', 'dynamite');
        $batch->user_id = $this->user()->id;
        $batch->company_id = $this->user()->company_id;
        $batch->save();

        return $this->response->item($batch, new BatchTransformer())
            ->setStatusCode(201);
    }

    public function update(BatchRequest $request, Batch $batch)
    {
        $this->authorize('own', $batch);

        if($request->technicians) {
            $batch->groups['technicians'] = $request->technicians;
        }
        if($request->custodians) {
            $batch->groups['custodians'] = $request->custodians;
        }
        if($request->safety_officers) {
            $batch->groups['safety_officers'] = $request->safety_officers;
        }
        if($request->powdermen) {
            $batch->groups['powdermen'] = $request->powdermen;
        }
        if($request->manager) {
            $batch->groups['manager'] = $request->manager;
        }
        if($request->detonator) {
            $batch->materials['detonator'] = $request->detonator;
        }
        if($request->dynamite) {
            $batch->materials['dynamite'] = $request->dynamite;
        }

        $batch->fill($request->all());
        $batch->save();

        return $this->response->item($batch, new BatchTransformer());
    }

    public function destroy(Batch $batch)
    {
        $this->authorize('own', $batch);

        $batch->delete();
        return $this->response->noContent();
    }

    protected function getUserIdsByCurrentCompany()
    {
        $company_id = $this->user()->company_id;

        $user_ids = User::query()->where('company_id', $company_id)->pluck('id')->toArray();

        return $user_ids;
    }

    protected function getGroupsSpelling($batch)
    {
        $user = app(User::class);
        // 取出各个职位的人名，拼成 A, B, C, ... 的形式
        foreach($batch->groups as $position => $users_array) {
            $batch[$position] = '';
            is_array($users_array) ?: $users_array = [$users_array];

            foreach ($user->find($users_array) as $oneUser) {
                $batch[$position] .= $oneUser->realname . ', ';
            }
            $batch[$position] = rtrim($batch[$position], ', ');
        }

        return $batch;
    }
}
