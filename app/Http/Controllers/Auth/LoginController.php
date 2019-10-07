<?php

namespace App\Http\Controllers\Auth;

use App\Data;
use App\Task;
use App\Admin;
use App\Http\Controllers\Controller;
use App\Schedule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Console\Helper\Table;

class LoginController extends Controller
{

    function construct(Request $request){
        if(Session::has('logged')){
            $logged = Session::get('logged');
            if($logged == false){               
                return false;
            }else{
                return true;
            }
        }else{
                return false;
        }
    }
    
    public function login(Request $request){
        $id = $request->userId;
        $user = Data::find($id);
        session_start();
        if($user == null){
            return back()
                ->with('flash_message_error', 'Student Id not found')
                ->withInput(request(['userId']));
        }
        
        $admin = $user->Admin;
        session_destroy();
        if($admin == 1){
            session_start();
            session(['id' => $id]);
            return view('auth\login-admin');
        } else {
            $schedule = Schedule::find($id);
            $monday = explode('-', $schedule->Monday);
            $tuesday = explode('-', $schedule->Tuesday);
            $wednesday = explode('-', $schedule->Wednesday);
            $thursday = explode('-', $schedule->Thursday);
            $friday = explode('-', $schedule->Friday);
            $schedule_arr = [$monday, $tuesday, $wednesday, $thursday, $friday];
            $dayoWeek = date('l');
            $tasks = Task::find($id);
            $message = $tasks->$dayoWeek;
            if ($message == NULL) {
                session(['message' => 'No messages. Have a great day!']);
            } else {
                $message_arr = explode(',', $message);
                foreach($message_arr as $key=> $task){
                    if(empty($task)){
                        unset($tasks[$key]);
                    }
                }
                session(['message' => $message_arr]);
            }
            for ($x = 0; $x < count($schedule_arr); $x++) {
                if (count($schedule_arr[$x]) < 2) {
                    $schedule_arr[$x][0] = 'N/A';
                    $schedule_arr[$x][1] = 'N/A';
                }
            }
            session_start();
            session(['id' => $id]);
            session(['user' => $user]);
            session(['schedule' => $schedule_arr]);
            session(['logged'=>true]);
            return view('index');
        }

    }
    public function logout(){
        Auth::logout();
        Session::flush();
        return redirect('/');
    }
    public function back(Request $request){
        $authenticated = $this->construct($request);
        if($authenticated){
            return view('admin');
        }else{
            return $this->auth();
        }
        return view('admin');
    }
    public function auth(){
        session_start();
        session_destroy();
        session_start();
        session(['auth'=>'You have to be logged in before proceeding!']);
        return view('auth\login');
    }
     public function login_admin(Request $request){
        $id = Session::get('id');
        $temp_pass = $request->adminPass;
        $content = Admin::find($id);
        $pass = $content->Password;
        $user = Data::find($id);
        $ascii_pass = '';
        session_start();
        for($x = 0; $x<strlen($pass);$x++){
            $int_char = ord($pass[$x]);
            $int_char += 6;  
            $ascii_pass .= chr($int_char);
        }
        if($ascii_pass != $temp_pass){
            session(['error'=>'Incorrect Password']);
            return view('auth/login-admin');
        }else{
            session_destroy();
            $coaches = DB::select('SELECT*FROM login WHERE Admin = 0');
            session_start();
            session(['id'=> $id]);
            session(['coaches'=> $coaches]);
            session(['user' => $user]);
            session(['logged'=>true]);
            return view('admin');
        }
        
    }
    function returnAdmin(Request $request){ 
        $authenticated = $this->construct($request);
        if($authenticated){
            $coaches = DB::select('SELECT*FROM login WHERE Admin = 0');
            session(['coaches'=>$coaches]);
            session(['coach_found'=>null]);
            return view('admin');
        }else{
            return $this->auth();
        }
    }
    function index(Request $request){
        $authenticated = $this->construct($request);
        if($authenticated){
            return view('index');
        }else{
            return $this->auth();
        }
    }    
    

}