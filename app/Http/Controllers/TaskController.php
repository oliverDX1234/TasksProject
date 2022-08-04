<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Models\Task;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
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
                "message" => "success"
            ], 200);

        }catch(Exception $e){

            return response()->json([
                "message" => $e->getMessage()
            ], 400);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
