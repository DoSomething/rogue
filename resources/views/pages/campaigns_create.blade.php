@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Campaign IDs', 'subtitle' => 'Create & manage campaign IDs.'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <div class="gallery__heading">
                    <h1>Create Campaign ID</h1>
                </div>
                <p>Please reach out in the #dev-rogue channel for help creating a campagin ID for your campaign.</p>
            </div>
        </div>
    </div>

@stop
