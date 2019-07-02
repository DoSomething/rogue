@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Rogue', 'subtitle' => 'Please log in with your DoSomething.org admin account to continue.'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <p>
                    Welcome to <strong>Rogue</strong>, our one source of truth for all things user activity. Logging in will allow you to review and search for reportbacks.
                </p>
                <br>
                <a href="/login" class="button">Log In</a>
                <br>
                <div class="container__block">
                    <p>Unauthorized? Please contact #team-product in Slack for admin access.</p>
                </div>
            </div>
        </div>
    </div>

@stop
