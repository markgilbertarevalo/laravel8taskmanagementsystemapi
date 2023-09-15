<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'user_id',
        'status',
        'trash',
    ];

    public function subTask()
    {
        return $this->hasMany(
           SubTask::class,
           'task_id'
        );
    }
}
