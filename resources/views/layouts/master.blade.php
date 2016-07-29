<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Rogue</title>

        <link rel="icon" type="image/ico" href="/favicon.ico?v1">

        <link rel="stylesheet" href="{{ asset('dist/app.css') }}">
        <script src="{{ asset('dist/modernizr.js') }}"></script>
    </head>

    <body>
        <div class="chrome">
            <div class="wrapper">
                <div class="container">

                    @yield('main_content')

                </div>
            </div>
        </div>

        <script src="{{ asset('/dist/app.js') }}"></script>
    </body>

</html>
