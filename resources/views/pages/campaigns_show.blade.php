@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Campaign IDs', 'subtitle' => 'Create & manage campaign IDs.'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <h3>Internal Campaign Name</h3>
                <p>{{ $campaign->internal_title }}</p>

                <h3>Start Date</h3>
                <p>{{ $campaign->start_date }}</p>

                <h3>End Date</h3>
                <p>{{ $campaign->end_date }}</p>
            </div>
            <div class="container__block -narrow">
                <a class="secondary" href="rogue.dosomething.org">Edit this campaign</a>
                <p class="footnote">
                    Last updated: {{ $campaign->updated_at->format('F d, Y g:ia') }}<br />
                    Created: {{ $campaign->created_at->format('F d, Y g:ia') }}
                </p>
            </div>
        </div>
    </div>

@stop
