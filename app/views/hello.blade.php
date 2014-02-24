@extends('main.layout')
@section('content')
    <h1>@lang('app.welcome')</h1>
    <a href="/users/login">@lang('users.login')</a>
    <a href="/users/create">@lang('users.signup')</a>

    <a href="/users/twitter">@lang('users.loginTwitter')</a>
    <a href="/users/facebook">@lang('users.loginFacebook')</a>
    <a href="#" class="googleLogin">@lang('users.loginGoogle')</a>

    <div class="g-signin"></div>
    <script>
        var gData = {
            state: '{{ $state }}'
        }
    </script>
    <meta name="google-signin-clientid" content="{{ $clientId }}" />
    <meta name="google-signin-cookiepolicy" content="single_host_origin" />
    <meta name="google-signin-accesstype" content="offline" />
    <meta name="google-signin-requestvisibleactions" content="https://schemas.google.com/AddActivity" />
    <meta name="google-signin-scope" content="https://www.googleapis.com/auth/plus.login email" />
@stop