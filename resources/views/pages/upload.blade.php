@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Upload a CSV for import'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <form action={{url('/import')}} method="post" enctype="multipart/form-data">
                    {{ csrf_field()}}
                    <div class="form-item">
                        <label for="upload-file" class="field-label">Upload</label>
                        <input type="file" name="upload-file" class="form-control">
                    </div>
                    <div class="form-actions -padded">
                        <input type="submit" class="button" value="Submit CSV">
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop
