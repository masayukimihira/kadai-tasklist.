<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            //$tasks = Task::all();
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            
            return view('tasks.index', $data);
        }else {
            return view('welcome');
    }
    
    }
    
    public function create()
    {
      if (\Auth::check()) {
        $task = new Task;
      }
      else{
        return view('tasks.create', [
            'task' => $task,
        ]);}
    }
    
    
    public function store(Request $request)
    {
        if (\Auth::check()) {
        $this->validate($request, [
            'status' => 'required|max:10',
            'content' => 'required|max:191',
        ]);

        $task = new Task;
        $task->status = $request->status;  
        $task->content = $request->content;
        $task->user_id = \Auth::user()->id;
        $task->save();}
        
       else{
        return redirect('/');}
    }

   
    public function show($id)
    {
        if (\Auth::check()) {
           $task = Task::find($id); 
            
            if (\Auth::user()->id === $task->user_id) {
                return view('tasks.show', [
                    'task' => $task,
                ]);   
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/wellcome');
            
        }
        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      if (\Auth::check()) {
           $task = Task::find($id); 
            
            if (\Auth::user()->id === $task->user_id) {
                return view('tasks.edit', [
                    'task' => $task,
                ]);   
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/wellcome');
            
        }
    }

   
    public function update(Request $request, $id)
    {
        if (\Auth::check()){
        $this->validate($request, [
            'status' => 'required|max:10', 
            'content' => 'required|max:10',
        ]);
       
        
        $task = Task::find($id);
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();}

        return redirect('/');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        
        
         if (\Auth::user()->id === $task->user_id) {
            $task->delete();
        }

        return redirect('/');
    }
}