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
        <a href="/clockIn" class="clockIn">Clock In</a><br><br><br>
        <a href="/clockOut" class="clockOut">Clock Out</a><br><br><br><br><br>
        <a href="/logOut" class="logOut">Log Out</a>
        {{csrf_field()}}
    </div>
    <div class="lines">
    <div class="container">
        @if(\Illuminate\Support\Facades\Session::has('clockIn'))
            @if(\Illuminate\Support\Facades\Session::get('clockIn') == true)
                @if(!\Illuminate\Support\Facades\Session::has('clockIS'))
                <div class="clockIn-success" id="clockIS">Clocked In <br>Successful
                    <img class="icon-s" src="{{asset('img/check.png')}}" height="25px" width="30px">
                    <p class="close-div" onclick="hideDiv('clockIS')">close</p>
                </div>
                <?php
                    session(['clockIS'=>true]); // So it knows when message has been displayed once
                ?>
                @endif
            @endif
            @if(Illuminate\Support\Facades\Session::get('clockIn') == false)
                @if(!\Illuminate\Support\Facades\Session::has('clockIF'))
                <div class="clockIn-failed" id="clockIF">Something went wrong...
                    <img class='icon' src="{{asset('img/x.png')}}" height="25px" width="30px">
                    <p class="close-div" onclick="hideDiv('clockIF')">close</p>
                </div>
                    <?php
                    session(['clockIF'=>true]); // So it knows when message has been displayed once
                    ?>
                @endif
        @endif
            @endif
        @if(\Illuminate\Support\Facades\Session::has('clockOut'))
            @if(Illuminate\Support\Facades\Session::get('clockOut') == true)
                @if(!\Illuminate\Support\Facades\Session::has('clockOS'))
                    <div class="clockIn-success" id="clockOS">Clocked Out <br>Successful
                        <img class='icon-s' src="{{asset('img/check.png')}}" height="25px" width="30px">
                        <p class="close-div"  onclick="hideDiv('clockOS')">close</p>
                    </div>
                        <?php
                        session(['clockOS'=>true]); // So it knows when message has been displayed once
                        ?>
                @endif
            @endif
            @if(Illuminate\Support\Facades\Session::get('clockOut') == false)
                    @if(!\Illuminate\Support\Facades\Session::has('clockOF'))
                <div class="clockIn-failed" id="clockOF">Something went wrong...
                    <img class='icon' src="{{asset('img/x.png')}}" height="25px" width="30px">
                    <p class="close-div"  onclick="hideDiv('clockOF')">close</p>
                </div>
                    <?php
                    session(['clockOF'=>true]);     // So it knows when message has been displayed once
                    ?>
                @endif
            @endif

        @endif
            <script>
                function hideDiv(container){
                    let div = document.getElementById(container);
                    div.style.display = "none";
                }
            </script>
        <div class="title">
        <h2 class="sche">SCHE</h2>
        <h2 class="dule">DULE</h2>
        </div>
      <div class="schedule-con">
        <div class="monday"><div class="space"></div><span class="first-mon">MON</span><span class="second-mon">DAY</span>
            <div class="top-mon"><div class="space"></div><span>{{\Illuminate\Support\Facades\Session::get('schedule')[0][0]}}</span></div>
            <div class="line-mon"></div>
            <div class="bottom-mon"><div class="space"></div><span>{{\Illuminate\Support\Facades\Session::get('schedule')[0][1]}}</span></div>
        </div>
        <div class="tuesday"><div class="space"></div><span class="first-tues">TUES</span><span class="second-tues">DAY</span>
            <div class="top-tues"><div class="space"></div><span>{{\Illuminate\Support\Facades\Session::get('schedule')[1][0]}}</span></div>
            <div class="line-tues"></div>
            <div class="bottom-tues"><div class="space"></div><span>{{\Illuminate\Support\Facades\Session::get('schedule')[1][1]}}</span></div>
        </div>
        <div class="wednesday"><div class="space"></div><span class="first-wed">WEDNES</span><span class="second-wed">DAY</span>
            <div class="top-wed"><div class="space"></div><span>{{\Illuminate\Support\Facades\Session::get('schedule')[2][0]}}</span></div>
            <div class="line-wed"></div>
            <div class="bottom-wed"><div class="space"></div><span>{{\Illuminate\Support\Facades\Session::get('schedule')[2][1]}}</span></div>
        </div>
        <div class="thursday"><div class="space"></div><span class="first-thu">THURS</span><span class="second-thu">DAY</span>
            <div class="top-thu"><div class="space"></div><span>{{\Illuminate\Support\Facades\Session::get('schedule')[3][0]}}</span></div>
            <div class="line-thu"></div>
            <div class="bottom-thu"><div class="space"></div><span>{{\Illuminate\Support\Facades\Session::get('schedule')[3][1]}}</span></div>
        </div>
        <div class="friday"><div class="space"></div><span class="first-fri">FRI</span><span class="second-fri">DAY</span>
            <div class="top-fri"><div class="space"></div><span>{{\Illuminate\Support\Facades\Session::get('schedule')[4][0]}}</span></div>
            <div class="line-fri"></div>
            <div class="bottom-fri"><div class="space"></div><span>{{\Illuminate\Support\Facades\Session::get('schedule')[4][1]}}</span></div>
        </div>
      </div>
    </div>
    </div>
    </div>
                
    <div class="to-dolist">
        <div class="td-title">
            <h2 class="to-do">TO-DO</h2>
            <h2 class="list">LIST</h2>
        </div>
        <div class="message-box">
            <img class="to-do-backImg" src="{{asset('img/Rectangle.png')}}" height="350px" width="300px">
            <img class="on-top-img" src="{{asset('img/Vector 3.png')}}" height="350px" width="300px">
            <div class="l">
            @if(\Illuminate\Support\Facades\Session::get('message') != 'No messages. Have a great day!')
                    @php
                        $message_length = count(\Illuminate\Support\Facades\Session::get('message'));
                    @endphp
                    @for ($x = 0; $x<$message_length-2;$x++)
                        <input type="checkbox" name="check{!!$x!!}">
                    @endfor
                @endif
            </div>   
            <div class="text-list">
                @if(\Illuminate\Support\Facades\Session::get('message') == 'No messages. Have a great day!')
                    <div class="space"></div>
                    <div class="message-empty">{!! session('message') !!}</div>
                @else
                    @php
                        $message_length = count(\Illuminate\Support\Facades\Session::get('message'));
                    @endphp
                    @for ($x = 0; $x<$message_length-1;$x++)
                        <div class="task"><span class="task-span">{!! session('message')[$x+1] !!}</span></div>
                    @endfor
                @endif
            </div>    
       
        </div>
    </div>
</body>
</html>
