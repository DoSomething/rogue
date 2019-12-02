@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Campaign Creation'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <h1>Create Campaign ID</h1>
                <p>Please reach out in the #dev-rogue channel for help creating a campaign ID for your campaign.</p>
                <br>
                <form method="post" enctype="application/x-www-form-urlencoded" action="{{ route('campaigns.store') }}">
                {{ csrf_field()}}

                    <div class="form-item">
                        <label class="field-label">Internal Campaign Name</label>
                        <input type="text" name="internal_title" class="text-field" placeholder="Campaign Name YYYY-MM Start Date
 e.g. Teens for Jeans 2015-08" value="{{old('internal_title') }}">
                    </div>

                    <div class="form-item">
                        <label class="field-label">Cause Area <em>(choose between 1-5)</em></label>
                        <div class="columns-2">
                            @foreach($causes as $cause => $name)
                                <label class="option -checkbox">
                                    <input {{ old('cause.' . $cause) }} name="cause[{{ $cause }}]" type="checkbox" value="{{ $cause }}">
                                    <span class="option__indicator"></span>
                                    <span>{{ $name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-item">
                        <label class="field-label">Proof of Impact</label>
                        <input type="text" name="impact_doc" class="text-field" placeholder="Link to Proof of Impact doc, including https://" value="{{ old('impact_doc') }}">
                    </div>
                    <div class="form-item -half">
                        <label class="field-label">Start Date</label>
                        <input type="text" name="start_date" class="text-field" placeholder="MM/DD/YYYY" value="{{ old('start_date') }}">
                    </div>
                    <div class="form-item -half">
                        <label class="field-label">End Date</label>
                        <input type="text" name="end_date" class="text-field" placeholder="MM/DD/YYYY or blank" value="{{ old('end_date') }}">
                    </div>

                    <p>Each action in your campaign will require a different set of metadata, to determine how it will be treated by Rogue. After creating your campaign, you'll be taken to a Campaign ID page that will allow you to create your actions.</p>

                    <ul class="form-actions -inline -padded">
                        <li><input type="submit" class="button" value="Create Campaign"></li>
                    </ul>
                </form>
            </div>
        </div>
    </div>

@stop
