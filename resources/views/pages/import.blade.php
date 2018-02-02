@extends('layouts.master')

@section('main_content')

    @include('layouts.header', ['header' => 'Upload a CSV for import'])

    <div class="container -padded">
        <div class="wrapper">
            <div class="container__block -narrow">
                <article class="figure margin-bottom-none -left -small">
                    <div class="figure__media rounded-figure">
                        <img src={{asset('images/Jen.png')}}>
                    </div>
                    <div class="figure__body">
                        <strong>Attention:</strong>
                        <br>
                        <p class="footnote">For the time being, this feature should only be used by Jen Ng. If you have any questions please reach out to Jen directly or post a message in the #rogue channel in slack.</p>
                    </div>
                </article>
            </div>
            <div class="container__block -narrow">
                <form action={{url('/import')}} method="post" enctype="multipart/form-data">
                    {{ csrf_field()}}
                    <div class="form-item">
                        <label for="upload-file" class="field-label">Upload</label>
                        <input type="file" name="upload-file" class="form-control">
                    </div>

                    <label class="field-label">Type of Import</label>
                    <div class="form-item">
                        <label class="option -checkbox">
                            <input checked type="checkbox" id="importType" value="turbovote" name="importType">
                            <span class="option__indicator"></span>
                            <span>Turbovote Import</span>
                        </label>
                    </div>
                    <div class="form-actions -padded">
                        <input type="submit" class="button" value="Submit CSV">
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop
