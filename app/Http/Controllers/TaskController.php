<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskController extends Controller
{

public function index()
{
    $user = JWTAuth::parseToken()->authenticate();

    $tasks = Task::where('user_id', $user->id)->get();

    return response()->json($tasks);
}

public function store(Request $request)
{
    $user = JWTAuth::parseToken()->authenticate();

    $task = Task::create([
        'user_id' => $user->id,
        'title' => $request->title,
        'description' => $request->description,
        'status' => $request->status ?? 'pending',
        'deadline' => $request->deadline
    ]);

    return response()->json([
        'message' => 'Task berhasil dibuat',
        'data' => $task
    ]);
}

public function update(Request $request, $id)
{
    $user = JWTAuth::parseToken()->authenticate();

    $task = Task::where('user_id', $user->id)->findOrFail($id);

    $task->update([
        'title' => $request->title,
        'description' => $request->description,
        'status' => $request->status,
        'deadline'=>$request->deadline
    ]);

    return response()->json([
        'message'=>'Task berhasil diupdate',
        'data'=>$task
    ]);
}

public function destroy($id)
{
    $user = JWTAuth::parseToken()->authenticate();

    $task = Task::where('user_id', $user->id)->findOrFail($id);

    $task->delete();

    return response()->json([
        'message'=>'Task berhasil dihapus'
    ]);
}

}