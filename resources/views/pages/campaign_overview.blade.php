@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Campaign Overview', 'subtitle' => ''])

    <div class="container -padded">
        <div class="wrapper" data-container="CampaignOverview">
            Loading...
        </div>
    </div>

@stop
