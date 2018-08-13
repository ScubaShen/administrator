<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EngineeringRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Engineering;
use App\Models\User;
use App\Models\Supervision;
use Auth;
use App\Handlers\ImageUploadHandler;
use Illuminate\Support\Facades\Cookie;

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

        return $engineering;
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

    protected function getUserIdsByCurrentCompany()
    {
        $company_id = Auth::user()->company_id;

        $user_ids = User::query()->where('company_id', $company_id)->pluck('id')->toArray();

        return $user_ids;
    }

    protected function getEngineerings()
    {
        //获取分页信息
        $paginate = request()->cookie('paginate') ? json_decode(request()->cookie('paginate')) : [];

        $user_ids = $this->getUserIdsByCurrentCompany();

        $engineerings = Engineering::query()
            ->whereIn('user_id', $user_ids)
            ->with('supervision')
            ->orderBy('created_at', 'desc');

        if(array_key_exists('engineerings', $paginate)) {
            return $engineerings->paginate($paginate->engineerings->per_page, ['*'], 'page', $paginate->engineerings->page);
        }

        return $engineerings->paginate(10);
    }
}