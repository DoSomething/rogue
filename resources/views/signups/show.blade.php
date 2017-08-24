@extends('layouts.master')

@section('main_content')
    @include('layouts.header', ['header' => $campaign['title'], 'subtitle' => ''])

    <div class="container -padded">
        <div id="signupContainer" class="wrapper">
            Loading...
        </div>
    </div>
@stop
