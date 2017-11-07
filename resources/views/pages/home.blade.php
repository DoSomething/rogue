@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Rogue', 'subtitle' => 'Please log in with your DoSomething.org admin account to continue.
'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block">
                <p>
                    Welcome to <strong>Rogue</strong>, our one source of truth for all things user activity. Logging in will allow you to review and search for reportbacks.
                </p>

                <h4>We're Team Bleed - your friends behind building and improving Rogue!</h4>
                <div class="container__block -half">
                    <ul class>
                        <img src={{asset('images/Jen.png')}}>
                        <li>Jen - Product Manager</li>
                        <img src={{asset('images/Shae.png')}}>
                        <li>Shae - Tech Lead</li>
                        <img src={{asset('images/Katie.png')}}>
                        <li>Katie - Engineer</li>
                        <img src={{asset('images/Chloe.png')}}>
                        <li>Chloe - Engineer</li>
                    </ul>
                    <p>Please contact @jen for Rogue access.</p>

                    <a href="/login" class="button">Log In</a>
                </div>
                <div class="container__block -half">
                    <ul class>
                        <img src={{asset('images/Luke.png')}}>
                        <li>Luke - Product Desginer</li>
                        <img src={{asset('images/Dave.png')}}>
                        <li>Dave - Staff Engineer</li>
                        <img src={{asset('images/TongueCat.png')}}>
                        <li>Tongue Cat - Rogue Mascot</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@stop
