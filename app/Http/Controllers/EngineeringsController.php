<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EngineeringRequest;
use App\Http\Requests\PaginateRequest;
use App\Models\Engineering;
use App\Models\User;
use Auth;
use App\Handlers\ImageUploadHandler;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cookie;

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
                        ->paginate(10);
        return view('engineerings.index', compact('engineerings'));
    }

    public function show(Engineering $engineering)
    {
        $paginate = unserialize(request()->cookie('paginate'))['engineerings'];

        if($paginate) {
            $engineerings = Engineering::query()
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate($paginate['per_page'], ['*'], 'page', $paginate['page']);
        }else {
            $engineerings = Engineering::query()
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate();
        }

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
        $this->authorize('own', $engineering);
        $engineering->start_at = str_replace(" ", "T", $engineering->start_at);
        $engineering->finish_at = str_replace(" ", "T", $engineering->finish_at);

        return view('engineerings.create_and_edit', compact('engineering'));
    }

    public function update(Engineering $engineering, Request $request)
    {
        $this->authorize('own', $engineering);
        $engineering->update($request->all());

        return redirect()->route('engineerings.show', $engineering->id)->with('success', '更新成功');
    }

    public function destroy(Engineering $engineering, Request $request)
    {
        $this->authorize('own', $engineering);
        $engineering->delete();

        if(URL::previous() === $request->url()) {
            return redirect()->route('engineerings.index')->with('success', '成功删除');
        }
        return redirect()->back()->with('success', '成功删除');
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
        $user_id = $engineering->user_id;
        $engineering['user_name'] = User::find($user_id)->name;
        return $engineering;
    }

    public function getResults(PaginateRequest $request)
    {
        $total = Engineering::where('user_id', Auth::id())->count();

        $lastpage = ceil($total/$request->rows_per_page);

        $page = $request->current_page > $lastpage ? $lastpage : $request->current_page;

        $per_page=$request->rows_per_page;

        Cookie::queue('paginate', serialize(['engineerings' => compact('page', 'per_page')]), 60);

        $results = Engineering::query()
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->offset(($page-1) * $per_page)
            ->limit($per_page)
            ->get();

        return compact('results', 'page', 'total', 'lastpage');
    }
}
