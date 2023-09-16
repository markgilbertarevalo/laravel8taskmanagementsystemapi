<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SubTask;
use App\Http\Requests\Task\StoreSubTaskRequest;
use App\Http\Requests\Task\UpdateSubTaskRequest;
use App\Http\Resources\SubTasksResource;
use App\Services\Task\TaskService;
use Illuminate\Http\Request;

class SubTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubTaskRequest $request, TaskService $taskService)
    {
        try {

            $data = $taskService->subStore($request);

            if($data === "unauthorized"){
                return response()->json(['error' => 'Unauthorized.'], 401);
            }

            return response(null, 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in SubTaskController.store',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SubTask $subTask)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubTask $subTask)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubTaskRequest $request, TaskService $taskService)
    {
        try {
            $task = $taskService->subUpdate($request);
            if($task == "unauthorized"){
                return response()->json(['error' => 'Unauthorized.'], 401);
            }

            return new SubTasksResource($task);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in TaskController.update',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($subTask, TaskService $taskService)
    {
        try {
            $subTask = $taskService->subDelete($subTask);
            if($subTask == "unauthorized"){
                return response()->json(['error' => 'Unauthorized.'], 401);
            }

            return response(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in SubTaskController.destroy',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
