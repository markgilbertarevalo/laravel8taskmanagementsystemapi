<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\SubTask;
use Auth;
use PhpParser\Node\Stmt\TryCatch;

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

        if(!Task::where('user_id', $this->authId())->where('id', $task->id)->count() > 0){
            return "unauthorized";
        }

        return Task::findOrFail($task->id);

    }

    public function delete($task)
    {
        if($task->user_id !== $this->authId()){
            return "unauthorized";
        }

        $task = Task::findOrFail($task->id);

        // $task->trash = "1";

        // $task->save();
        $task->delete();

        return $task;
    }

    public function trash($task)
    {

        $data = Task::onlyTrashed()->where('user_id', $this->authId())->get();
        foreach ($data as $row) {
            if(!empty($row->image)){
                $currentImage = public_path() . '/images/tasks/' . $row->image;

                if(file_exists($currentImage)){
                    unlink($currentImage);
                }

                    $task = Task::withTrashed()->find($row->id);
                    $task->forceDelete();
            }
        }

        return $data;

    }

    public function restore_task($request)
    {
        if(!Task::withTrashed()->where('user_id', $this->authId())->where('id', $request->id)->count() > 0){
            return "unauthorized";
        }

        $task = Task::withTrashed()->find($request->id);
        $task->restore();

        return $task;
    }

    public function bin_delete_task($id)
    {

        if(!Task::withTrashed()->where('user_id', $this->authId())->where('id', $id)->count() > 0){
            return "unauthorized";
        }

        $task = Task::withTrashed()->find($id);
        if ($task == null)
        {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }
        //dd($task->id);
        if(!empty($task->image)){
            $currentImage = public_path() . '/images/tasks/' . $task->image;

            if(file_exists($currentImage)){
                unlink($currentImage);
            }
        }
        $task = Task::withTrashed()->find($task->id);
        $task->forceDelete();

        return $task;
    }

    public function search($request)
    {
        $query = $request->input('query');

        return $tasks = Task::where('title', 'LIKE', "%$query%")
            ->where('user_id', $this->authId())
            ->get();
    }

    public function subStore($request)
    {
        if(!Task::where('user_id', $this->authId())->where('id', $request->task_id)->count() > 0){
            return "unauthorized";
        }

        return $data = SubTask::create($this->subPrepareData($request));
    }

    public function subPrepareData($request)
    {
        $data['title'] = $request['title'];
        $data['task_id'] = $request['task_id'];

        return $data;
    }

    public function subUpdate($request)
    {

        $count = SubTask::join('tasks', 'sub_tasks.task_id', '=', 'tasks.id')->where('tasks.user_id', $this->authId())->where('sub_tasks.id', $request->id)->count();
        //dd(SubTask::join('tasks', 'sub_tasks.id', '=', 'task.id')->where('tasks.user_id', $this->authId())->where('sub_tasks.id', $request->id)->count());
        if(!$count > 0){
            return "unauthorized";
        }

        $task = SubTask::findOrFail($request->id);

        $task->title = $request->title;
        $task->status = $request->status;

        $task->save();

        return $task;
    }

    public function subDelete($task)
    {
        $count = SubTask::join('tasks', 'sub_tasks.task_id', '=', 'tasks.id')->where('tasks.user_id', $this->authId())->where('sub_tasks.id', $task)->count();

        if(!$count > 0){
            return "unauthorized";
        }

        $task = SubTask::findOrFail($task);

        $task->delete();

        return $task;
    }

    public function fetchSubTask($task)
    {
        $count = SubTask::join('tasks', 'sub_tasks.task_id', '=', 'tasks.id')->where('tasks.user_id', $this->authId())->where('sub_tasks.id', $task)->count();

        if(!$count > 0){
            return "unauthorized";
        }

        return SubTask::findOrFail($task);

    }

}
