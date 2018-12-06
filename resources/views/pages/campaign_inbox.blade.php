@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => $state['campaign']['internal_title']])

        <div class='container'>
            <div class='wrapper'>
                <a href={{ '/campaigns/' . $state['campaign']['id'] }} class='button -tertiary'>Campaign View</a>
            </div>
        </div>
	    <div class='container -padded'>
	        <div class='wrapper' data-container="CampaignInbox" data-reviewing="true">
	            Loading...
	        </div>
	    </div>

@stop
