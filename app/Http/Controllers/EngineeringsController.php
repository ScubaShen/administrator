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
        $engineerings = $engineering
                        ->where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);
        return view('engineerings.index', compact('engineerings'));
    }

    public function show(Engineering $engineering, Request $request)
    {
        if ($request->getJson) {
            $user_id = $engineering->user_id;
            $engineering['user_name'] = User::find($user_id)->name;
            return $engineering;
        }
        $engineerings = Engineering::query()
                            ->where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->paginate(20);
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

        return redirect()->to(route('engineerings.show', $engineering->id))->with('success', '创建成功');
    }

    public function edit(Engineering $engineering)
    {
        $this->authorize('update', $engineering);
        $engineering->start_at = str_replace(" ", "T", $engineering->start_at);
        $engineering->finish_at = str_replace(" ", "T", $engineering->finish_at);

        return view('engineerings.create_and_edit', compact('engineering'));
    }

    public function update(Engineering $engineering, Request $request)
    {
        $this->authorize('update', $engineering);
        $engineering->update($request->all());

        return redirect()->route('engineerings.show', $engineering->id)->with('success', '更新成功');
    }

    public function destroy(Engineering $engineering)
    {
        $this->authorize('destroy', $engineering);
        $engineering->delete();

        return redirect()->route('engineerings.index')->with('success', '成功删除');
    }
}
