<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BatchRequest;

class BatchesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index()
	{
		$batches = Batch::paginate();
		return view('batches.index', compact('batches'));
	}

    public function show(Batch $batch)
    {
        return view('batches.show', compact('batch'));
    }

	public function create(Batch $batch)
	{
		return view('batches.create_and_edit', compact('batch'));
	}

	public function store(BatchRequest $request)
	{
		$batch = Batch::create($request->all());
		return redirect()->route('batches.show', $batch->id)->with('message', 'Created successfully.');
	}

	public function edit(Batch $batch)
	{
        $this->authorize('update', $batch);
		return view('batches.create_and_edit', compact('batch'));
	}

	public function update(BatchRequest $request, Batch $batch)
	{
		$this->authorize('update', $batch);
		$batch->update($request->all());

		return redirect()->route('batches.show', $batch->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Batch $batch)
	{
		$this->authorize('destroy', $batch);
		$batch->delete();

		return redirect()->route('batches.index')->with('message', 'Deleted successfully.');
	}
}