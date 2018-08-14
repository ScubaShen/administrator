<?php

namespace App\Http\Controllers;

use App\Http\Requests\BatchRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Engineering;
use App\Models\Batch;
use App\Models\Member;
use Auth;
use Illuminate\Support\Facades\Cookie;

class BatchesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
        $batches = $this->getBatches();

		return view('batches.index', compact('batches'));
	}

	public function show(Batch $batch)
	{
        // 确保是同公司
        $this->authorize('ownCompany', $batch);

        $batches = $this->getBatches();

        $this->getGroupsSpelling($batch);

		$currentBatch = $batch;

		return view('batches.index', compact('batches', 'currentBatch'));
	}

	public function create()
	{
        $members = $this->getUsersGroupByPosition();

		$engineerings = Engineering::where('company_id', Auth::user()->company_id)->get();

		return view('batches.create_and_edit', compact('members', 'engineerings'));
	}

	public function store(BatchRequest $request, Batch $batch)
	{
        $technicians = $request->technicians;
        $custodians = $request->custodians;
        $safety_officers = $request->safety_officers;
        $powdermen = $request->powdermen;
        $manager = $request->manager;
        $detonator = $request->detonator;
        $dynamite = $request->dynamite;

        $batch->fill($request->all());
        $batch->groups = compact('technicians', 'custodians', 'safety_officers', 'powdermen', 'manager');
        $batch->materials = compact('detonator', 'dynamite');
        $batch->user_id = Auth::id();
		$batch->company_id = Auth::user()->company_id;
		$batch->save();

		return redirect()->to(route('batches.show', $batch->id))->with('success', '创建成功');
	}

	public function edit(Batch $batch)
	{
		$this->authorize('own', $batch);
		$batch->start_at = str_replace(" ", "T", $batch->start_at);
		$batch->finish_at = str_replace(" ", "T", $batch->finish_at);

        $members = $this->getUsersGroupByPosition();

		$engineerings = Engineering::where('company_id', Auth::user()->company_id)->get();

		return view('batches.create_and_edit', compact('batch', 'engineerings', 'members'));
	}

	public function update(BatchRequest $request, Batch $batch)
	{
		$this->authorize('own', $batch);

        $technicians = $request->technicians;
        $custodians = $request->custodians;
        $safety_officers = $request->safety_officers;
        $powdermen = $request->powdermen;
        $manager = $request->manager;
        $detonator = $request->detonator;
        $dynamite = $request->dynamite;

        $batch->fill($request->all());
        $batch->groups = compact('technicians', 'custodians', 'safety_officers', 'powdermen', 'manager');
        $batch->materials = compact('detonator', 'dynamite');

        $batch->save();

		return redirect()->route('batches.show', $batch->id)->with('success', '更新成功');
	}

	public function getView(Batch $batch)
	{
		// 确保是调用同公司的纪录
		$this->authorize('ownCompany', $batch);

        $batch['user_name'] = $batch->user->realname;
        $batch['engineering_name'] = $batch->engineering->name;

        $this->getGroupsSpelling($batch);

		return $batch;
	}

	public function getResults(PaginateRequest $request)
	{
        $company_id = Auth::user()->company_id;

		$results = Batch::query()->where('company_id', $company_id);

		$total = $results->count();
		$lastpage = ceil($total/$request->rows_per_page);
		$page = $request->page > $lastpage ? $lastpage : (int)$request->page;
		$per_page = $request->rows_per_page;

		Cookie::queue('paginate', json_encode(['batches' => compact('page', 'per_page')]), 60);

		$results = $results
				->with('engineering')
				->orderBy('created_at', 'desc')
				->offset(($page-1) * $per_page)
				->limit($per_page)
				->get();

		return compact('results', 'total', 'page', 'lastpage');
	}

	public function destroyAll(BatchRequest $request)
	{
		$batches = Batch::find($request->ids);

		foreach($batches as $batch){
			$this->authorize('own', $batch);
		}

        Batch::destroy($request->ids);
		return [];
	}

	public function search(SearchRequest $request)
	{
        $company_id = Auth::user()->company_id;

		$results = Batch::query()
				->where('company_id', $company_id)
				->with('engineering')
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

    protected function getUsersGroupByPosition()
    {
        $company_id = Auth::user()->company_id;

        $members = Member::query()->where('company_id', $company_id)->get();

        foreach($members as $member) {
            $users_array[$member->role_id][] = $member;
        }

        return $users_array;
    }

    protected function getBatches()
    {
        $paginate = request()->cookie('paginate') ? json_decode(request()->cookie('paginate')) : [];

        $company_id = Auth::user()->company_id;

        $batches = Batch::query()
            ->where('company_id', $company_id)
            ->with('engineering')
            ->orderBy('created_at', 'desc');

        if(array_key_exists('batches', $paginate)) {
            return $batches->paginate($paginate->batches->per_page, ['*'], 'page', $paginate->batches->page);
        }

        return $batches->paginate(10);

    }

    protected function getGroupsSpelling($batch)
    {
        $member = app(Member::class);
        // 取出各个职位的人名，拼成 A, B, C, ... 的形式
        foreach($batch->groups as $position => $users_array) {
            $batch[$position] = '';
            is_array($users_array) ?: $users_array = [$users_array];

            foreach ($member->find($users_array) as $oneUser) {
                $batch[$position] .= $oneUser->name . ', ';
            }
            $batch[$position] = rtrim($batch[$position], ', ');
        }

        return $batch;
    }
}