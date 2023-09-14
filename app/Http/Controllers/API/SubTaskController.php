<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SubTask;
use App\Http\Requests\Task\StoreSubTaskRequest;
use App\Http\Requests\Task\UpdateSubTaskRequest;
use App\Services\Task\TaskService;

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

            $taskService->subStore($request);

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
    public function update(UpdateSubTaskRequest $request, SubTask $subTask)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubTask $subTask)
    {
        //
    }
}
