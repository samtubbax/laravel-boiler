{{ Form::open(array('url' => 'users/login')) }}
    <h1>@lang('users.login')</h1>

    @if($errors->count() > 0)
    <p class="message error">
        {{ $errors->first('email') }}
        {{ $errors->first('password') }}
    </p>
    @endif
    @if($invalidLogin)
    <p class="message error">
        {{ $invalidLogin }}
    </p>
    @endif

    <p>
        {{ Form::label('email', Lang::get('users.email')) }}
        {{ Form::text('email', Input::old('email'), array('placeholder' => 'email@uccbm.com')) }}
    </p>

    <p>
        {{ Form::label('password', Lang::get('users.password')) }}
        {{ Form::password('password') }}
    </p>

    <p>{{ Form::submit(Lang::get('users.login')) }}</p>
{{ Form::close() }}