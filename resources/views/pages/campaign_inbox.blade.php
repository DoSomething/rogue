@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => $state['title']])

	    <div class="container -padded">
	        <div id="inboxContainer" class="wrapper">
	            Loading...
	        </div>
	    </div>

@stop
