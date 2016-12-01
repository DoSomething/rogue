@extends('layouts.master')

@section('main_content')

    @include('layouts.header',[
        'header' => 'Users'
    ])


    @if ($users->count())
        @include('users.partials._table_users', ['users' => $users, 'role' => 'Admins'])
    @endif

@stop
