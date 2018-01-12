@extends('layouts.app')

@section('pageTitle', $entity->name." | ".trans('words.download_listen'))
@section('pageDescription', trans('words.home_description'))

@section('content')
    @include('partials.functions')
    <div class="container page_content">
        <div class="row">
            <div class="col-lg-8 track_list">
                <h1 style="  text-align: center;  padding: 0 30px;">{{$entity->name}} - @lang('words.download_listen')</h1>
                <ul class="songs">
                    @forelse($songs as $song)
                        @include('song.one',['song'=>$song,'loop'=>$loop])
                    @empty
                        <li>@lang('words.no_results')</li>
                    @endforelse
                </ul>
                {{$songs->links()}}

            </div>
            @include('partials.tags',['tags'=> $hot_tags])
        </div>
        @include("partials.footer")
    </div>

@endsection