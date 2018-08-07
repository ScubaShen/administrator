<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\MemberRequest;
use Auth;

class MembersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function index()
	{
        $members = $this->getMembers();

		return view('members.index', compact('members'));
	}

    public function show(Member $member)
    {
        $members = $this->getMembers();

        $currentMember = $member;

        return view('members.index', compact('members', 'currentMember'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('members.create_and_edit', compact('roles'));
    }

    public function store(EngineeringRequest $request, Engineering $engineering)
    {
        $engineering->fill($request->all());
        $engineering->user_id = Auth::id();
        $engineering->company_id = Auth::user()->company_id;
        $engineering->save();

        return redirect()->to(route('engineerings.show', $engineering->id))->with('success', '创建成功');
    }

    public function edit(Engineering $engineering)
    {
        $this->authorize('own', $engineering);
        $engineering->start_at = str_replace(" ", "T", $engineering->start_at);
        $engineering->finish_at = str_replace(" ", "T", $engineering->finish_at);

        $supervisions = Supervision::all();

        return view('engineerings.create_and_edit', compact('engineering', 'supervisions'));
    }

    public function update(Engineering $engineering, Request $request)
    {
        $this->authorize('own', $engineering);
        $engineering->update($request->all());

        return redirect()->route('engineerings.show', $engineering->id)->with('success', '更新成功');
    }

    public function getView(Member $member)
    {
        $member['role_name'] = $member->role->name;

        return $member;
    }

    public function getResults(PaginateRequest $request)
    {
        $user_ids = $this->getUserIdsByCurrentCompany();

        $results = Engineering::query()->whereIn('user_id', $user_ids);

        $total = $results->count();
        $lastpage = ceil($total/$request->rows_per_page);
        $page = $request->page > $lastpage ? $lastpage : (int)$request->page;
        $per_page = $request->rows_per_page;

        Cookie::queue('paginate', json_encode(['engineerings' => compact('page', 'per_page')]), 60);

        $results = $results
            ->with('supervision')
            ->orderBy('created_at', 'desc')
            ->offset(($page-1) * $per_page)
            ->limit($per_page)
            ->get();

        return compact('results', 'total', 'page', 'lastpage');
    }

    public function destroyAll(EngineeringRequest $request)
    {
        $engineerings = Engineering::find($request->ids);

        foreach($engineerings as $engineering){
            $this->authorize('own', $engineering);
        }

        Engineering::destroy($request->ids);
        return [];
    }

    public function search(SearchRequest $request)
    {
        $user_ids = $this->getUserIdsByCurrentCompany();

        $results = Engineering::query()
            ->whereIn('user_id', $user_ids)
            ->with('supervision')
            ->where('name','like','%'.$request->name.'%')
            ->orderBy('created_at', 'desc');

        if($request->start_at) {
            $results = $results->whereBetween('start_at', [$request->start_at, $request->end_at]);
        }

        $total = $results->count();

        $per_page = $request->rows_per_page;

        if($total == 0) {
            $lastpage = 1;
            $page = 1;
            $results = [];
        } else {
            $lastpage = ceil($total/$per_page);
            $page = $request->page > $lastpage ? $lastpage : (int)$request->page;
            $results = $results->offset(($page-1) * $per_page)->limit($per_page)->get();
        }

        return compact('results', 'total', 'page', 'lastpage');
    }

    protected function getMembers()
    {
        //获取分页信息
        $paginate = request()->cookie('paginate') ? json_decode(request()->cookie('paginate')) : [];

        $company_id = Auth::user()->company_id;

        $members = Member::query()
            ->where('company_id', $company_id)
            ->with('company')
            ->orderBy('created_at', 'desc');

        if(array_key_exists('users', $paginate)) {
            return $members->paginate($paginate->users->per_page, ['*'], 'page', $paginate->users->page);
        }

        return $members->paginate(10);
    }
}