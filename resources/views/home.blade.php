@extends('layouts.app')

@section('pageTitle', trans('words.home_title')." | mp3cloud.su")
@section('pageDescription', trans('words.home_description'))

@section('content')
    @include('partials.functions')
    <div class="container page_content">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 track_list">
                <ul class="songs">
                    @forelse($songs as $song)
                        @include('song.one',['song'=>$song,'loop'=>$loop])
                    @empty
                        <li>No songs</li>
                    @endforelse
                </ul>
                {{$songs->links()}}

            </div>
            @include('partials.tags',['tags'=> $hot_tags])
        </div>
        @include("partials.footer")
    </div>
@endsection
