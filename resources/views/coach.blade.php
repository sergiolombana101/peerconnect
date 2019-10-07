<?php use App\Schedule;
    use Illuminate\Support\Facades\Session;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/ref.css') }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="contain-a">
    <div class="side-bar"><br>
        <div class="initial"><span class="initial-span">{{\Illuminate\Support\Facades\Session::get('user')->Initial}}</span></div><br>
        <span class="user-name">{{\Illuminate\Support\Facades\Session::get('user')->Name}}</span><br>
        <div class="space1"></div>
        <a href="/home" class="homeLink">Home</a><br><br>
        <a href="/logOut" class="logOut">Log Out</a><br><br>
        <a href="/coaches" class="view_coaches">Coaches</a>
        {{csrf_field()}}
    </div>
    <div class="container-b">
        <div class="user-left">
            <div class="user-header">
                <h2>{{ Session::get('coach')->Name }}</h2>
            </div>  
            <div class="coach-info">
                <h2>{{ Session::get('coach')->Name }}</h2>
                <img class="position-logo" src="{{asset('img/position_Logo.png')}}" height="30px" width="30px">
                <span>PeerCoach</span><br>
                <img class="id-logo" src="{{asset('img/id_Logo.png')}}" height="30px" width="30px">
                <span class="">{{ Session::get('coach')->Id }}</span><br>
                <img class="mail-logo" src="{{asset('img/mail_Logo.png')}}" height="24px" width="24px">
                <span>{{ Session::get('coach')->Mail }}</span>
            </div> 
        </div>      
        <div class="user-description">
            @if( Session::get('coach_found') == true) 
            <h2>Schedule - <span>Week {{ Session::get('schedule_arr')[5] }}</span></h2>
            <table class="table">
                <thead>
                    <th scope="col">Monday</th>
                    <th scope="col">Tuesday</th>
                    <th scope="col">Wednesday</th>
                    <th scope="col">Thursday</th>
                    <th scope="col">Friday</th>  
                    <th></th>                   
                </thead> 
                <tbody>
                <form action="/update" method="post">
                    <input name="coach_id" value="{{ Session::get('coach')->Id }}" hidden>
                    <td><select name="monday-drop1">
                            <option value="{{ Session::get('schedule_arr')[0][0] }}">{{ Session::get('schedule_arr')[0][0] }}</option>
                            <?php 
                                printSelect();
                            ?>
                            <option value="N/A">N/A</option>
                        </select>
                        <br>To<br>
                        <select name="monday-drop2">
                            <option value="{{ Session::get('schedule_arr')[0][1] }}">{{ Session::get('schedule_arr')[0][1] }}</option>
                                <?php 
                                    printSelect();
                                ?>
                                <option value="N/A">N/A</option>
                        </select>
                    </td>
                    <td>
                            <select name="tuesday-drop1">
                                    <option value="{{ Session::get('schedule_arr')[1][0] }}">{{ Session::get('schedule_arr')[1][0]}}</option>
                                    <?php 
                                        printSelect();
                                    ?>
                                    <option value="N/A">N/A</option>
                                </select>
                                <br>To<br>
                                <select name="tuesday-drop2">
                                    <option value="{{ Session::get('schedule_arr')[1][1] }}">{{ Session::get('schedule_arr')[1][1]}}</option>
                                        <?php 
                                            printSelect();
                                        ?>
                                        <option value="N/A">N/A</option>
                                </select>
                    </td>
                    <td>
                            <select name="wednesday-drop1">
                                    <option value="{{ Session::get('schedule_arr')[2][0] }}">{{ Session::get('schedule_arr')[2][0]}}</option>
                                    <?php 
                                       printSelect();
                                    ?>
                                    <option value="N/A">N/A</option>
                                </select>
                                <br>To<br>
                                <select name="wednesday-drop2">
                                    <option value="{{ Session::get('schedule_arr')[2][1] }}">{{ Session::get('schedule_arr')[2][1]}}</option>
                                       <?php
                                        printSelect();
                                        ?>
                                        <option value="N/A">N/A</option>
                                </select>
                    </td>
                    <td>
                            <select name="thursday-drop1">
                                    <option value="{{ Session::get('schedule_arr')[3][0]  }}">{{ Session::get('schedule_arr')[3][0] }}</option>
                                    <?php
                                     printSelect();
                                    ?>
                                    <option value="N/A">N/A</option>
                                </select>
                                <br>To<br>
                                <select name="thursday-drop2">
                                    <option value="{{ Session::get('schedule_arr')[3][1] }}">{{ Session::get('schedule_arr')[3][1]}}</option>
                                        <?php 
                                            printSelect();
                                        ?>
                                        <option value="N/A">N/A</option>
                                </select>
                    </td>
                    <td>
                            <select id="friday-drop1" name="friday-drop1">
                                    <option value="{{  Session::get('schedule_arr')[4][0] }}">{{ Session::get('schedule_arr')[4][0]}}</option>
                                    <?php 
                                        printSelect();
                                    ?>
                                    <option value="N/A">N/A</option>
                                </select>
                                <br>To<br>
                                <select name="friday-drop2">
                                    <option value="{{ Session::get('schedule_arr')[4][1] }}">{{ Session::get('schedule_arr')[4][1] }}</option>
                                        <?php 
                                            printSelect();
                                        ?>
                                        <option value="N/A">N/A</option>
                                </select>
                    </td>
                    <td>
                    <input type="submit" class="updateBtn" name="submit" value="Update">
                    </td>
                    {{csrf_field()}}
                    </form>
                </tbody>       
            <table>
            @else
            <form method="post" action="/add_schedule">
                <input name="coach_id" value="{{ Session::get('coach')->Id }}" hidden>
                <h2>Schedule - <span>Week
                                <select name="week" id="week_drop"> 
                                @for($x = 1; $x < 16; $x++)
                                    <option value="{{ $x }}">{{ $x }}</option>                                    
                                @endfor    
                                </select>
                                </span>
                </h2>
                <table class="table">
                    <thead>
                        <th scope="col">Monday</th>
                        <th scope="col">Tuesday</th>
                        <th scope="col">Wednesday</th>
                        <th scope="col">Thursday</th>
                        <th scope="col">Friday</th>  
                        <th scope="col"></th>                   
                    </thead>
                    <tbody>
                            <input name="coach_id" value="{{ Session::get('coach')->Id }}" hidden>
                            <td>
                            <div class="td-div">
                            <select name="monday_drop1">
                                <option value="N/A">N/A</option>
                                <?php
                                    printSelect();
                                ?>                               
                            </select>
                            <br>To<br>
                            <select name="monday_drop2">
                                <option value="N/A">N/A</option>
                                <?php
                                    printSelect();
                                ?>
                            </select>
                            </div>
                            </td>
                            <div class="td-div">
                            <td><select name="tuesday_drop1">
                                <option value="N/A">N/A</option>
                                <?php
                                    printSelect();
                                ?>
                            </select>
                            <br>To<br>
                            <select name="tuesday_drop2">
                                <option value="N/A">N/A</option>
                                <?php
                                    printSelect();
                                ?>
                            </select>
                            </div>
                            </td>
                            <td>
                            <div class="td-div">
                            <select name="wednesday_drop1">
                                <option value="N/A">N/A</option>
                                <?php
                                    printSelect();
                                ?>
                            </select>
                            <br>To
                            <select name="wednesday_drop2">
                                <option value="N/A">N/A</option>
                                <?php
                                    printSelect();
                                ?>
                            </select>
                            </div>
                            </td>
                            <td>
                            <div class="td-div">
                            <select name="thursday_drop1">
                                <option value="N/A">N/A</option>
                                <?php
                                    printSelect();
                                ?>
                            </select>
                            <br>To
                            <select name="thursday_drop2">
                                <option value="N/A">N/A</option>
                                <?php
                                    printSelect();
                                ?>
                            </select>
                            </div>
                            </td>
                            <td>
                            <div class="td-div">
                            <select name="friday_drop1">
                                <option value="N/A">N/A</option>
                                <?php
                                    printSelect();
                                ?>
                            </select>
                            <br>To
                            <select name="friday_drop2">
                                <option value="N/A">N/A</option>
                                <?php
                                    printSelect();
                                ?>
                            </select>
                            </div>
                            </td>
                            <td>
                            <input type="submit" class="updateBtn" name="submit" value="Update">
                            </td>
                            {{csrf_field()}}
                    </tbody>  
                </table>
            </form>  
            @endif 
             <br>
             <?php $day = Session::get('day');?> 
            <h2>To-Do List:</h2>
            <form action="/addTask" method="post">
                <select name="task_day" id="task_day">
                    <option name="selected" selected="selected"><?php echo $day;?></option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                </select><div class="space-2"></div>
                <input type="text" name="task" placeholder="Title...">
                <input name="userId" value="{{ Session::get('coach')->Id }}" hidden>
                {{csrf_field()}}
                <input type="submit" name="submitTask" value="Add">
            </form>
            <script type='text/javascript'>

                 
                    
                /*document.addEventListener('DOMContentLoad', function(){
                    document.queryselector('select[name="task_day"]').onchange = changeEventHandle(this);
                },false);
                function changeEventHandler(event){
                    alert("You selected "+event.target.value);
                }*/
            </script>
            @if(Illuminate\Support\Facades\Session::has('addTask'))
                @if(Illuminate\Support\Facades\Session::get('addTask') == false)               
                <div class="addTask-failed" id="addFail">Add Task <br>Unsuccessful
                        <img class="icon-s" src="{{asset('img/check.png')}}" height="25px" width="30px">
                        <p class="close-div" onclick="hideDiv('addFail')">close</p>
                </div>
                @endif
                @if(Illuminate\Support\Facades\Session::get('addTask') == true) 
                <div class="addTask-success" id="addSuccess">Add Task <br>Unsuccessful
                        <img class="icon-s" src="{{asset('img/check.png')}}" height="25px" width="30px">
                        <p class="close-div" onclick="hideDiv('addSuccess')">close</p>
                </div>
                @endif
                <!--Session::forget('addTask');-->
            @endif
            <?php
                $messages = Session::get('tasks');
                if (Session::get('tasks') == null || Session::get('tasks') == "" ){
                    $message_length = 0;
                }else{
                    $message_length = count(Session::get('tasks'));
                }
            ?>
                <table class="task-table">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Function</th>
                            </tr>
                        </thead>
                        <tbody> 
                <?php
                    if($message_length>0){
                        for($x = 0; $x<$message_length;$x++){
                        ?>
                            <tr>
                                <td>{{ $messages[$x+1] }}</td>   
                                <td>
                                    <div class='suButtons'>
                                    <form method='post' action='/delTask' id='delete-form'>
                                        <input name='userId' value='{{ Session::get('coach')->Id }}' hidden>
                                        <input name='message' value='{{ $messages[$x+1] }}' hidden>
                                        <input name='selected_day'  class='selDay' value='<?php echo $day;?>' hidden>
                                        <input class='delButt' id='delBut' type='submit' name='delMessage' value='Delete'>
                                        {{csrf_field()}}
                                    </form>
                                    
                                
                             
                                     </div>
                                </td>               
                            </tr>
                        <?php
                        }   
                    }
                ?>
                    </tbody>
                </table>
        </div>
    </div>
    </div>
    <script>
                function hideDiv(container){
                    let div = document.getElementById(container);
                    div.style.display = "none";
                }

                let selected_day = document.getElementById('task_day');
                selected_day.addEventListener('change', function(){
                   window.location.href = '/change/'+selected_day.value;
                });
                                        
    </script>
    <?php 
     function printSelect(){
        $value = "9:00";
        for($x=0;$x<14;$x++){
            $temp = explode(':', $value);
            $minutes = (int) $temp[1];
            if($minutes == 00){$minutes+=30;}
            else if($minutes == 30){$minutes="00";}
            if($x%2 != 0){
                 $hour = (int) $temp[0];
                if($hour == 12){$hour = 0;}
                 $hour += 1;
            }else{$hour = $temp[0];}
             $value = "" . $hour . ":" . $minutes;
            echo "<option value='$value'>$value</option>";
        }
    }
     ?>
</body>
</html>