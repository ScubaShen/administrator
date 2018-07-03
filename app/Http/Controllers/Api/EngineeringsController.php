<?php

namespace App\Http\Controllers\Api;

use App\Models\Engineering;
use Illuminate\Http\Request;
use App\Transformers\EngineeringTransformer;


class EngineeringsController extends Controller
{
    public function index()
    {
        return $this->response->collection(Engineering::all(), new EngineeringTransformer());
    }

    public function show(Engineering $engineering)
    {
        return $this->response->item($engineering, new EngineeringTransformer());
    }
}
