<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\SubTask;
use Auth;

class TaskService
{

    public function store($request)
    {
        $data = Task::create($this->prepareData($request));

        return response()->json(['message' => "Success!"],200);
    }

    public function prepareData($request)
    {
        $data['user_id'] = Auth::id();
        $data['image'] = $request['image'];
        $data['title'] = $request['title'];

        return $data;
    }

    public function update($request)
    {
        // $task->update([
        //     'title' => $request->input('title'),
        //     'status' => $request->input('status')
        // ]);

        $task = Task::findOrFail($request->id);

        $task->title = $request->title;
        $task->status = $request->status;

        $task->save();

        return $task;
    }

    public function subStore($request)
    {
        $data = SubTask::create($this->subPrepareData($request));

        return response()->json(['message' => "Success!"],200);
    }

    public function subPrepareData($request)
    {
        $data['title'] = $request['title'];
        $data['task_id'] = $request['task_id'];

        return $data;
    }

}
