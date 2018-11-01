@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Campaign IDs', 'subtitle' => 'Create & manage campaign IDs.'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <div class="gallery__heading">
                    <h1>All Campaign IDs</h1>
                </div>
                <p>These are all the campaign IDs assocaited with their campaign name, start date, and if applicable, end date.</p>
            </div>
            <ul class="gallery -duo">
                <div class="container__block -narrow">
                    <a class="button -secondary" href="{{ route('campaigns.create') }}">New Campaign ID</a>
                 </div>
                <div class="container__block">
                    <table class="table">
                      <thead>
                          <tr class="row table-header">
                              <th class="table-cell">Internal Name</th>
                              <th class="table-cell">Campaign ID</th>
                              <th class="table-cell">Start Date</th>
                              <th class="table-cell">End Date</th>
                          </tr>
                      </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>

@stop
