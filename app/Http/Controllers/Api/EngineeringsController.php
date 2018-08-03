<?php

namespace App\Http\Controllers\Api;

use App\Models\Engineering;
use App\Models\User;
use Illuminate\Http\Request;
use App\Transformers\EngineeringTransformer;
use App\Http\Requests\Api\EngineeringRequest;

class EngineeringsController extends Controller
{
    public function index(Engineering $engineering)
    {
        $user_ids = $this->getUserIdsByCurrentCompany();

        $engineerings = $engineering
            ->whereIn('user_id', $user_ids)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return $this->response->paginator($engineerings, new EngineeringTransformer());
    }

    public function userIndex(User $user)
    {
        $engineerings = $user->engineerings()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return $this->response->paginator($engineerings, new EngineeringTransformer());
    }

    public function show(Engineering $engineering)
    {
        $this->authorize('ownCompany', $engineering);
        
        return $this->response->item($engineering, new EngineeringTransformer());
    }

    public function store(EngineeringRequest $request, Engineering $engineering)
    {
        $engineering->fill($request->all());
        $engineering->user_id = $this->user()->id;
        $engineering->company_id = $this->user()->company_id;
        $engineering->save();

        return $this->response->item($engineering, new EngineeringTransformer())
            ->setStatusCode(201);
    }

    public function update(EngineeringRequest $request, Engineering $engineering)
    {
        $this->authorize('own', $engineering);

        $engineering->update($request->all());
        return $this->response->item($engineering, new EngineeringTransformer());
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
}
