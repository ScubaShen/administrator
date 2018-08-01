<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests\EngineeringRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Engineering;
use App\Models\User;
use App\Models\Batch;
use App\Models\Company;
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

    public function forTest(Request $request)
    {
        $company_id = $request->get('q') ?: 1;
        $users = User::where('company_id', $company_id)->get(['id', 'name', 'role_id']);
        $engineerings = Engineering::where('company_id', $company_id)->get(['id', 'name']);

        foreach($users as $index => $user) {
            $groups_load_array[$user->role_id][$index]['id'] = $user->id;
            $groups_load_array[$user->role_id][$index]['text'] = $user->name;
        }

        foreach($engineerings as $index => $engineering) {
            $groups_load_array[0][$index]['id'] = $engineering->id;
            $groups_load_array[0][$index]['text'] = $engineering->name;
        }

        return $groups_load_array;
        //return view('test.test', compact('users_array'));
    }
}