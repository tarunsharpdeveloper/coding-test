<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function kanban()
    {
     
        return view('tasks.index');
    }
    public function cardCountTotal(){
        $count = [];
        $count['backlog'] = Task::where('phase_id',1)->count();
        $count['todo'] = Task::where('phase_id',2)->count();
        $count['doing'] = Task::where('phase_id',3)->count();
        $count['done'] = Task::where('phase_id',4)->count();
        $count['archieved'] = Task::where('phase_id',5)->count();
        $cardCount =  json_encode($count);
        return $cardCount;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \App\Models\Phase::with('tasks.user')->get();
    }
   
    /**
     * Display a listing of the Users resource.
     */
    public function users()
    {
        return \App\Models\User::all();
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
    public function store(StoreTaskRequest $request)
    {
        // Create a new task from the $request
        $task = Task::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
        return \App\Models\Phase::with('tasks.user')->where('id', $task)->get();
        
    }

   

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateTaskRequest $request, Task $task)
    // {
    //     dd($request->all());
    //     try {
    //         // Attempt to update the task
    //         $task->update($request->validated());

    //         // Check if the update was successful
    //         if ($task->wasChanged()) {
    //             return response()->json(['message' => 'Task updated successfully']);
    //         } else {
    //             return response()->json(['message' => 'Task was not modified'], 200);
    //         }
    //     } catch (\Exception $e) {
    //         // Handle any exceptions or errors
    //         return response()->json(['error' => 'An error occurred while updating the task'], 500);
    //     }
    // }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        // Update the task based on the request data
        $task->update($request->all());
        if(is_null($task->completed_at))
        {
            $task->completed_at = now();
            $task->save();
        }

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Task::destroy($task->id);
    }
}
