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
                <br>
                <a href="/login" class="button">Log In</a>
                <br>
                <div class="container__block -narrow">
                    <h4>We're Team Bleed - your friends behind building and improving Rogue!</h4>
                </div>
                <div class="container__block -half">
                    <article class="figure margin-bottom-none -left -small">
                        <div class="figure__media rounded-figure">
                            <img src={{asset('images/Jen.png')}}>
                        </div>
                        <div class="figure__body">
                            <strong>Jen</strong>
                            <br>
                            <p class="footnote">Product Manager</p>
                        </div>
                    </article>
                    <br>
                    <article class="figure margin-bottom-none -left -small">
                        <div class="figure__media rounded-figure">
                            <img src={{asset('images/Shae.png')}}>
                        </div>
                        <div class="figure__body">
                            <strong>Shae</strong>
                            <br>
                            <p class="footnote">Tech Lead</p>
                        </div>
                    </article>
                    <br>
                    <article class="figure margin-bottom-none -left -small">
                        <div class="figure__media rounded-figure">
                            <img src={{asset('images/Katie.png')}}>
                        </div>
                        <div class="figure__body">
                            <strong>Katie</strong>
                            <br>
                            <p class="footnote">Engineer</p>
                        </div>
                    </article>
                    <br>
                    <article class="figure margin-bottom-none -left -small">
                        <div class="figure__media rounded-figure">
                            <img src={{asset('images/Chloe.png')}}>
                        </div>
                        <div class="figure__body">
                            <strong>Chloe</strong>
                            <br>
                            <p class="footnote">Engineer</p>
                        </div>
                    </article>
                </div>
                <div class="container__block -half">
                    <article class="figure margin-bottom-none -left -small">
                        <div class="figure__media rounded-figure">
                            <img src={{asset('images/Luke.png')}}>
                        </div>
                        <div class="figure__body">
                            <strong>Luke</strong>
                            <br>
                            <p class="footnote">Product Designer</p>
                        </div>
                    </article>
                    <br>
                    <article class="figure margin-bottom-none -left -small">
                        <div class="figure__media rounded-figure">
                            <img src={{asset('images/Dave.png')}}>
                        </div>
                        <div class="figure__body">
                            <strong>Dave</strong>
                            <br>
                            <p class="footnote">Staff Engineer</p>
                        </div>
                    </article>
                    <br>
                    <article class="figure margin-bottom-none -left -small">
                        <div class="figure__media rounded-figure">
                            <img src={{asset('images/TongueCat.png')}}>
                        </div>
                        <div class="figure__body">
                            <strong>Tongue Cat</strong>
                            <br>
                            <p class="footnote">Rogue Mascot</p>
                        </div>
                    </article>
                </div>
                <div class="container__block -narrow">
                    <p>Please contact @jen (in Slack) for Rogue access.</p>
                </div>
            </div>
        </div>
    </div>

@stop
