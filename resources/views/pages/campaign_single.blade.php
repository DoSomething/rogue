@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => $state['campaign']['title']])

        <div class="container -padded">
            <div id="singleCampaignContainer" class="wrapper">
                <div id="campaignStatusCounter" class="status-counts">
                    <ul>
                        <li>
                            <span class="count">456</span>
                            <span class="status">Pending</span>
                            <a class="button -secondary">Review</a>
                        </li>
                        <li>
                            <span class="status">Approved</span>
                            <span class="count">100</span>
                        </li>
                        <li>
                            <span class="status">Rejected</span>
                            <span class="count">24</span>
                        </li>
                    </ul>
                </div>
                <div id="postContainer">
                    Loading...
                </div>
            </div>
        </div>

@stop
