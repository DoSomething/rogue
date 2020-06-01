@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Edit Group Type'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <h1>
                    <a href="/group-types/{{ $groupType->id }}">{{ $groupType->name }}</a>
                </h1>
                <form method="POST" action="{{ route('group-types.update', $groupType->id) }}">
                    {{ csrf_field()}}
                    {{ method_field('PATCH') }}
                    <div class="form-item">
                        <label class="field-label">Name</label>
                        @include('forms.text', ['name' => 'name', 'placeholder' => 'e.g. March For Our Lives, DoSomething Clubs', 'value' => $groupType->name])
                    </div>
                    <ul class="form-actions -inline -padded">
                        <li><input type="submit" class="button" value="Update"></li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
@stop