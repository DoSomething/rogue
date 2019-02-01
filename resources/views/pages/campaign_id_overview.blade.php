@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Campaign IDs', 'subtitle' => 'Create & manage campaign IDs.'])

    <div class="container -padded">
        <div class="wrapper" data-container="CampaignIdOverview">
            Loading...
        </div>
    </div>

@stop
