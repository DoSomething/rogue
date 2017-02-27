@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Lemonade'])

    <div>
        <div class="container -padded">
            <div id="app" class="wrapper">
                <div class="container__block -narrow">
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </div>
@stop
