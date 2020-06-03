@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'New Group', 'subtitle' => 'Group Type #' . $groupTypeId])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <form method="POST" action="{{ route('groups.store', ['group_type_id' => $groupTypeId]) }}">
                    {{ csrf_field()}}

                    <div class="form-item">
                        <label class="field-label">Name</label>
                        @include('forms.text', ['name' => 'name', 'placeholder' => 'NYC Chapter'])
                    </div>

                    <ul class="form-actions -inline -padded">
                        <li><input type="submit" class="button" value="Create Group"></li>
                    </ul>
                </form>
            </div>
        </div>
    </div>

@stop
