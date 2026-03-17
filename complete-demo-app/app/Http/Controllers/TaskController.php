<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('created_at', 'desc')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|max:255']);
        Task::create(['title' => $request->title]);
        return redirect('/');
    }

    public function toggle(Task $task)
    {
        $task->update(['completed' => !$task->completed]);
        return redirect('/');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect('/');
    }

    public function completed()
    {
        $tasks = Task::where('completed', true)->orderBy('updated_at', 'desc')->get();
        $count = $tasks->count();
        return view('tasks.completed', compact('tasks', 'count'));
    }
}
