<?php

namespace App\Http\Controllers;
use App\Data;
use App\Record;
use Illuminate\Support\Facades\Input;

use App\Schedule;
use App\Task;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Console\Helper\Table;

class Controller extends BaseController
{
    
    function clockIn(){
        $id = Session::get('id');
        $user = Data::find($id);
        $temp = date('y-m-d h:i');
        $date = explode(' ', $temp);
        $dayOfWeek = date("l", strtotime($date[0]));
        $clockedIn = false;
        try {
            DB::table('record')->insert(
                ['Id' => $id, 'Day' => $date[0], 'ClockIn' => $date[1], 'WeekDay' => $dayOfWeek]
            );
            $clockedIn = true;
        } catch (QueryException $ex) {
            $clockedIn = false;
        }
        if($clockedIn){
            session_start();
            session(['clockIn'=>true]);
            return view('index');
        }else{
            session_start();
            session(['clockIn'=>false]);
            return view('index');
        }

        }
    function clockOut(){
        $id = Session::get('id');
        $temp = date('y-m-d h:i');
        $date = explode(' ', $temp);
        $clockedOut = false;
        try {
            $user = Record::where(['Id'=>$id, 'Day'=>$date[0]])->update(['ClockOut'=>$date[1]]); 
            $clockedOut = true;
        }catch(QueryException $ex){
            $clockedOut = false;
        }
        if($clockedOut){
            session_start();
            session(['clockOut'=>true]);
            return view('index');
        }else{
            session_start();
            session(['clockOut'=>false]);
            return view('index');
        }

    }
    function view(Request $request){
        $id = $request->input('coach_Id');
        $coach = Data::find($id);
        $day = /*date('l')*/'Friday'; 
        $schedule = Schedule::find($id);
        $tasks = DB::select('SELECT '.$day.' FROM task WHERE Id= '.$id);
        if($tasks != null){
            $tasks = explode(',',$tasks[0]->$day);
            foreach($tasks as $key=> $task){
                if(empty($task)){
                    unset($tasks[$key]);
                }
            }
        }
        if($schedule == null){
            session_start();
            session(['coach'=>$coach]);
            session(['tasks'=>$tasks]);
            session(['day'=>$day]);
            session(['coach_found'=>true]);
            return view('coach');
        }
        $monday = explode('-', $schedule->Monday);
        $tuesday = explode('-', $schedule->Tuesday);
        $wednesday = explode('-', $schedule->Wednesday);
        $thursday = explode('-', $schedule->Thursday);
        $friday = explode('-', $schedule->Friday);
        $schedule_arr = [$monday, $tuesday, $wednesday, $thursday, $friday];
        for ($x = 0; $x < count($schedule_arr); $x++) {
            if (count($schedule_arr[$x]) < 2) {
                $schedule_arr[$x][0] = 'N/A';
                $schedule_arr[$x][1] = 'N/A';
            }
        }
        $week = $schedule->Week;
        array_push($schedule_arr, $week);
        session_start();
        session(['coach'=>$coach]);
        session(['schedule_arr'=>$schedule_arr]);
        session(['tasks'=>$tasks]);
        session(['day'=>$day]);
        session(['coach_found'=>true]);
        return view('coach');
        //return view('coach', ['coach'=>$coach], ['schedule'=>$schedule_arr], ['tasks'=>$tasks]);
        
    }
    function update(Request $request){
        $id = $request->coach_id;
        $content = Schedule::find($id);
        $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday');
        foreach($days as $day){
            $temp1 = $request->get($day."-drop1");
            $temp2 = $request->get($day."-drop2");
            $complete = $temp1."-".$temp2;
            $capDay = ucfirst($day);
            $content->$capDay = $complete;
        }
        $day = date('l');
        session(['day'=>$day]);
        $content->save();
        return back(); 

    }
    function delTask(Request $request){
        $id = $request->userId;
        $day = $request->selected_day;
        //echo "SELECTED DAY:".$day."<br>";
        $content = Task::find($id);
        $delMessage = $request->message;
        $message = $content->$day;
        $message = explode(',',$message);
        $newMessages = [];
        $count = 0;
        foreach($message as $m){
            if($delMessage == $m){echo "true";}
            if($delMessage != $m){
                $newMessages[$count] = $m;
                $count++;
            }
        }
        $message = implode(',',$newMessages);
        $content->$day = $message;
        $content->save();
        $tasks = DB::select('SELECT '.$day.' FROM task WHERE Id= '.$id);
        $tasks = explode(',',$tasks[0]->$day);
        foreach($tasks as $key=> $task){
            if(empty($task)){
                unset($tasks[$key]);
            }
        }
        session(['tasks'=>$tasks]);
        session(['day'=>/*$day*/'Friday']);
        return back();
      /*-------------------------------------------
       $newMessages = [];
        $count = 0;
        if($message!=null){
            $messages = explode(',',$message);
            foreach($messages as $m){
                if($m != $delMessage){
                    $newMessages[$count] = $m;
                }
            }
            $content->Message = $newMessages;
            $content->save();
            return back();
        }
        return back();------------------------------------------------------*/
    }
    
    function addTask(Request $request){
        $id = $request->userId;
        $content = Task::find($id);
        $day =  $request->task_day; 
        $day_tasks = $content->$day;
        $newTask = $request->get("task");
        $addTask = "";
        if($day_tasks == null || $day_tasks == ""){
            $day_tasks = ",".$newTask.",";
        }else{
            $day_tasks = $day_tasks.$newTask.",";
        }
        $content->$day = $day_tasks;
        $content->save();
        $coach = Data::find($id);
        $schedule = Schedule::find($id);
        $monday = explode('-', $schedule->Monday);
        $tuesday = explode('-', $schedule->Tuesday);
        $wednesday = explode('-', $schedule->Wednesday);
        $thursday = explode('-', $schedule->Thursday);
        $friday = explode('-', $schedule->Friday);
        $schedule_arr = [$monday, $tuesday, $wednesday, $thursday, $friday];
        for ($x = 0; $x < count($schedule_arr); $x++) {
            if (count($schedule_arr[$x]) < 2) {
                $schedule_arr[$x][0] = 'N/A';
                $schedule_arr[$x][1] = 'N/A';
            }
        }
        $week = $schedule->Week;
        array_push($schedule_arr, $week);
        $tasks = DB::select('SELECT '.$day.' FROM task WHERE Id= '.$id);
        $tasks = explode(',',$tasks[0]->$day);
        if($newTask == ""){
            session_start();
            session(['addTask'=>false]);
            session(['day'=>/*$day*/'Friday']);
            return back();
        }
        /*$message_arr = explode(',',$message);
        foreach($message_arr as $mess){
            if(strtolower($mess) == strtolower($newTask)){
                session_start();
                session(['addTask'=>false]);
                return view('coach',['coach'=>$content], ['schedule'=>$schedule_arr]);
            }
        }*/
        session_start();
        session(['addTask'=>true]);
        session(['coach'=>$coach]);
        session(['schedule_arr'=>$schedule_arr]);
        session(['tasks'=>$tasks]);
        session(['day'=>/*$day*/'Friday']);
        return back();
        //return \Redirect::route('/coach', ['coach'=>$coach, 'schedule_arr'=>$schedule_arr, 'tasks'=>$tasks]);
        //return Redirect::to('/coach', array('coach'=>$coach , 'schedule_arr'=>$schedule_arr , 'tasks'=>$tasks));
        //return view('coach', compact(['coach','schedule_arr','tasks']));
    }

    function day_changed($day, Request $request){
        $coach = Session::get('coach');
        $id = $coach->Id;
        $content = Task::find($id);
        $tasks = $content->$day;
        if($tasks == null){
            session_start();
            session(['tasks'=>null]);
            session(['day'=>/*$day*/'Friday']);
            return view('coach');
        }
        $tasks = explode(',',$tasks);
        foreach($tasks as $key=> $task){
            if(empty($task)){
                unset($tasks[$key]);
            }
        }
        session_start();
        session(['tasks'=>$tasks]);
        session(['day'=>/*$day*/'Friday']);
        return view('coach');
    }
    function manage_coaches(){
        $content= Data::paginate(5);
        session(['coach_found'=>null]);
        return view('coaches', ['content'=>$content]);
    }
    function addCoach(Request $request){
        $new_name = $request->first_name;
        $new_id = $request->new_id;
        $new_email = $request->new_email;
        $initial = $new_name[0];
        try{
            DB::table('login')->insert(
                ['Id' => $new_id, 'Name' => $new_name, 'Initial' => $initial, 'Admin' => 0, 'Mail'=>$new_email]
            );
        }catch (QueryException $ex) {
            session(['failed'=>true]);
            return back();
    }
        session(['failed'=>false]);
        return redirect('/coaches');

    }
    function add_schedule(Request $request){
        $id = $request->coach_id;
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $count = 1;
        foreach($days as $day){
            $name = $day.''.$count;
            $$name = $request->$$name;
            echo $$name;
            $count++;
            if($count == 2){
                $count = 1;
            }
        }

    }
}

   