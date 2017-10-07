@extends('layouts.app')

@section('pageTitle', "TUNE-TUBE")
@section('pageDescription', "")

@section('content')
    <style>{!!file_get_contents(public_path('css/homepage.css'))!!}</style>
    <div class="container page_content">
        <div class="row">
            <div class="col-lg-8 track_list">
                <h1 style="    padding: 0 30px;">Popular songs</h1>
                <ul class="songs">
                    @forelse($popular as $song)
                        @include('song.one',['song'=>$song,'loop'=>$loop])
                    @empty
                        <li>No songs</li>
                    @endforelse
                </ul>
                {{$popular->links()}}

            </div>
            <div class="col-lg-4 tags">
                <h2>Hot Tags</h2>
                @forelse($hot_tags as $tag)
                    <a class="tag" href="">{{$tag->name}}</a>
                @empty
                    <li>No tags</li>
                @endforelse

            </div>
        </div>
        @include("partials.footer")
    </div>

@endsection

@section('footer_scripts')
    @include('partials.player')
    <script>
        $(document).ready(function () {
            $(".tabs-menu a").click(function (event) {
                event.preventDefault();
                $(this).parent().addClass("current");
                $(this).parent().siblings().removeClass("current");
                var tab = $(this).attr("href");
                $(".tab-content").not(tab).css("display", "none");
                $(tab).fadeIn();
            });
        });
    </script>
@endsection