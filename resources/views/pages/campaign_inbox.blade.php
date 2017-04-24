@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => $state['campaign']['title']])

	    <div class="container -padded">
	        <div id="inboxContainer" class="wrapper">
	            Loading...
	        </div>
	    </div>

@stop
