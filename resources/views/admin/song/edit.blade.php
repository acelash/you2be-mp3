{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends('admin.layouts.admin')

@section('pageTitle', 'Song ID:' . $entry->id)


@section('headerScripts', '')

@section('footerScripts', '')


@section('content')

    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Editing song ID: {{$entry->id}} [{{$entry->state}}]</h3>
            </div>

        </div>

        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_content">
                        @if (session('message'))
                            <div class=" alert
                            @if(session('success') == true) alert-success
                              @elseif(session('success') == false) alert-error
                            @endif">
                                <ul>
                                    <li>{{ session('message') }}</li>
                                </ul>
                            </div>
                        @endif


                        @if (count($errors) > 0)
                            <div class="alert-error">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="edit_movie" class="form-horizontal form-label-left edit_form"
                              method="POST"
                              action="{{ url('/admin/songs/'.$entry->id) }}">
                            {{ csrf_field() }}


                            <div class="col-md-12">
                                <label class="control-label col-md-1" for="title">Title <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-11">
                                    <input type="text" id="title" name="title" required="required"
                                           value="{{ $entry->title }}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="control-label col-md-1" for="title_original">Title original</label>
                                <div class="col-md-11">
                                    <input type="text" id="title_original" name="title_original"
                                           value="{{ $entry->title_original }}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="control-label col-md-2" for="year">Year </label>
                                <div class="col-md-10">
                                    <input type="number" step="1" min="1900" max="{{date("Y",time())}}" id="year" name="year"   value="{{ $entry->year }}" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="control-label col-md-5" for="source_start_at">Start at</label>
                                <div class="col-md-7">
                                    <input type="number" step="1" min="0" id="source_start_at" name="source_start_at"
                                           value="{{ $entry->source_start_at }}"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label col-md-5" for="source_id">Source ID</label>
                                <div class="col-md-7">
                                    <input readonly="readonly" type="text" id="source_id" name="source_id"
                                           value="{{ $entry->source_id }}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="control-label col-md-2" for="title">Genres <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <?php $genreSelected = $entry->genres()->get()->pluck('id')->toArray(); ?>
                                    <select multiple="multiple" id="genres" name="genre_list[]">
                                        @foreach($genres as $genre)
                                            <option @if(in_array($genre->id,$genreSelected)) selected
                                                    @endif value="{{$genre->id}}">{{$genre->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="control-label col-md-2" for="country_list">Countries <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <?php $countriesSelected = $entry->countries()->get()->pluck('id')->toArray();  ?>
                                    <select multiple="multiple" id="countries" name="country_list[]">
                                        @foreach($countries as $country)
                                            <option @if(in_array($country->id,$countriesSelected)) selected
                                                    @endif value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="control-label col-md-1" for="text">Text
                                </label>
                                <div class="col-md-11">
                                    <textarea id="text" name="text" rows="7"
                                              class="form-control">{{$entry->text }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12 ln_solid"></div>

                            @if($entry->state_id == config('constants.STATE_UNCHECKED'))
                                <div class="col-md-12">
                                    <button type="submit" name="save_mode" value="check_and_next" class="btn btn-primary">Check & open next Unchecked</button>
                                    <button type="submit" name="save_mode" value="skip_and_next" class="btn btn-warning">Skip & open next Unchecked</button>
                                    <a onclick="return confirm('This can be undone. Are you sure ?')" href="{{url('admin/movies/delete/'.$entry->id.'?next_unckecked=1&no_return=1')}}" class="btn btn-danger">Remove & open next Unchecked</a>
                                </div>
                                <div class="col-md-12 ln_solid"></div>
                            @endif

                            <div class="col-md-12">
                                <button type="submit" name="save_mode" value="save" class="btn btn-success">@lang("translate.save_changes")</button>
                                <a onclick="return confirm('This can be undone. Are you sure ?')" href="{{url('admin/movies/delete/'.$entry->id.'?no_return=1')}}" class="btn btn-danger">@lang("translate.remove")</a>
                            </div>

                        </form>
                        <div class="embeded_video_sample" style="text-align: center;width: 100%;">
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/{{$entry->source_id}}?rel=0&amp;showinfo=0&amp;iv_load_policy=3&amp;t={{$entry->source_start_at}}s" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{url('')}}/public/admin/vendors/tinymce_4.6.4/js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#text',
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
        });
        document.addEventListener('DOMContentLoaded', function () {
            $('#genres').multiSelect();
            $('#countries').multiSelect();
        });

    </script>
@endsection
