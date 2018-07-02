<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EngineeringRequest;
use App\Models\Engineering;
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

    public function show(Engineering $engineering)
    {
        $engineerings = Engineering::paginate(20);
        $specificEngineering = $engineering;
        return view('engineerings.index', compact('engineerings', 'specificEngineering'));
    }

    public function create(Engineering $engineering)
    {
        return view('engineerings.create_and_edit', compact('engineering'));
    }

    public function store(EngineeringRequest $request, Engineering $engineering)
    {
        $engineering->fill($request->all());
        $engineering->user_id = Auth::id();
        $engineering->save();

        return redirect()->route('engineerings.index', $engineering->id)->with('success', '创建成功');
    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
