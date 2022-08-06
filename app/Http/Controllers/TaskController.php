<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{

    public function index(): JsonResponse
    {

        if(auth()->user()->role === "admin"){
            $tasks = Task::orderBy("expiration_date", "desc")->where("status", "!=", "completed")->get();
        }else{
            $tasks = Task::where("user_id", auth()->user()->id)->where("status", "!=", "completed")->orderBy("expiration_date", "desc")->get();
        }


        return response()->json([
            "tasks" => $tasks,
            "message" => "Success"
        ]);
    }


    public function show($id): JsonResponse
    {
        try{
            $task = Task::where("id", $id)->with("user")->first();

            return response()->json([
                "task" => $task,
                "message" => "Success"
            ], 200);
        }catch(Exception $e){
            return response()->json([
                "message" => $e->getMessage()
            ], 400);
        }
    }


    public function store(TaskStoreRequest $request): JsonResponse
    {
        try{
            $task = new Task();

            $task->title = $request->title;
            $task->status = $request->status;
            $task->user_id = $request->user_id;
            $task->expiration_date = Carbon::createFromFormat('d/m/Y', $request->expiration_date);
            $task->description = $request->description;

            $task->save();

            return response()->json([
                "message" => "Successfully stored task"
            ], 200);

        }catch(Exception $e){

            return response()->json([
                "message" => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try{
            $task = Task::findOrFail($id);

            $task->title = $request->has("title") ? $request->title : $task->title;
            $task->expiration_date = $request->has('expiration_date') ? Carbon::createFromFormat('d/m/Y', $request->expiration_date) : $task->expiration_date;
            $task->status = $request->has("status") ? $request->status : $task->status;
            $task->user_id = $request->has("user_id") ? $request->user_id : $task->user_id;
            $task->description = $request->has("description") ? $request->description : $task->description;

            $task->save();

        }catch(Exception $e){
            return response()->json([
                "message" => $e->getMessage()
            ], 400);
        }

        return response()->json([
            "message" => "Successfully updated task"
        ], 200);
    }


    public function destroy($id): JsonResponse
    {
        try{
            Task::destroy($id);

            return response()->json([
                "message" => "Successfully deleted task"
            ], 200);

        }catch(Exception $e){
            return response()->json([
                "message" => $e->getMessage()
            ], 400);
        }
    }

    public function moveTask(Request $request): JsonResponse
    {
        try{
            $task = Task::findOrFail($request->id);

            $task->status = "completed";

            $task->save();

            return response()->json([
                "task" => $task->id,
                "message" => "Successfully updated task"
            ], 200);

        }catch(Exception $e){
            return response()->json([
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function getTasks($username): JsonResponse
    {
        $user = User::where("name", $username)->with("tasks")->first();

        if(!$user){
            throw new ModelNotFoundException("The user with this username was not found");
        }

        return response()->json($user->tasks->toArray(), 200);
    }
}
