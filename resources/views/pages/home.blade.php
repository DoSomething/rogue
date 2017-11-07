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
                <div class="container__block -half">
                    <ul class>
                        <li>
                            <img src={{asset('images/Jen.png')}}>
                            <p>Jen - Product Manager</p>
                        </li>
                        <li>
                            <img src={{asset('images/Shae.png')}}>
                            <p>Shae - Tech Lead</p>
                        </li>
                        <li>
                            <img src={{asset('images/Katie.png')}}>
                            <p>Katie - Engineer</p>
                        </li>
                        <li>
                            <img src={{asset('images/Chloe.png')}}>
                            <p>Chloe - Engineer</p>
                        </li>
                    </ul>
                    <p>Please contact @jen (in Slack) for Rogue access.</p>

                    <a href="/login" class="button">Log In</a>
                </div>
                <div class="container__block -half">
                    <ul class>
                        <li>
                            <img src={{asset('images/Luke.png')}}>
                            <p>Luke - Product Desginer</p>
                        </li>
                        <li>
                            <img src={{asset('images/Dave.png')}}>
                            <p>Dave - Staff Engineer</p>
                        </li>
                        <li>
                            <img src={{asset('images/TongueCat.png')}}>
                            <p>Tongue Cat - Rogue Mascot</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@stop
