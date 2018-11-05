@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Campaign IDs', 'subtitle' => 'Create & manage campaign IDs.'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <h1>All Campaign IDs</h1>
                <p>These are all the campaign IDs assocaited with their campaign name, start date, and if applicable, end date.</p>
            </div>
            <ul class="gallery -duo">
                <div class="container__block -narrow">
                    <a class="button -secondary" href="{{ route('campaign_id.create') }}">New Campaign ID</a>
                 </div>
                <div class="container__block">
                    <table class="table">
                        <thead>
                            <tr class="table__header">
                              <th class="table__cell">Internal Name</th>
                              <th class="table__cell">Campaign ID</th>
                              <th class="table__cell">Start Date</th>
                              <th class="table__cell">End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($campaigns as $campaign)
                                <tr class="table__row">
                                    <td class="table__cell"><a href="{{ route('campaign_id.show', [$campaign->id]) }}">{{ $campaign->internal_title}}</a></td>
                                    <td class="table__cell">{{ $campaign->id }}</td>
                                    <td class="table__cell">{{ $campaign->start_date }}</td>
                                    <td class="table__cell">{{ $campaign->end_date }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop
