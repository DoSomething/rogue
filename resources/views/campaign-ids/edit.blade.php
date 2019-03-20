@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Campaign IDs', 'subtitle' => 'Create & manage campaign IDs.'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <h1>Edit {{ $campaign->internal_title }}</h1>
                <br>
                <form method="post" enctype="application/x-www-form-urlencoded" action="{{ route('campaign-ids.update', $campaign) }}">
                {{ csrf_field()}}
                <input name="_method" type="hidden" value="PATCH">

                    <div class="form-item">
                        <label class="field-label">Internal Campaign Name</label>
                        <input type="text" name="internal_title" class="text-field"
                            @if (old('internal_title'))
                                value="{{ old('internal_title') }}"
                            @else
                                value="{{ $campaign->internal_title }}"
                            @endif
                        >
                    </div>

                    <div class="select form-item">
                        <label class="field-label">Cause</label>
                        <select name="cause">
                            @foreach($causes as $cause)
                                @if (old('cause'))
                                    <option value="{{ $cause }}" {{ (old('cause') == $cause ? "selected":"") }}>{{ $cause }}</option>
                                @else
                                    <option value="{{ $cause }}" {{ ($campaign->cause == $cause ? "selected":"") }}>{{ $cause }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-item">
                        <label class="field-label">Proof of Impact</label>
                        <input type="text" name="impact_doc" class="text-field"
                            @if (old('impact_doc'))
                                value="{{ old('impact_doc') }}"
                            @else
                                value="{{ $campaign->impact_doc }}"
                            @endif
                        >
                    </div>

                    <div class="form-item">
                        <label class="field-label">Start Date</label>
                        <input type="text" name="start_date" class="text-field"
                            @if (old('start_date'))
                                value="{{ old('start_date') ? old('start_date')->format('m/d/Y') : null }}"
                            @else
                                value="{{ $campaign->start_date->format('m/d/Y') }}"
                            @endif
                        >
                    </div>

                    <div class="form-item">
                        <label class="field-label">End Date</label>
                        <input type="text" name="end_date" class="text-field" placeholder="e.g. 10/16/2018 or you can leave this blank if there's no end date"
                            @if (old('end_date'))
                                value="{{ old('end_date') ? old('end_date')->format('m/d/Y') : null }}"
                            @else
                                value="{{ $campaign->end_date ? $campaign->end_date->format('m/d/Y') : null }}"
                            @endif
                        >
                    </div>

                    <ul class="form-actions -inline -padded">
                        <li><input type="submit" class="button" value="Submit"></li>
                    </ul>
                </form>
            </div>
        </div>
    </div>

@stop
