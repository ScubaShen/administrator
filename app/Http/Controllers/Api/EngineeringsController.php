<?php

namespace App\Http\Controllers\Api;

use App\Models\Engineering;
use App\Models\User;
use Illuminate\Http\Request;
use App\Transformers\EngineeringTransformer;


class EngineeringsController extends Controller
{
    public function index(Engineering $engineering)
    {
        $user_ids = $this->getUserIdsByCurrentCompany();

        $engineerings = $engineering
            ->whereIn('user_id', $user_ids)
            ->with('supervision')
            ->orderBy('created_at', 'desc')
            ->get();
        return $this->response->collection($engineerings, new EngineeringTransformer());
    }

    public function show(Engineering $engineering)
    {
        return $this->response->item($engineering, new EngineeringTransformer());
    }

    protected function getUserIdsByCurrentCompany()
    {
        $company_id = $this->user()->company_id;

        $user_ids = User::query()->where('company_id', $company_id)->pluck('id')->toArray();

        return $user_ids;
    }
}
