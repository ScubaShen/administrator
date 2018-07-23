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

    public function index(Engineering $engineering)
    {
        $paginate = request()->cookie('paginate') ? json_decode(request()->cookie('paginate')) : [];

        $users = $this->getUsersByCurrentCompany();

        $user_ids = [];
        foreach($users as $user) {
            array_push($user_ids, $user->id);
        }

        if(array_key_exists('engineerings', $paginate)) {
            $engineerings = $engineering
                ->whereIn('user_id', $user_ids)
                ->with('supervision')
                ->orderBy('created_at', 'desc')
                ->paginate($paginate->engineerings->per_page, ['*'], 'page', $paginate->engineerings->page);
        }else {
            $engineerings = $engineering
                ->whereIn('user_id', $user_ids)
                ->with('supervision')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('engineerings.index', compact('engineerings'));
    }

    public function show(Engineering $engineering, User $user)
    {
        $paginate = request()->cookie('paginate') ? json_decode(request()->cookie('paginate')) : [];

        $users = $this->getUsersByCurrentCompany();

        $user_ids = [];
        foreach($users as $user) {
            array_push($user_ids, $user->id);
        }

        if(array_key_exists('engineerings', $paginate)) {
            $engineerings = $engineering
                ->whereIn('user_id', $user_ids)
                ->with('supervision')
                ->orderBy('created_at', 'desc')
                ->paginate($paginate->engineerings->per_page, ['*'], 'page', $paginate->engineerings->page);
        }else {
            $engineerings = $engineering
                ->whereIn('user_id', $user_ids)
                ->with('supervision')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        $specificEngineering = $engineering;

        // 取出各个职位的人名，拼成 A, B, C, ... 的形式
        $users = json_decode($engineering->data);
        if($users) {
            foreach($users as $position => $users_array){
                $data[$position] = '';
                foreach($user->find($users_array) as $oneUser) {
                    $data[$position] .= $oneUser->realname . ', ';
                }
                $data[$position] = rtrim($data[$position], ', ');
            }
        }

        return view('engineerings.index', compact('engineerings', 'specificEngineering', 'data'));
    }

    public function create()
    {
        $users = $this->getUsersByCurrentCompany();
        foreach($users as $user) {
            $users_array[$user->role_id][] = $user;
        }

        $supervisions = Supervision::all();

        return view('engineerings.create_and_edit', compact('users_array', 'supervisions'));
    }

    public function store(EngineeringRequest $request, Engineering $engineering)
    {
        $technicians = $request->technician;
        $custodians = $request->custodian;
        $safety_officers = $request->safety_officer;

        $engineering->fill($request->except(['technician', 'custodian', 'safety_officer']));
        $engineering->user_id = Auth::id();
        $engineering->data = json_encode(compact('technicians', 'custodians', 'safety_officers'));
        $engineering->save();

        return redirect()->to(route('engineerings.show', $engineering->id))->with('success', '创建成功');
    }

    public function edit(Engineering $engineering)
    {
        $this->authorize('own', $engineering);
        $engineering->start_at = str_replace(" ", "T", $engineering->start_at);
        $engineering->finish_at = str_replace(" ", "T", $engineering->finish_at);

        $users = $this->getUsersByCurrentCompany();

        foreach($users as $user) {
            $users_array[$user->role_id][] = $user;
        }

        $supervisions = Supervision::all();

        return view('engineerings.create_and_edit', compact('engineering', 'users_array', 'supervisions'));
    }

    public function update(Engineering $engineering, Request $request)
    {
        $this->authorize('own', $engineering);

        $technicians = $request->technician;
        $custodians = $request->custodian;
        $safety_officers = $request->safety_officer;
        $data = json_encode(compact('technicians', 'custodians', 'safety_officers'));

        $engineering->update(array_merge($request->except(['technician', 'custodian', 'safety_officer']), compact('data')));

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

    public function getView(Engineering $engineering, User $user)
    {
        $engineering['user_name'] = $engineering->user->realname;

        $engineering['supervision_name'] = $engineering->supervision->name;

        // 取出各个职位的人名，命名为data
        $users = json_decode($engineering->data);
        if($users) {
            foreach ($users as $position => $users_array) {
                $engineering[$position] = '';
                foreach ($user->find($users_array) as $oneUser) {
                    $engineering[$position] .= $oneUser->realname . ', ';
                }
                $engineering[$position] = rtrim($engineering[$position], ', ');
            }
        }

        return $engineering;
    }

    public function getResults(PaginateRequest $request)
    {
        $users = $this->getUsersByCurrentCompany();

        $user_ids = [];
        foreach($users as $user) {
            array_push($user_ids, $user->id);
        }

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
        $users = $this->getUsersByCurrentCompany();

        $user_ids = [];
        foreach($users as $user) {
            array_push($user_ids, $user->id);
        }

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

    protected function getUsersByCurrentCompany()
    {
        $company_id = Auth::user()->company_id;
        $users = User::query()->where('company_id', $company_id)->get();

        return $users;
    }
}
