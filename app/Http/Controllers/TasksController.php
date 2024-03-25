<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TasksController extends Controller
{
    // getでTasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        $data = [];
        
        if (\Auth::check()) { // 認証済みの場合
        
        // 認証済みユーザを取得
        $user = \Auth::user();
        
        // ユーザの投稿の一覧を作成日時の降順で取得
        $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        // タスク一覧ビューでそれを表示
        return view('tasks/index', $data);
    }

    // getでTasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task = new Task;

        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    // postでTasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        // タスクを作成
        $request->user()->tasks()->create([ 
            'status' => $request->status,
            'content' => $request->content,
        ]);

        $data = [];
        
        if (\Auth::check()) { // 認証済みの場合
        
        // 認証済みユーザを取得
        $user = \Auth::user();
        
        // ユーザの投稿の一覧を作成日時の降順で取得
        $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        // タスク一覧ビューでそれを表示
        return view('tasks/index', $data);
    }

    // getでTasks/（任意のid）にアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            // タスク詳細ビューでそれを表示
            return view('tasks.show', [
                'task' => $task,
            ]);
        }
        
        else if (\Auth::id() !== $task->user_id) {
            $data = [];
        
            if (\Auth::check()) { // 認証済みの場合
            
            // 認証済みユーザを取得
            $user = \Auth::user();
            
            // ユーザの投稿の一覧を作成日時の降順で取得
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
                $data = [
                    'user' => $user,
                    'tasks' => $tasks,
                ];
            }
        
            // タスク一覧ビューでそれを表示
            return view('tasks/index', $data);
        }
    }

    // getでTasks/（任意のid）/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);

        if (\Auth::id() === $task->user_id) {
            // メッセージ編集ビューでそれを表示
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }
        
        else if (\Auth::id() !== $task->user_id) {
            $data = [];
        
            if (\Auth::check()) { // 認証済みの場合
            
            // 認証済みユーザを取得
            $user = \Auth::user();
            
            // ユーザの投稿の一覧を作成日時の降順で取得
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
                $data = [
                    'user' => $user,
                    'tasks' => $tasks,
                ];
            }
        
            // タスク一覧ビューでそれを表示
            return view('tasks/index', $data);
        }
    }

    // putまたはpatchでTasks/（任意のid）にアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
            
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
            
        // タスクを更新
        $request->user()->tasks()->create([ 
            'status' => $request->status,
            'content' => $request->content,
        ]);
    
        $data = [];
            
        if (\Auth::check()) { // 認証済みの場合
            
        // 認証済みユーザを取得
        $user = \Auth::user();
            
        // ユーザの投稿の一覧を作成日時の降順で取得
        $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
            
        // タスク一覧ビューでそれを表示
        return view('tasks/index', $data);
    }

    // deleteでTasks/（任意のid）にアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        $data = [];
        
        if (\Auth::check()) { // 認証済みの場合
            
        // 認証済みユーザを取得
        $user = \Auth::user();
            
        // ユーザの投稿の一覧を作成日時の降順で取得
        $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        // タスク一覧ビューでそれを表示
        return view('tasks/index', $data);
    }
}