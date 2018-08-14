<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaterialRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Material;
use Auth;
use Illuminate\Support\Facades\Cookie;

class MaterialsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
        $materials = $this->getMaterials();

		return view('materials.index', compact('materials'));
	}

	public function show(Material $material)
	{
		// 确保是同公司
		$this->authorize('ownCompany', $material);

		$materials = $this->getMaterials();

		$currentMaterial = $material;

		return view('materials.index', compact('materials', 'currentMaterial'));
	}

	public function create()
	{
		return view('materials.create_and_edit');
	}

	public function store(MaterialRequest $request, Material $material)
	{
        $material->fill($request->all());
        $material->user_id = Auth::id();
        $material->company_id = Auth::user()->company_id;
        $material->save();

		return redirect()->to(route('materials.show', $material->id))->with('success', '创建成功');
	}

	public function edit(Material $material)
	{
		$this->authorize('own', $material);

		return view('materials.create_and_edit', compact('material'));
	}

	public function update(MaterialRequest $request, Material $material)
	{
		$this->authorize('own', $material);

        $material->update($request->all());

		return redirect()->route('materials.show', $material->id)->with('success', '更新成功');
	}

	public function getView(Material $material)
	{
		// 确保是调用同公司的纪录
		$this->authorize('ownCompany', $material);

		$material['user_name'] = $material->user->realname;

		return $material;
	}

	public function getResults(PaginateRequest $request)
	{
        $company_id = Auth::user()->company_id;

		$results = Material::query()->where('company_id', $company_id);

		$total = $results->count();
		$lastpage = ceil($total/$request->rows_per_page);
		$page = $request->page > $lastpage ? $lastpage : (int)$request->page;
		$per_page = $request->rows_per_page;

		Cookie::queue('paginate', json_encode(['materials' => compact('page', 'per_page')]), 60);

		$results = $results
				->with('user')
				->orderBy('created_at', 'desc')
				->offset(($page-1) * $per_page)
				->limit($per_page)
				->get();

		return compact('results', 'total', 'page', 'lastpage');
	}

	public function destroyAll(MaterialRequest $request)
	{
		$materials = Material::find($request->ids);

		foreach($materials as $material){
			$this->authorize('own', $material);
		}

		Material::destroy($request->ids);
		return [];
	}

	public function search(SearchRequest $request)
	{
        $company_id = Auth::user()->company_id;

		$results = Material::query()
				->where('company_id', $company_id)
				->with('user')
				->where('name','like','%'.$request->name.'%')
				->orderBy('created_at', 'desc');

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

	protected function getMaterials()
	{
		$paginate = request()->cookie('paginate') ? json_decode(request()->cookie('paginate')) : [];

		$company_id = Auth::user()->company_id;

		$materials = Material::query()
				->where('company_id', $company_id)
				->with('user')
				->orderBy('created_at', 'desc');

		if(array_key_exists('materials', $paginate)) {
			return $materials->paginate($paginate->materials->per_page, ['*'], 'page', $paginate->materials->page);
		}

		return $materials->paginate(10);

	}
}