@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Edit an Action'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <h1>Edit {{ $action->name }}</h1>

                <form method="post" enctype="application/x-www-form-urlencoded" action="{{ route('actions.update', $action->id) }}">
                {{ csrf_field()}}
                <input name="_method" type="hidden" value="PATCH">

                    <div class="form-item">
                        <label class="field-label">Action Name</label>
                        <input type="text" name="name" class="text-field" value="{{ $action->name }}">
                    </div>

                    <div class="select form-item">
                        <label class="field-label">Post Type</label>
                        <select name="post_type">
                            <option value="">Select the post type</option>
                            @foreach($postTypes as $postType)
                                    <option value="{{ $postType }}" {{ ($action->post_type == $postType ? "selected":"") }}>{{ $postType }}</option>

                            @endforeach
                        </select>
                    </div>
                    <div class="form-item -third">
                        <label class="field-label">CallPower Campaign ID</label>
                        <input type="text" name="callpower_campaign_id" class="text-field" placeholder="e.g. 4 (optional)" value="{{ $action->callpower_campaign_id }}">
                    </div>
                    <div class="form-item -third">
                        <label class="field-label">Action Noun</label>
                        <input type="text" name="noun" class="text-field" placeholder="e.g. Jeans" value="{{ $action->noun }}">
                    </div>
                    <div class="form-item -third">
                        <label class="field-label">Action Verb</label>
                        <input type="text" name="verb" class="text-field" placeholder="e.g. Collected" value="{{ $action->verb }}">
                    </div>
                    <div class="form-item">
                        <label class="field-label">Post Details</label>
                        <label class="option -checkbox">
                          <input type="checkbox" id=reportback name=reportback value=1>
                          <span class="option__indicator"></span>
                          <span>Reportback</span>
                        </label>
                        <label class="option -checkbox">
                          <input type="checkbox" id=civic_action name=civic_action value=1>
                          <span class="option__indicator"></span>
                          <span>Civic action</span>
                        </label>
                        <label class="option -checkbox">
                          <input type="checkbox" id=scholarship_entry name=scholarship_entry value=1>
                          <span class="option__indicator"></span>
                          <span>Scholarship entry</span>
                        </label>
                        <label class="option -checkbox">
                          <input type="checkbox" id=anonymous name=anonymous value=1>
                          <span class="option__indicator"></span>
                          <span>Anonymous Post</span>
                        </label>
                    </div>
                    <ul class="form-actions -inline -padded">
                        <li><input type="submit" class="button" value="Update Action"></li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
@stop
