@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Campaign Inbox', 'subtitle' => 'all pending reportbacks for the campaign'])

	    <div class="container -padded">
	        <div id="inboxContainer" class="wrapper">
	            Loading...
	        </div>
	    </div>

@stop
