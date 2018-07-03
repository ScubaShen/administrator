<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EngineeringRequest;
use App\Models\Engineering;
use App\Models\User;
use Auth;

class EngineeringsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Engineering $engineering)
    {
        $engineerings = $engineering->paginate(20);
        return view('engineerings.index', compact('engineerings'));
    }

    public function show(Engineering $engineering, Request $request)
    {
        if ($request->getJson) {
            $user_id = $engineering->id;
            $engineering['user_name'] = User::find($user_id)->name;
            return $engineering;
        }
        $engineerings = Engineering::paginate(20);
        $specificEngineering = $engineering;
        return view('engineerings.index', compact('engineerings', 'specificEngineering'));
    }

    public function create()
    {
        return view('engineerings.create_and_edit');
    }

    public function store(EngineeringRequest $request, Engineering $engineering)
    {
        $engineering->fill($request->all());
        $engineering->user_id = Auth::id();
        $engineering->save();

        return redirect()->route('engineerings.index', $engineering->id)->with('success', '创建成功');
    }

    public function edit(Engineering $engineering)
    {
        return view('engineerings.create_and_edit', compact('engineering'));
    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
