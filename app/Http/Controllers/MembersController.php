<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\MemberRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SearchRequest;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

class MembersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function index()
	{
        $members = $this->getMembers();

        $roles = Role::all();

		return view('members.index', compact('members', 'roles'));
	}

    public function show(Member $member)
    {
        $members = $this->getMembers();

        $currentMember = $member;

        $roles = Role::all();

        return view('members.index', compact('members', 'currentMember', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('members.create_and_edit', compact('roles'));
    }

    public function store(Request $request, Member $member)
    {
        $user_id = Auth::id();
        $company_id = Auth::user()->company_id;
        $now = Carbon::now()->toDateTimeString();

        foreach($request->name as $role_id => $names) {
            foreach($names as $name) {
                $data[] = [
                    'name' => $name,
                    'role_id' => $role_id,
                    'user_id' => $user_id,
                    'company_id' => $company_id,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
        }
        $member->insert($data);

        return redirect()->to(route('members.index'))->with('success', '创建成功');
    }

    public function update(MemberRequest $request, Member $member)
    {
        $this->authorize('own', $member);
        $member->update($request->all());

        return [];
    }

    public function getView(Member $member)
    {
        $member['user_name'] = $member->user->realname;

        return $member;
    }

    public function getResults(PaginateRequest $request)
    {
        $company_id = Auth::user()->company_id;

        $results = Member::query()->where('company_id', $company_id);

        $total = $results->count();
        $lastpage = ceil($total/$request->rows_per_page);
        $page = $request->page > $lastpage ? $lastpage : (int)$request->page;
        $per_page = $request->rows_per_page;

        Cookie::queue('paginate', json_encode(['members' => compact('page', 'per_page')]), 60);

        $results = $results
            ->orderBy('created_at', 'desc')
            ->with('role')
            ->offset(($page-1) * $per_page)
            ->limit($per_page)
            ->get();

        return compact('results', 'total', 'page', 'lastpage');
    }

    public function destroyAll(MemberRequest $request)
    {
        $members = Member::find($request->ids);

        foreach($members as $member){
            $this->authorize('own', $member);
        }

        Member::destroy($request->ids);
        return [];
    }

    public function search(SearchRequest $request)
    {
        $company_id = Auth::user()->company_id;
        $per_page = $request->rows_per_page;

        $results = Member::query()
            ->where('company_id', $company_id)
            ->where('name','like','%'.$request->name.'%')
            ->with('role')
            ->orderBy('created_at', 'desc');

        $total = $results->count();

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
            ->with(['company', 'role'])
            ->orderBy('created_at', 'desc');

        if(array_key_exists('members', $paginate)) {
            return $members->paginate($paginate->members->per_page, ['*'], 'page', $paginate->members->page);
        }

        return $members->paginate(10);
    }
}