@extends('layouts.master')

@section('main_content')

    @include('layouts.header',[
        'header' => 'Users'
    ])

    <div class="container">
        <div class="wrapper">
            <div class="container__block">
                <h1 class="heading">{{ $user->first_name }}</h1>

                <div class="key-value">
                    <dt>Email:</dt>
                    <dd>{{ $user->email }}</dd>
                    <dt>ID:</dt>
                    <dd>{{ $user->id }}</dd>
                </div>
            </div>
        </div>
    </div>
@stop
