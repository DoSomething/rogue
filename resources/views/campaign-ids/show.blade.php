@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Campaign IDs', 'subtitle' => 'Create & manage campaign IDs.'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <h3>Internal Campaign Name</h3>
                <p>{{ $campaign->internal_title }}</p>

                <h3>Campaign ID</h3>
                <p>{{ $campaign->id }}</p>

                <h3>Cause</h3>
                <p>{{ $campaign->cause or '–' }}</p>

                <h3>Proof of Impact</h3>
                @if($campaign->impact_doc)
                    <p><a href="{{ $campaign->impact_doc }}" target="_blank">{{ $campaign->impact_doc }}</a></p>
                @else
                    <p>–</p>
                @endif

                <h3>Start Date</h3>
                <p>{{ $campaign->start_date->format('m/d/Y') }}</p>

                <h3>End Date</h3>
                <p>{{ $campaign->end_date ? $campaign->end_date->format('m/d/Y') : '–' }}</p>
            </div>
            <div class="container__block -narrow">
                <a class="secondary" href="{{ route('campaign-ids.edit', [ $campaign ]) }}">Edit this campaign</a>
                <p class="footnote">
                    Last updated: {{ $campaign->updated_at->setTimezone('EST')->format('F d, Y g:ia') }}<br />
                    Created: {{ $campaign->created_at->setTimezone('EST')->format('F d, Y g:ia') }}
                </p>
            </div>
        </div>
    </div>

@stop
