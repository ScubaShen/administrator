<?php

namespace App\Http\Controllers\Api;

use App\Models\Batch;
use App\Models\Engineering;
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
        $batch->fill($request->all());
        $batch->user_id = $this->user()->id;
        $batch->company_id = $this->user()->company_id;
        $batch->save();

        return $this->response->item($batch, new BatchTransformer())
            ->setStatusCode(201);
    }

    public function update(EngineeringRequest $request, Engineering $engineering)
    {
        $this->authorize('own', $engineering);

        $engineering->update($request->all());
        return $this->response->item($engineering, new BatchTransformer());
    }

    public function destroy(Engineering $engineering)
    {
        $this->authorize('own', $engineering);

        $engineering->delete();
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
