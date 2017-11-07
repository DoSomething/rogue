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


                <h4>We're Team Bleed - your friends behind building and improving Rogue!</h4>
                <ul class="list">
                    <li>Jen - Product Manager</li>
                    <li>Shae - Tech Lead</li>
                    <li>Katie - Engineer</li>
                    <li>Chloe - Engineer</li>
                    <li>Luke - Product Desginer</li>
                    <li>Dave - Staff Engineer</li>
                    <li>Tongue Cat - Rogue mascot</li>
                </ul>

                <p>
                    Please contact @jen for Rogue access.
                </p>
                <p>
                    <a href="/login" class="button">Log In</a>
                </p>
        </div>
    </div>

@stop
