<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskApiController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::with('category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return TaskResource::collection($tasks);
    }

    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        $task = Task::create([
            'user_id' => $request->user()->id,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
            'completed_at' => $validated['status'] === 'completed'
                ? now()
                : null,
        ]);

        $task->load('category');

        return (new TaskResource($task))
            ->additional([
                'message' => 'タスクを登録しました。',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, $id)
    {
        $task = Task::with('category')
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return new TaskResource($task);
    }

    public function update(TaskRequest $request, $id)
    {
        $validated = $request->validated();

        $task = Task::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $completedAt = $task->completed_at;

        if ($validated['status'] === 'completed') {
            $completedAt ??= now();
        } else {
            $completedAt = null;
        }

        $task->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
            'completed_at' => $completedAt,
        ]);

        $task->load('category');

        return (new TaskResource($task))
            ->additional([
                'message' => 'タスクを更新しました。',
            ]);
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $task->delete();

        return response()->json([
            'message' => 'タスクを削除しました。',
        ]);
    }
}