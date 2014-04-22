@extends('main.layout')
@section('content')
{{ Form::open(array('url' => 'users/create')) }}
<h1>@lang('users.signup')</h1>

@if($errors->count() > 0)
<p class="message error">
    {{ $errors->first('email') }}
    {{ $errors->first('name') }}
    {{ $errors->first('password') }}
    {{ $errors->first('password_confirmation') }}
</p>
@endif

<p>
    {{ Form::label('email', Lang::get('users.email')) }}
    {{ Form::text('email', Input::old('email'), array('placeholder' => 'email@lunargravity.be')) }}
</p>

<p>
    {{ Form::label('name', Lang::get('users.name')) }}
    {{ Form::text('name', Input::old('name'), array('placeholder' => Lang::get('users.yourName'))) }}
</p>

<p>
    {{ Form::label('password', Lang::get('users.password')) }}
    {{ Form::password('password') }}
</p>
<p>
    {{ Form::label('password_confirmation', Lang::get('users.confirmPassword')) }}
    {{ Form::password('password_confirmation') }}
</p>


<p>{{ Form::submit(Lang::get('users.signup')) }}</p>
{{ Form::close() }}
@stop