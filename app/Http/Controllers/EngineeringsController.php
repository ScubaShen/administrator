<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EngineeringRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Engineering;
use App\Models\Supervision;
use App\Models\Batch;
use Auth;
use App\Handlers\ImageUploadHandler;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class EngineeringsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $engineerings = $this->getEngineerings();

        return view('engineerings.index', compact('engineerings'));
    }

    public function show(Engineering $engineering)
    {
        // 确保是调用同公司的纪录
        $this->authorize('ownCompany', $engineering);

        $engineerings = $this->getEngineerings();

        $currentEngineering = $engineering;

        return view('engineerings.index', compact('engineerings', 'currentEngineering'));
    }

    public function create()
    {
        $supervisions = Supervision::all();

        return view('engineerings.create_and_edit', compact('supervisions'));
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

    public function update(EngineeringRequest $request, Engineering $engineering)
    {
        $this->authorize('own', $engineering);
        $engineering->update($request->all());

        return redirect()->route('engineerings.show', $engineering->id)->with('success', '更新成功');
    }

    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];
        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'engineerings', \Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return $data;
    }

    public function getView(Engineering $engineering)
    {
        $this->authorize('ownCompany', $engineering);

        $engineering['user_name'] = $engineering->user->realname;
        $engineering['supervision_name'] = $engineering->supervision->name;
        $engineering['batches'] = Batch::where('engineering_id', $engineering->id)->pluck('name')->toArray();

        return $engineering;
    }

    public function getResults(PaginateRequest $request)
    {
        $results = $this->getEngineeringsUnion();

        $total = $results->get()->count();
        $lastpage = ceil($total/$request->rows_per_page);
        $page = $request->page > $lastpage ? $lastpage : (int)$request->page;
        $per_page = $request->rows_per_page;

        Cookie::queue('paginate', json_encode(['engineerings' => compact('page', 'per_page')]), 60);

        $results = $results
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
        if ($request->start_at) {
            $results = Batch::query()
                ->join('engineerings as e', 'e.id', 'batches.engineering_id')
                ->join('supervisions', 'supervisions.id', 'e.supervision_id')
                ->where('e.company_id', Auth::user()->company_id)
                ->where('e.name','like','%'. $request->name .'%')
                ->whereBetween('start_at', [$request->start_at, $request->end_at])
                ->select(DB::raw('any_value(e.id) as id,any_value(e.name) as name,any_value(supervisions.name) as supervision_name,any_value(e.description) as description,any_value(e.user_id) as user_id,any_value(e.created_at) as created_at,MIN(batches.start_at) as start_at,MAX(batches.finish_at) as finish_at'))
                ->groupBy('batches.engineering_id');
        } else {
            $results = $this->getEngineeringsUnion($request->name);
        }

        $total = $results->get()->count();

        $per_page = $request->rows_per_page;

        if($total == 0) {
            $lastpage = 1;
            $page = 1;
            $results = [];
        } else {
            $lastpage = ceil($total/$per_page);
            $page = $request->page > $lastpage ? $lastpage : (int)$request->page;
            $results = $results->orderBy('created_at', 'desc')->offset(($page-1) * $per_page)->limit($per_page)->get();
        }

        return compact('results', 'total', 'page', 'lastpage');
    }

    protected function getEngineerings()
    {
        $paginate = request()->cookie('paginate') ? json_decode(request()->cookie('paginate')) : [];
        $perPage = @$paginate->engineerings->per_page ?: 10;
        $page = @$paginate->engineerings->page ?: 1;

        $engineerings = $this->getEngineeringsUnion()->orderBy('created_at', 'desc')->get();

        $itemsForCurrentPage = array_slice($engineerings->toArray(), ($page-1) * $perPage, $perPage, true);
        return new LengthAwarePaginator($itemsForCurrentPage, $engineerings->count(), $perPage, $page);
    }

    protected function getEngineeringsUnion($searchName = null)
    {
        $company_id = Auth::user()->company_id;
        $have_batches_ids = [];

        $engineerings_have_batches = Batch::query()
            ->join('engineerings as e', 'e.id', 'batches.engineering_id')
            ->join('supervisions', 'supervisions.id', 'e.supervision_id')
            ->where('e.company_id', $company_id)
            ->select(DB::raw('any_value(e.id) as id,any_value(e.name) as name,any_value(supervisions.name) as supervision_name,any_value(e.description) as description,any_value(e.user_id) as user_id,any_value(e.created_at) as created_at,MIN(batches.start_at) as start_at,MAX(batches.finish_at) as finish_at'))
            ->groupBy('batches.engineering_id');

        if($searchName) {
            $engineerings_have_batches = $engineerings_have_batches->where('e.name','like','%'. $searchName .'%');
        }

        foreach($engineerings_have_batches->get()->toArray() as $engineering) {
            $have_batches_ids[] = $engineering['id'];
        }

        $engineerings = Engineering::query()
            ->join('supervisions', 'supervisions.id', 'engineerings.supervision_id')
            ->where('engineerings.company_id', $company_id);

        if(!empty($have_batches_ids)) {
            $engineerings = $engineerings->whereNotIn('engineerings.id', $have_batches_ids);
        }
        if($searchName) {
            $engineerings = $engineerings->where('engineerings.name','like','%'. $searchName .'%');
        }

        return $engineerings
            ->select(DB::raw('engineerings.id,engineerings.name,supervisions.name as supervision_name,engineerings.description,engineerings.user_id,engineerings.created_at,NULL as start_at,NULL as finish_at'))
            ->unionAll($engineerings_have_batches);
    }
}
