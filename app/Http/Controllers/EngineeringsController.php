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

    public function index()
    {
        return view('engineerings.index');
    }

    public function show(Engineering $engineering)
    {
        return view('engineerings.show', compact('engineering'));
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

        return redirect()->route('engineerings.show', $engineering->id)->with('success', '创建成功');
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
