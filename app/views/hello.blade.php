@extends('layout')
@section('content')
    <h1>@lang('app.welcome')</h1>
    <a href="/users/login">@lang('users.login')</a>
    <a href="/users/create">@lang('users.signup')</a>
@stop