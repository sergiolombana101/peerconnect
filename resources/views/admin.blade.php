<?php use App\Schedule;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\DB;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/ref.css') }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<script>
src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
</script>
`<!--<script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> <!-- SCRIPT FRO GOOGLE FOR THE PIECHART -->
<?php
                                    
                                    $days = [
                                        "Monday"=>0,
                                        "Tuesday"=>0,
                                        "Wednesay"=>0,
                                        "Thursday"=>0,
                                        "Friday"=>0
                                    ];
                                    $counter = 0;
                                    $on_Time = 0;
                                    $ten_Min = 0;
                                    $late = 0;
                                   // foreach($days as $day){
                                        $result = DB::table('schedule')->select('Monday')->where('user_id','101137768')->get();
                                       // $result = DB::table('schedule')->where('Id', $coaches[$counter]->Id)->value($day);  
                                        $counter++;  
                                        $time = explode('":"', $result);
                                        $time = str_replace('"}]','', $time);
                                        $start_Time = explode('-',$time[1]);
                                        $start_Time = $start_Time[0];
                                        $clock = DB::table('record')->select('ClockIn')->where('Id', '101137768')->get();
                                        $clocked_In = explode('":"', $clock);
                                        if(count($clocked_In) > 1){
                                            $clocked_In = str_replace('"}]','',$clocked_In);
                                            $clocked_In = $clocked_In[1];
                                            $temp_Hour = explode(":", $clocked_In);
                                            $element = $temp_Hour[0];
                                            $element1 = $temp_Hour[1];
                                            if($element[0] == '0'){
                                                $element = 12+$element[1]; 
                                                $temp_Hour[0] = $element;   
                                            } 
                                            if($element1[0] == '0'){                                        
                                                $temp_Hour[1] = $element1[1]; 
                                            } 
                                                                                
                                            $clocked_In = ["Hour"=> $temp_Hour[0], "Minutes"=>$temp_Hour[1]];
                                            $temp_Hour = explode(":", $start_Time);
                                            $element = $temp_Hour[0];
                                            $element1 = $temp_Hour[1];
                                            
                                            if($element[0] == '0'){
                                                $element = 12+$element[1]; 
                                                $temp_Hour[0] = $element;   
                                            } 
                                            if($element1[0] == '0'){                                        
                                                $temp_Hour[1] = $element1[1]; 
                                            }   
                                                                        
                                            $start_Time = ["Hour"=> $temp_Hour[0], "Minutes"=>$temp_Hour[1]];

                                            if($clocked_In['Hour']<$start_Time['Hour']){ // IF CLOCK IN HOUR IS LESS THAN SCHEDULE HOUR
                                                $on_Time++;
                                            }
                                            
                                            else if($clocked_In['Hour']==$start_Time['Hour']){ 
                                                if($clocked_In['Minutes']>=$start_Time['Minutes']&&$clocked_In['Minutes']<=$start_Time['Minutes']+5){ // IF CLOCKED IN HOUR IS EQUAL AND MINUTES ARE LESS OR EQUAL TO FIVE MINUTES PASS THE MINUTES IN THE SCHEDULE
                                                    $on_Time++;                                             
                                                }else if($clocked_In['Minutes']>$start_Time['Minutes']+5 && $clocked_In['Minutes']<=$start_Time['Minutes']+10){ // IF CLOCKED MINUTES ARE MORE THAN 5 MINUTES PASS BUT LESS THAN 10 MINUTES PASS THE MINUTES IN THE SCHEDULE
                                                    $ten_Min++;
                                                    
                                                }else{ // IF MINUTE IN CLOCK IN IS MORE THAN 10 MINUTES PASS THE MINUTES IN THE SCHEDULE MINUTES
                                                    $late++;    
                                                }
                                            }                                                                          
                                        else if($clocked_In['Hour']>$start_Time['Hour']){ // IF CLOCKED IN HOUR IS MORE THAN SCHEDULE HOUR THEN IS LATE
                                                $late++;
                                            }
                                        }else{  // IF THERE ARE NO RECORDS ON THE TABLE 
                                            $on_Time = 1;
                                            $ten_Min = 0;
                                            $late = 0;
                                        }
                                        // }HERE GOES THE END OF FOREACH
                                                             
                                ?>
                                <script type="text/javascript">
                                    google.charts.load('current', {'packages':['corechart']});
                                    google.charts.setOnLoadCallback(drawChart);

                                    function drawChart(){
                                        let on_Time = <?php echo $on_Time; ?>;
                                        let late = <?php echo $late; ?>;
                                        let ten_Min = <?php echo $ten_Min; ?>;

                                        var data = google.visualization.arrayToDataTable([
                                            ['Task', 'Quantity'],
                                            ['On Time', on_Time],
                                            ['Late', late],
                                            ['10 Minutes Late', ten_Min]
                                        ]);
                                        var options = {
                                             is3D: false,
                                             backgroundColor: 'transparent',
                                             height: "100%",
                                             width: "100%",
                                             chartArea: {
                                                 height: "100%",
                                                 width: "100%"
                                             }
                                            
                                             };    

                                        var chart = new google.visualization.PieChart(document.getElementById('pie-chart'));
                                        chart.draw(data, options);
                                        
                                    }
                                                                            
                                </script>
                           
<body>
   <script>
        function coach_onclick(coach_Id){
            let url = '{{ route("coach")}}';
            window.location.href = url;
        }
    </script>
    <div class="contain">
        <div class="side-bar"><br>
            <div class="initial"><span class="initial-span">{{\Illuminate\Support\Facades\Session::get('user')->Initial}}</span></div><br>
            <span class="user-name">{{\Illuminate\Support\Facades\Session::get('user')->Name}}</span><br>
            <div class="space1"></div>
            <a href="/logOut" class="logOut">Log Out</a><br><br>
            <a href="/coaches" class="view_coaches">Coaches</a>
            {{csrf_field()}}
        </div>
        <div class="stats-container">
            <div class="myClock-title"><b>My Clock</b></div>
            <div class="myClock-arrowLine"></div>
            <div class="myClock-arrow"><i class="right"></i></div>
            <div class="clock-div">
                <canvas class="canva-clock" id="canvas" width="230" height="230"></canvas>
            </div>
            <div class="punctuality-title"><b>Punctuality</b></div>
            <div class="punctuality-arrowLine"></div>
            <div class="punctuality-arrow"><i class="left"></i></div>
            <div class="peercoaches-div">
                <!--<div class="punctuality"><b>PUNCTUALITY</b></div>-->
                <div class="pie-chart-div"><div class="pie-chart" id="pie-chart"></div></div>
            </div>
        </div>
        <div class="line"></div>
        <div class="coach-title-div">
            <div class="coach-title"><b>COACHES</b></div>
        </div>
        <div class="coaches">
        @if(Illuminate\Support\Facades\Session::has('coaches'))
                        @php
                            $coaches = Illuminate\Support\Facades\Session::get('coaches');
                            $counter = 0;
                        @endphp
                        @foreach($coaches as $coach)
                            <?php 
                                if($counter == 3){
                                    $counter = 0;
                                }
                                $counter += 1;
                            ?>
                            @if($counter%2 != 0 && $counter%3 != 0)
                            <div class="side-left">
                                <div class="identifier">
                                <div class="card">
                                    <input type="checkbox" id="card1" class="more" aria-hidden="true">
                                    <div class="content">
                                        <div class="front">
                                            <div class="inner">
                                                <h2>{{ $coach->Name }}</h2>
                                                <div class="student-id">{{ $coach->Id }}</div>
                                                <label for="card1" class="view-coachd" aria-hidden="true">Details</label>      
                                            </div>
                                        </div>
                                        <div class="back">
                                            <div class="inner">
                                                <h2>{{ $coach->Id }}</h2>
                                                <div class="email">Email: <span>{{ $coach->Mail }}</span></div>
                                                <div class="view-buttons">
                                                    <form method="post" action="{{ route('coach') }}">
                                                        <input type="text" name="coach_Id" value="{{ $coach->Id }}" hidden>
                                                        <input type="submit" class="view-coach" value="View Schedule">
                                                        {{csrf_field()}}
                                                    </form>
                                                </div>
                                            <label for="card1" class="return" aria-hidden="true">
                                                return
                                            </label>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="block"></div>
                            </div>
                            <br>
                        </div>
                        @elseif($counter%2 == 0)
                        <div class="side-middle">
                                <div class="identifier">
                                <div class="card">
                                    <input type="checkbox" id="card1" class="more" aria-hidden="true">
                                    <div class="content">
                                        <div class="front">
                                            <div class="inner">
                                                <h2>{{ $coach->Name }}</h2>
                                                <div class="student-id">{{ $coach->Id }}</div>
                                                <label for="card1" class="view-coachd" aria-hidden="true">Details</label>      
                                            </div>
                                        </div>
                                        <div class="back">
                                            <div class="inner">
                                                <h2>{{ $coach->Id }}</h2>
                                                <div class="email">Email: <span>{{ $coach->Mail }}</span></div>
                                                <div class="view-buttons">
                                                    <form method="post" action="{{ route('coach') }}">
                                                        <input type="text" name="coach_Id" value="{{ $coach->Id }}" hidden>
                                                        <input type="submit" class="view-coach" value="View Schedule">
                                                        {{csrf_field()}}
                                                    </form>
                                                </div>
                                            <label for="card1" class="return" aria-hidden="true">
                                                return
                                            </label>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="block"></div>
                            </div>
                            <br>
                        </div>
                        @elseif($counter%3 == 0)
                        <div class="side-right">
                                <div class="identifier">
                                <div class="card">
                                    <input type="checkbox" id="card1" class="more" aria-hidden="true">
                                    <div class="content">
                                        <div class="front">
                                            <div class="inner">
                                                <h2>{{ $coach->Name }}</h2>
                                                <div class="student-id">{{ $coach->Id }}</div>
                                                <label for="card1" class="view-coachd" aria-hidden="true">Details</label>      
                                            </div>
                                        </div>
                                        <div class="back">
                                            <div class="inner">
                                                <h2>{{ $coach->Id }}</h2>
                                                <div class="email">Email: <span>{{ $coach->Mail }}</span></div>
                                                <div class="view-buttons">
                                                    <form method="post" action="{{ route('coach') }}">
                                                        <input type="text" name="coach_Id" value="{{ $coach->Id }}" hidden>
                                                        <input type="submit" class="view-coach" value="View Schedule">
                                                        {{csrf_field()}}
                                                    </form>
                                                </div>
                                            <label for="card1" class="return" aria-hidden="true">
                                                return
                                            </label>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="block"></div>
                            </div>
                            <br>
                        </div>
                        @endif      
                                <div class="space-2"></div>
                        @endforeach

                @endif

           
            <!--<div class="card">
                <div class="card-front"></div>
                <div class="card-back">
                    <h2>Student Id<br><span>2902029</span></h2>
                    <div class="email"><span>Email: mail@georgebrown.ca</span></div>
                    <form method="post" action="{{ route('coach') }}">
                        <input type="text" value="8297829" hidden>
                        <input type="submit" class="view-coach" value="View Details">
                        {{csrf_field()}}
                    </form>                   
                </div>
            </div>-->
        </div>
    </div>
    <script>
            let cards = document.getElementsByClassName('view-coachd');
            let content = document.getElementsByClassName('content');
            let returns = document.getElementsByClassName('return');
            for (let x = 0; x<cards.length;x++){
                cards[x].addEventListener('click', function(){
                    content[x].style.transform = 'rotateY(180deg)';
                });
            }
            for(let i = 0; i<returns.length;i++){
                returns[i].addEventListener('click', function(){
                    content[i].style.transform = 'rotateY(0deg)';
                });
            }  
    </script>
    <script>
        let canvas = document.getElementById("canvas");
        let ctx =   canvas.getContext("2d");
        let radius = canvas.height / 2;
        ctx.translate(radius, radius);
        radius = radius * 0.90
        setInterval(drawClock, 1000);

        function drawClock(){
            drawFace(ctx, radius);
            drawNumbers(ctx, radius);
            drawTime(ctx, radius);
        }
        function drawFace(ctx, radius){
            let grad;
            ctx.beginPath();
            ctx.arc(0,0,radius,0,2*Math.PI); // This start drawing the circle
            ctx.fillStyle = '#001f3f'; // This sets the background color of that circle
            ctx.fill();
            grad = ctx.createRadialGradient(0,0,radius*0.95,0,0,radius*1.05); //Is used to fill thr circle with a gradient and assigns it to variable grad
            grad.addColorStop(0, '#39CCCC'); //Adds the color to the hands
            grad.addColorStop(0.5, 'white'); //Adds color to the border
            grad.addColorStop(1, 'green');
            ctx.strokeStyle = grad; //Sets the color used for strokes or drawings...
            ctx.lineWidth = radius*0.03;
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(0,0,radius*0.1,0,2*Math.PI);
            ctx.fillStyle = 'orange'; // COLOR FOR THE NUMBERS
            ctx.fill();
        }
        function drawNumbers(ctx, radius){
            let ang;
            let num;
            ctx.font = radius*0.15 + "px arial";
            ctx.textBaseline = "middle";
            ctx.textAlign = "center";
            for(num = 1; num<13;num++){
                ang = num * Math.PI /6;
                ctx.rotate(ang);
                ctx.translate(0,-radius*0.85);
                ctx.rotate(-ang);
                ctx.fillText(num.toString(),0,0);
                ctx.rotate(ang);
                ctx.translate(0,radius*0.85);
                ctx.rotate(-ang);

            }
        }
        function drawTime(ctx, radius){
            let now = new Date();
            let hour = now.getHours();
            let minute = now.getMinutes();
            let second = now.getSeconds();
            //HOUR
            hour = hour%12;
            hour = (hour*Math.PI/6)+(minute*Math.PI/(6*60))+(second*Math.PI/(360*60));
            drawHand(ctx,hour,radius*0.5, radius*0.05);
            //MINUTE
            minute = (minute*Math.PI/30)+(second*Math.PI/(30*60));
            drawHand(ctx,minute,radius*0.8,radius*0.03);
            //SECOND
            second = (second*Math.PI/30);
            drawHand(ctx, second, radius * 0.9, radius*0.01);
        }
        function drawHand(ctx, pos, length, width){
            ctx.beginPath();
            ctx.lineWidth = width;
            ctx.lineCap = "round";
            ctx.moveTo(0,0);
            ctx.rotate(pos);
            ctx.lineTo(0, -length);
            ctx.stroke();
            ctx.rotate(-pos);
        }
    </script>
            <!--
            <div class="title-a">
            <h2 class="coaches-title">COACHES</h2>
            </div>
            <div class="instruction"><b>*Click in the huskee to view coach schedule and details</b></div>
                <div class="coaches">

                    @if(Illuminate\Support\Facades\Session::has('coaches'))
                        @php
                            $coaches = Illuminate\Support\Facades\Session::get('coaches');
                            $counter = 1;
                        @endphp
                        @foreach($coaches as $coach)
                            @if($counter%2 != 0 && $counter%3 != 0)
                                <div class="side-left">
                                    <div class="dot"></div>
                                    <img class="vector" src="{{asset('img/Vector.png')}}" height="20px" width="20px">
                                    <div class="rectangle1"></div>
                                    <div class="rectangle2"></div>
                                    <div class="circle">
                                         <form method="post" action="{{ route('coach') }}">
                                         <img class="huskee" src="{{asset('img/Huskee.png')}}" height="80px" width="80px">{{csrf_field()}}
                                         <input class="submit_coach" name="coach_Id" type="submit" value="{{ $coach->Id }}"></form>
                                    </div>
                                    <div class="info">INFO</div>
                                    <div class="Name_lbl">NAME:</div>
                                    <div class="Name_value">{{ $coach->Name }}</div>
                                    <div class="Student_Id">ID:</div>
                                    <div class="Id_value">{{ $coach->Id }}</div>                        
                                </div>
                            @elseif($counter%2 == 0)
                                <div class="side-middle">
                                        <div class="rectangle1"></div>
                                        <div class="rectangle2"></div>                                       
                                        <div class="circle">
                                            <img class="huskee" src="{{asset('img/Huskee.png')}}" height="80px" width="80px" onclick="coach_onclick1({{ $coach->Id }})" >
                                            <form method="post"><input name="coach_Id" value="{{ $coach->Id }}" hidden></form>
                                        </div>
                                        <div class="info">INFO</div>
                                        <div class="Name_lbl">NAME:</div>
                                        <div class="Name_value">{{ $coach->Name }}</div>
                                        <div class="Student_Id">ID:</div>
                                        <div class="Id_value">{{ $coach->Id }}</div>
                                </div>
                            @elseif($counter%3 == 0)
                                <div class="side-right">
                                            <div class="rectangle1"></div>
                                            <div class="rectangle2"></div>
                                            <div class="circle">
                                                <img class="huskee" src="{{asset('img/Huskee.png')}}" height="80px" width="80px" onclick="coach_onclick({{ $coach->Id }})">
                                                <form method="post"><input name="coach_Id" value="{{ $coach->Id }}" hidden></form>
                                            </div>
                                            <div class="info">INFO</div>
                                            <div class="Name_lbl">NAME:</div>
                                            <div class="Name_value">{{ $coach->Name }}</div>
                                            <div class="Student_Id">ID:</div>
                                            <div class="Id_value">{{ $coach->Id }}</div>
                                    </div>
                            @endif      
                                <div class="space-2"></div>
                        @endforeach

                    @endif
                    
                </div>
                <div class="line"></div>    
                <div class="stats">
                    <div class="stats-title">
                        <span>STATS</span><br>
                        <span>PEERCONNECT TEAM</span>
                    </div>
                    <div class="boxes">
                        <div class="clock-div">
                            <div class="my-clock"><b>MY CLOCK</b></div>
                            <div class="clock">
                                <span></span>
                                <div class="hours-container">
                                    <div class="hours"></div>
                                </div>
                                <div class="minutes-container">
                                    <div class="minutes"></div>
                                </div>
                                <div class="seconds-container">
                                    <div class="seconds"></div>
                                </div>
                            </div>
                        </div>
                        <div class="peercoaches-div">
                            <div class="punctuality"><b>PUNCTUALITY</b></div>
                            <div class="pie-chart-div"><div class="pie-chart" id="pie-chart"></div></div>
                        </div>
                        
                    </div>    
                </div> -->
                
       
</body>
</html>
<?php

?>
