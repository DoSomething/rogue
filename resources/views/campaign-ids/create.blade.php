@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Campaign IDs', 'subtitle' => 'Create & manage campaign IDs.'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <h1>Create Campaign ID</h1>
                <p>Please reach out in the #dev-rogue channel for help creating a campagin ID for your campaign.</p>
                <br>
                <form method="post" enctype="application/x-www-form-urlencoded" action="{{ action('Legacy\Web\CampaignsController@store') }}">
                {{ csrf_field()}}

                    <div class="form-item">
                        <label class="field-label">Internal Campaign Name</label>
                        <input type="text" name="internal_title" class="text-field" placeholder="e.g. Teens for Jeans 2018" value="{{old('internal_title') }}">
                    </div>

                    <div class="select form-item">
                        <label class="field-label">Cause</label>
                        <select name="cause">
                            <option value="" disabled selected>Select the cause</option>
                            @foreach($causes as $cause)
                                @if (old('cause'))
                                    <option value="{{ old('cause') }}" {{ (old('cause') == $cause ? "selected":"") }}>{{ $cause }}</option>
                                @else
                                    <option>{{ $cause }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-item">
                        <label class="field-label">Start Date</label>
                        <input type="text" name="start_date" class="text-field" placeholder="e.g. 6/6/2018" value="{{ old('start_date') }}">
                    </div>

                    <div class="form-item">
                        <label class="field-label">End Date</label>
                        <input type="text" name="end_date" class="text-field" placeholder="e.g. 10/15/2018 or you can leave this blank if there's no end date" value="{{ old('end_date') }}">
                    </div>

                    <ul class="form-actions -inline -padded">
                        <li><input type="submit" class="button" value="Submit"></li>
                    </ul>
                </form>
            </div>
        </div>
    </div>

@stop
