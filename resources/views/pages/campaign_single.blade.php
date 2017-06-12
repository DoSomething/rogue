@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => $state['campaign']['title']])

        <div class="container -padded">
            <div id="singleCampaignContainer" class="wrapper">
                <div id="status-counter"></div>
                <div id="status-filter"></div>
                <div id="posts">
                    Loading...
                </div>
            </div>
        </div>

@stop
