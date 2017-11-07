@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Rogue', 'subtitle' => 'Please log in with your DoSomething.org admin account to continue.
'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <p>
                    Welcome to <strong>Rogue</strong>, our one source of truth for all things user activity. Logging in will allow you to review and search for reportbacks.
                </p>

                <p>
                    Please contact @jen for Rogue access.
                </p>

                <p>
                    <a href="/login" class="button">Log In</a>
                </p>
        </div>
    </div>

@stop
