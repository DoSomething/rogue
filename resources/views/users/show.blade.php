@extends('layouts.master')

@section('main_content')
    @include('layouts.header', ['header' => 'User', 'subtitle' => ''])

    <div class="container -padded">
        <div class="wrapper" data-container="UserOverview">
            Loading...
        </div>
    </div>
@stop
