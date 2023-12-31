<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\RestoreTaskRequest;
use App\Models\Task;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Services\Task\TaskService;
use App\Http\Resources\TasksResource;
use App\Models\SubTask;
use Auth;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function authId()
    {
        return Auth::id();
    }

    public function index(Request $request)
    {
        $id = $this->authId();
        return TasksResource::collection(QueryBuilder::for(Task::where('user_id', $id))
        ->allowedFilters(['title'])
        ->allowedSorts('created_at')
        ->get());
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
            $task = $taskService->delete($task);

            if($task == "unauthorized"){
                return response()->json(['error' => 'Unauthorized.'], 401);
            }

            return response(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in TaskController.update',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function trash(Task $task, TaskService $taskService)
    {

        try {
            $task = $taskService->trash($task);

            if($task == "unauthorized"){
                return response()->json(['error' => 'Unauthorized.'], 401);
            }

            //$task->delete();
            return response(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in TaskController.trash',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function recyclebin(Request $request)
    {
        try {

            $id = $this->authId();
            return TasksResource::collection(QueryBuilder::for(Task::onlyTrashed()->where('user_id', $id))
                ->allowedFilters(['title'])
                ->allowedSorts('created_at')
                ->get());

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in TaskController.recyclebin',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function restore_task(RestoreTaskRequest $request, TaskService $taskService)
    {
        try {
            $task = $taskService->restore_task($request);
            if($task == "unauthorized"){
                return response()->json(['error' => 'Unauthorized.'], 401);
            }

            return response(null, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in TaskController.restore_task',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function bin_delete_task($request, TaskService $taskService)
    {
        try {

            $task = $task = $taskService->bin_delete_task($request);

            if($task == "unauthorized"){
                return response()->json(['error' => 'Unauthorized.'], 401);
            }

            return response(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in TaskController.bin_delete_task',
                'error' => $e->getMessage()
            ], 400);
        }
    }

}
