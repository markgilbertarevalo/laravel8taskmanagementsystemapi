<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\SubTask;
use Auth;

class TaskService
{
    private function authId()
    {
        return Auth::id();
    }

    public function store($request)
    {
        if($request->hasFile('image') === false){
            return "no_image";
        }

        $data = Task::create($this->prepareData($request));

        return "success";
    }

    public function prepareData($request)
    {
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images/tasks'), $imageName);

        $data['user_id'] = Auth::id();
        $data['image'] = $imageName;
        $data['title'] = $request['title'];

        return $data;
    }

    public function update($request)
    {
        if(!Task::where('user_id', $this->authId())->where('id', $request->id)->count() > 0){
            return "unauthorized";
        }

        $task = Task::findOrFail($request->id);

        $task->title = $request->title;
        $task->status = $request->status;

        $task->save();

        return $task;
    }

    public function fetchTask($task)
    {
        if($task->user_id !== $this->authId()){
            return "unauthorized";
        }

        return Task::findOrFail($task->id);
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
