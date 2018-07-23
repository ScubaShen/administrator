<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EngineeringRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Engineering;
use App\Models\User;
use App\Models\Batch;
use App\Models\Supervision;
use Auth;
use App\Handlers\ImageUploadHandler;
use Illuminate\Support\Facades\Cookie;

class TestsController extends Controller
{
    function forTest(Batch $batch)
    {
        $batch->user_id = 1;
        $batch->company_id = User::find($batch->user_id)->company_id;
        $user_group_ids =  User::where('company_id', $batch->company_id)->pluck('id')->toArray();

        dd($user_group_ids);

        return [];
    }
}