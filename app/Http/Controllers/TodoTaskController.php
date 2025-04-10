<?php

namespace App\Http\Controllers;

use App\Models\TodoTask;
use Illuminate\Http\Request;

class TodoTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(TodoTask::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'completed' => 'required|boolean',
        ]);

        $todoTask = TodoTask::create($validatedData);

        return response()->json([
            'message' => __('api.taskCreated'),
            'task' => $todoTask,
        ],
            201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $todoTask = TodoTask::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'completed' => 'required|boolean',
        ]);

        $todoTask->update($validatedData);

        return response()->json([
            'message' => __('api.taskUpdated'),
            'task' => $todoTask,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todoTask = TodoTask::findOrFail($id);

        $todoTask->delete();

        return response()->json(['message' => __('api.taskDeleted')]);
    }

    public function deleteAll()
    {
        TodoTask::truncate();

        return response()->json(['message' => __('api.allTasksDeleted')], 200);
    }
}
