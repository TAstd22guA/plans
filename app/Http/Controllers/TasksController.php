<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
   public function __construct()
   {
       $this->middleware('auth');
   }
   
   private function checkMyData(Task$task){
       if($task->user_id != Auth::user()->id){
           return redirect()->route('tasks.index');
       }
   }
   
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        // タスク一覧を取得
        //$tasks = Task::all();
        // タスク一覧ビューでそれを表示
        //return view('tasks.index', [
        //    'tasks' => $tasks,
        //]);
        
        $tasks = Auth::user() -> tasks;
        return view('tasks.index',compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     
     
    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task = new Task;

        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
         // バリデーション
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',   // 追加
        ]);
        
        // タスクを作成
        $task = new Task;
        $task->content = $request->content;
        $task->status = $request->status;
        $task->user_id = Auth::user()->id;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
        //return redirect()->route('tasks.show',$task);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
     
    // getでtasks/idにアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        
    //ログインのチェック       
    function show(Task$task){
    $this->checkMyData($task);
    return view('tasks.show',compact('task'));
    }

    // idの値でタスクを検索して取得
    $task = Task::findOrFail($id);

    // タスク詳細ビューでそれを表示
    return view('tasks.show', [
    'task' => $task,
    ]);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    // getでtasks/id/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        
    //ログインのチェック       
    function edit(Task$task){
    $this->checkMyData($task);
    return view('tasks.edit',compact('task'));
    }

    // idの値でタスクを検索して取得
    $task = Task::findOrFail($id);

    // タスク編集ビューでそれを表示
    return view('tasks.edit', [
    'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    // putまたはpatchでtasks/idにアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        
    //ログインのチェック       
    function update(Task$task){
    $this->checkMyData($task);
    return view('tasks.update',compact('task'));
    }
    
    // バリデーション
    $request->validate([
    'content' => 'required',
    'status' => 'required|max:10',   // 追加
    ]);
        
    // idの値でタスクを検索して取得
    $task = Task::findOrFail($id);
    
    // タスクを更新
    $task->content = $request->content;
    $task->status = $request->status;    // 追加
    $task->save();

    // トップページへリダイレクトさせる
    return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    // deleteでtasks/idにアクセスされた場合の「削除処理」
    public function destroy($id)
    {

    // idの値でタスクを検索して取得
    $task = Task::findOrFail($id);
   
     // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
    if (\Auth::id() === $$task->user_id) {
        $$task->delete();
    }
   
    // トップページへリダイレクトさせる
    return back();
    }
 
}