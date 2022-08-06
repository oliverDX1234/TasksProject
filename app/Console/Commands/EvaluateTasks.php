<?php

namespace App\Console\Commands;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EvaluateTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'evaluate-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command that evaluates the tasks and sends an e-mail to
    users if one or more tasks have expired, listing the tasks that have
    expired';

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

        $tasks = Task::whereDate("expiration_date", "<", Carbon::now()->format("Y-m-d"))->with("user")->get();

        $tasksForEmails = $this->prepareTasks($tasks);

        foreach($tasksForEmails as $tasks){
            \Mail::to($tasks[0]["email"])->send(new \App\Mail\Mail($tasks));

            foreach($tasks as $task){
                $this->info("Task ". $task["title"]. " with status ". $task["status"]. " has expired on " . $task["expiration_date"]);
            }
        }


    }

    private function prepareTasks($tasks)
    {
        $response = [];

        foreach($tasks as $task){
            $response[$task->user->email][] = [
                "email" => $task->user->email,
                "title" => $task->title,
                "status" => $task->status,
                "expiration_date" => $task->expiration_date,
                "name" => $task->user->name
            ];
        }

        return $response;
    }
}
