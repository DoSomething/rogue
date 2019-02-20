@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Action Creation'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <h1>Add Post Metadata</h1>
                <form method="post" enctype="application/x-www-form-urlencoded" action="{{ route('actions.store', ['campaign_id' => $campaignId]) }}">
                {{ csrf_field()}}

                    <div class="form-item">
                        <label class="field-label">Action Name</label>
                        <input type="text" name="name" class="text-field" placeholder="Name your action e.g. Teens for Jeans Photo Upload" value="{{old('internal_title') }}">
                    </div>

                    <div class="select form-item">
                        <label class="field-label">Post Type</label>
                        <select name="post_type">
                            <option value="" disabled selected>Select the post type</option>
                            @foreach($postTypes as $postType)
                                @if (old('postType'))
                                    <option value="{{ old('postType') }}" {{ (old('postType') == $postType ? "selected":"") }}>{{ $postType }}</option>
                                @else
                                    <option>{{ $postType }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-item -half">
                        <label class="field-label">Action Noun</label>
                        <input type="text" name="noun" class="text-field" placeholder="e.g. Jeans" value="{{ old('noun') }}">
                    </div>
                    <div class="form-item -half">
                        <label class="field-label">Action Verb</label>
                        <input type="text" name="verb" class="text-field" placeholder="e.g. Collected" value="{{ old('verb') }}">
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
                        <li><input type="submit" class="button" value="Create Action"></li>
                    </ul>
                </form>
            </div>
        </div>
    </div>

@stop
