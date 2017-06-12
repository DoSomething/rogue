@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => $state['campaign']['title']])

        <div class='container'>
            <div class='wrapper'>
                <a href={{ '/campaigns/' . $state['campaign']['id'] }} class='button -tertiary'>Campaign View</a>
            </div>
        </div>
	    <div class='container -padded'>
	        <div id='inboxContainer' class='wrapper'>
	            Loading...
	        </div>
	    </div>

@stop
