<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BatchRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Engineering;
use App\Models\User;
use App\Models\Batch;
use Auth;
use Illuminate\Support\Facades\Cookie;

class BatchesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(Batch $batch)
	{
		$paginate = request()->cookie('paginate') ? json_decode(request()->cookie('paginate')) : [];

        $user_ids = $this->getUserIdsByCurrentCompany();

		if(array_key_exists('batches', $paginate)) {
			$batches = $batch
					->whereIn('user_id', $user_ids)
					->with('engineering')
					->orderBy('created_at', 'desc')
					->paginate($paginate->batches->per_page, ['*'], 'page', $paginate->batches->page);
		}else {
            $batches = $batch
					->whereIn('user_id', $user_ids)
					->with('engineering')
					->orderBy('created_at', 'desc')
					->paginate(10);
		}

		return view('batches.index', compact('batches'));
	}

	public function show(Batch $batch, User $user)
	{
		$paginate = request()->cookie('paginate') ? json_decode(request()->cookie('paginate')) : [];

        $user_ids = $this->getUserIdsByCurrentCompany();

		if(array_key_exists('batches', $paginate)) {
			$batches = $batch
					->whereIn('user_id', $user_ids)
					->with('engineering')
					->orderBy('created_at', 'desc')
                    ->paginate($paginate->batches->per_page, ['*'], 'page', $paginate->batches->page);
		}else {
			$batches = $batch
					->whereIn('user_id', $user_ids)
					->with('engineering')
					->orderBy('created_at', 'desc')
					->paginate(10);
		}

        // 取出各个职位的人名，拼成 A, B, C, ... 的形式
		foreach($batch->groups as $position => $users_array) {
			$groups[$position] = '';
			foreach ($user->find($users_array) as $oneUser) {
				$groups[$position] .= $oneUser->realname . ', ';
			}
			$groups[$position] = rtrim($groups[$position], ', ');
		}

		$specificBatch = $batch;

		return view('batches.index', compact('batches', 'specificBatch', 'groups'));
	}

	public function create()
	{
        $users = $this->getUsersGroupByPosition();

		$engineerings = Engineering::all();

		return view('batches.create_and_edit', compact('users', 'engineerings'));
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
        $batch->groups = json_encode(compact('technicians', 'custodians', 'safety_officers', 'powdermen', 'manager'));
        $batch->materials = json_encode(compact('detonator', 'dynamite'));
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

        $users = $this->getUsersGroupByPosition();

		$engineerings = Engineering::all();

		return view('batches.create_and_edit', compact('batch', 'engineerings', 'users'));
	}

	public function update(Batch $batch, Request $request)
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
        $batch->groups = json_encode(compact('technicians', 'custodians', 'safety_officers', 'powdermen', 'manager'));
        $batch->materials = json_encode(compact('detonator', 'dynamite'));

        $batch->save();

		return redirect()->route('batches.show', $batch->id)->with('success', '更新成功');
	}

	public function getView(Batch $batch, User $user)
	{
        $batch['user_name'] = $batch->user->realname;
        $batch['engineering_name'] = $batch->engineering->name;

        foreach($batch->groups as $position => $users_array) {
            $batch[$position] = '';
            foreach ($user->find($users_array) as $oneUser) {
                $batch[$position] .= $oneUser->realname . ', ';
            }
            $batch[$position] = rtrim($batch[$position], ', ');
        }

		return $batch;
	}

	public function getResults(PaginateRequest $request)
	{
        $user_ids = $this->getUserIdsByCurrentCompany();

		$results = Batch::query()->whereIn('user_id', $user_ids);

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

	public function destroyAll(Request $request)
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
        $user_ids = $this->getUserIdsByCurrentCompany();

		$results = Batch::query()
				->whereIn('user_id', $user_ids)
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

        $users = User::query()->where('company_id', $company_id)->get();

        foreach($users as $user) {
            $users_array[$user->role_id][] = $user;
        }

        return $users_array;
    }

    protected function getUserIdsByCurrentCompany()
    {
        $company_id = Auth::user()->company_id;

        $user_ids = User::query()->where('company_id', $company_id)->pluck('id')->toArray();

        return $user_ids;
    }
}
