<?php

namespace App\Http\Controllers;

use App\Models\Supervision;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupervisionRequest;

class SupervisionsController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth', ['except' => ['index', 'show']]);
//    }
//
//	public function index()
//	{
//		$supervisions = Supervision::paginate();
//		return view('supervisions.index', compact('supervisions'));
//	}
//
//    public function show(Supervision $supervision)
//    {
//        return view('supervisions.show', compact('supervision'));
//    }
//
//	public function create(Supervision $supervision)
//	{
//		return view('supervisions.create_and_edit', compact('supervision'));
//	}
//
//	public function store(SupervisionRequest $request)
//	{
//		$supervision = Supervision::create($request->all());
//		return redirect()->route('supervisions.show', $supervision->id)->with('message', 'Created successfully.');
//	}
//
//	public function edit(Supervision $supervision)
//	{
//        $this->authorize('update', $supervision);
//		return view('supervisions.create_and_edit', compact('supervision'));
//	}
//
//	public function update(SupervisionRequest $request, Supervision $supervision)
//	{
//		$this->authorize('update', $supervision);
//		$supervision->update($request->all());
//
//		return redirect()->route('supervisions.show', $supervision->id)->with('message', 'Updated successfully.');
//	}
//
//	public function destroy(Supervision $supervision)
//	{
//		$this->authorize('destroy', $supervision);
//		$supervision->delete();
//
//		return redirect()->route('supervisions.index')->with('message', 'Deleted successfully.');
//	}
}