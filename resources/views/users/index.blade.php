@extends('layouts.master')

@section('main_content')

    @include('layouts.header',[
        'header' => 'Users'
    ])

    <div class="container">
        <div class="wrapper">
            <div class="container__block">
                @include('search.search')
            </div>
        </div>
    </div>

    @if ($users->count())
        @include('users.partials._table_users', ['users' => $users, 'role' => 'Admins'])
    @endif

@stop
