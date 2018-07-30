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
        $users = User::where('company_id', 2)->select('id', 'role_id')->get()->toArray();
        foreach($users as $user){
            $users_array[$user['role_id']][] = (String)$user['id'];
        }
        dd($users_array);
        return view('test.test', compact('users_array'));
    }
}