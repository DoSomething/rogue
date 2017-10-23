<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Rogue</title>

        <link rel="icon" type="image/png" href="http://twooter.biz/Gifs/tonguecat.png">
        <link rel="stylesheet" href="{{ elixir('app.css', 'dist') }}">
    </head>

    <body>

        @if (Session::has('status'))
            <div class="messages">{{ Session::get('status') }}</div>
        @endif

        <div class="chrome">
            <div class="wrapper">

                @include('layouts.nav')

                <div class="container">

                    @yield('main_content')

                </div>
            </div>
        </div>

        {{ isset($state) ? scriptify($state) : scriptify() }}
        <script src="{{ elixir('app.js', 'dist') }}"></script>
    </body>

</html>
