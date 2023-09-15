<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

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
