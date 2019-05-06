@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Edit an Action'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <h1>Edit {{ $action->name }}</h1>

                <form method="POST" action="{{ route('actions.update', $action->id) }}">
                    {{ csrf_field()}}
                    {{ method_field('PATCH') }}

                    <div class="form-item">
                        <label class="field-label">Action Name</label>
                        @include('forms.text', ['name' => 'name', 'placeholder' => 'Name your action e.g. Teens for Jeans Photo Upload', 'value' => $action->name])
                    </div>

                    <div class="form-item">
                        <label class="field-label">Post Type</label>
                        @include('forms.select', ['name' => 'post_type', 'options' => $postTypes, 'value' => $action->post_type, 'placeholder' => 'What type of post is this?'])
                    </div>

                    <div class="form-item -third">
                        <label class="field-label">CallPower Campaign ID</label>
                        @include('forms.text', ['name' => 'callpower_campaign_id', 'placeholder' => 'e.g. 4 (optional)', 'value' => $action->callpower_campaign_id])
                    </div>

                    <div class="form-item -third">
                        <label class="field-label">Action Noun</label>
                        @include('forms.text', ['name' => 'noun', 'placeholder' => 'e.g. Jeans', 'value' => $action->noun])
                    </div>

                    <div class="form-item -third">
                        <label class="field-label">Action Verb</label>
                        @include('forms.text', ['name' => 'verb', 'placeholder' => 'e.g. Collected', 'value' => $action->verb])
                    </div>

                    <div class="form-item">
                        <label class="field-label">Action Details</label>
                        @include('forms.option', ['name' => 'reportback', 'label' => 'Reportback', 'value' => $action->reportback])
                        @include('forms.option', ['name' => 'civic_action', 'label' => 'Civic Action', 'value' => $action->civic_action])
                        @include('forms.option', ['name' => 'scholarship_entry', 'label' => 'Scholarship Entry', 'value' => $action->scholarship_entry])
                        @include('forms.option', ['name' => 'anonymous', 'label' => 'Anonymous', 'value' => $action->anonymous])
                    </div>

                    <ul class="form-actions -inline -padded">
                        <li><input type="submit" class="button" value="Update Action"></li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
@stop
