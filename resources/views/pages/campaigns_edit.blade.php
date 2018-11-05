@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Campaign IDs', 'subtitle' => 'Create & manage campaign IDs.'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <h1>Edit {{ $campaign->internal_title }}</h1>
                <br>
                <form method="post" enctype="application/x-www-form-urlencoded" action="{{ route('campaign_id.update', $campaign) }}">
                {{ csrf_field()}}
                <input name="_method" type="hidden" value="PATCH">

                    <div class="form-item">
                        <label class="field-label">Internal Campaign Name</label>
                        <input type="text" name="internal_title" class="text-field" value="{{ $campaign->internal_title }}">
                    </div>

                    <div class="form-item">
                        <label class="field-label">Start Date</label>
                        <input type="text" name="start_date" class="text-field" value="{{ $campaign->start_date }}">
                    </div>

                    <div class="form-item">
                        <label class="field-label">End Date</label>
                        <input type="text" name="end_date" class="text-field" placeholder="e.g. 10/16/2018" value="{{ $campaign->end_date }}">
                    </div>

                    <ul class="form-actions -inline -padded">
                        <li><input type="submit" class="button" value="Submit"></li>
                    </ul>
                </form>
            </div>
        </div>
    </div>

@stop
