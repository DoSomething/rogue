<!DOCTYPE html>

<html lang="en" class="modernizr-label-click modernizr-checked">

    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Rogue</title>

        <link rel="icon" type="image/png" href="http://twooter.biz/Gifs/tonguecat.png">
        <link rel="stylesheet" href="{{ elixir('app.css', 'dist') }}">
    </head>

    <body>
        <div class="chrome">
            <div class="wrapper">

                @include('layouts.nav')

                <div id="app">
                    <header class="header" role="banner">
                        <div class="wrapper">
                            <h1 class="header__title">&nbsp;</h1>
                            <p class="header__subtitle">&nbsp;</p>
                        </div>
                    </header>

                    <div class="flex-center-xy placeholder">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ elixir('app.js', 'dist') }}"></script>
    </body>

    {{ scriptify($auth, 'AUTH') }}
    {{ scriptify(['GRAPHQL_URL' => config('services.graphql.url')], 'ENV') }}
</html>
