<?php

namespace App\Console\Commands;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete 30days old Task';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = Task::whereDate('deleted_at', '<', Carbon::now()->subDays(30))->onlyTrashed()->get();
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
        info("DeleteOldTask run!");
        //info(Task::whereDate('deleted_at', '<', Carbon::now()->subDays(30))->onlyTrashed()->get());

        return 0;
    }
}
