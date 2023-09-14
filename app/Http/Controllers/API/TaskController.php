<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Services\Task\TaskService;
use App\Services\Task\ImageService;
use App\Http\Resources\TasksResource;
use Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function authId()
    {
        return Auth::id();
    }

    public function index()
    {
        $id = $this->authId();
        return TasksResource::collection(Task::where('user_id', $id)->get());
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
    public function store(StoreTaskRequest $request, TaskService $taskService)
    {
        try {

            if($taskService->store($request) === "success"){
                return response(null, 201);
            }
            else {
                return response()->json(['error' => 'There is no image to upload.'], 400);
            };

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in TaskController.store',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task, TaskService $taskService)
    {
        try {

            $task = $taskService->fetchTask($task);

            if($task == "unauthorized"){
                return response()->json(['error' => 'Unauthorized.'], 401);
            }

            return new TasksResource($task);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in TaskController.show',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, TaskService $taskService)
    {
        try {
            $task = $taskService->update($request);
            if($task == "unauthorized"){
                return response()->json(['error' => 'Unauthorized.'], 401);
            }

            return new TasksResource($task);
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
    public function destroy(Task $task, TaskService $taskService)
    {
        try {
            $task = $taskService->fetchTask($task);

            if($task == "unauthorized"){
                return response()->json(['error' => 'Unauthorized.'], 401);
            }

            if(!empty($task->image)){
                $currentImage = public_path() . '/images/tasks/' . $task->image;

                if(file_exists($currentImage)){
                    unlink($currentImage);
                }
            }
            $task->delete();
            return response(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in TaskController.update',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
