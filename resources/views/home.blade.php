@extends('layouts.app')

@section('pageTitle', "TUNE-TUBE")
@section('pageDescription', "")

@section('content')
    <style>{!!file_get_contents(public_path('css/homepage.css'))!!}</style>
    @include('partials.functions')
    <div class="container page_content">
        <div class="row">
            <div class="col-lg-8 track_list">
                <h1 style="    padding: 0 30px;">
                    @if(isset($sorted) && $sorted == 'new')
                        @lang('words.new_songs')
                    @else
                        @lang('words.popular_songs')
                    @endif
                </h1>
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

@section('footer_scripts')
    @include('partials.player')
@endsection