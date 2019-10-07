@extends('layouts.app')
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('css/login.css')}}"/>
</head>
<div class="back-img">
    <div class="div">
            <img class="logo" src="{{asset('img/peerConnectLogo.png')}}" height="80px" width="500px">
    </div>
    <div class="box">
        <h2>Login</h2>
        <form action= "{{ route('login') }}" method="post">
            <div class="inputBox">
                <input type="text" name="userId" value='{{ old('Id') }}' required="">
                <label>Student Id</label>
                @if(Illuminate\Support\Facades\Session::has('flash_message_error'))
                    <p class="pMessage">{!! session('flash_message_error') !!}</p>
                @endif
                @if(Illuminate\Support\Facades\Session::has('auth'))
                    <p class="pMessage">{!! session('auth') !!}</p>
                @endif
            </div>
            {{csrf_field()}}
            <input type="submit" class="submitBtn" name="submit" value="Log In">
        </form>
    </div>
</div>

</html>
