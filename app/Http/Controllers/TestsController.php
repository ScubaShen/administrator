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
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function forTest(Batch $batch)
    {
        $id = 2001;
//       $batch->getAttributeValue();
        $model = $batch->find($id);
        $model->setAppends([]);
        dd($model);
        return view('test.test', compact('users_array'));
    }
}