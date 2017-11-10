@extends('layouts.app')

@section('pageTitle', "Approving songs | mp3cloud.su")
@section('pageDescription', trans('words.home_description'))

@section('content')
    <style>{!!file_get_contents(public_path('css/homepage.css'))!!}</style>
    <style>
       /* .songs {
            margin-top: 20px;
        }*/
       .song_name {
           max-width: 700px;
       }
        .song_approve, .song_skip,.song_duration {
            width: 90px;
            margin: 0 10px 0;
            cursor: pointer;
            float: right;
            padding: 3px 5px;
            text-align: center;
            color: white;
        }
        .song_duration {
            color: #0f0f0f;
        }
        .song_approve {
            background: green;
        }

        .song_skip {
            background: orangered;
        }
        .song_poster { float: none;}
        .approve_box,.approve_label {
            margin: 15px 0;
            padding: 0;
            vertical-align: top;
        }
        .approve_box {
            /*display: none;*/
        }
        .approve_label {
           /* width: 50px;
            height: 35px;
            background: gray;*/
        }
        .approve_all {
            margin: 5px;
            color: white;
            background: green;
            padding: 6px 12px;
            text-align: center;
            outline: none;
            border: none;
        }
    </style>
    @include('partials.functions')
    <script>
        function select4approving(track) {
            var event = track.onclick.arguments[0];
            event.stopPropagation();
            return true;
        }
        function approveSong(track, track_id) {
            var event = track.onclick.arguments[0];
            event.stopPropagation();
            storeModeration(track_id, 1)
        }

        function skipSong(track, track_id) {
            var event = track.onclick.arguments[0];
            event.stopPropagation();
            storeModeration(track_id, 0)
        }

        function storeModeration(track_id, type) {
            $.ajax({
                type: "GET",
                url: getBaseUrl() + "admin/songs/store_approve/" + track_id + "/" + type,
                data: [],
                success: function (response) {
                    $("#song_"+track_id).hide(200);
                },
                error: function (request, status, error_message) {
                    console.log(request.responseJSON);
                }
            });
        }
        function approveChecked() {
            var checkedSongs = $(".songs input[type='checkbox']:checked");
            $.each(checkedSongs,function (i,e) {
                storeModeration($(e).val(), 1);
            });
        }
    </script>
    <div class="container page_content">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 track_list">
                <button class="approve_all" onclick="approveChecked()">Approve checked</button>
                <ul class="songs">
                    @forelse($songs as $song)
                        <li id="song_{{$song->id}}">
                            <div data-song_id="{{$song->id}}"  data-source="{{asset($song->file_url)}}" onclick="return playTrack(this)"
                                 class="track @if($loop->first) track-default @endif ">
                                <label onclick="return select4approving(this)" class="approve_label" for="song_{{$song->id}}">
                                    <input checked onclick="return select4approving(this)" id="song_{{$song->id}}" type="checkbox" class="approve_box" value="{{$song->id}}">
                                </label>
                                <div class="song_poster"  style="background-image: url('{{$song->thumbnail_mini}}')">
                                    <img class="play" src="{{asset('public/images/play-button_white.png')}}" alt="Play">
                                    <img class="pause" src="{{asset('public/images/pause_white.png')}}" alt="Pause">
                                    <div class="song_poster_cover"></div>

                                </div>
                                <span class="song_name">{{$song->title}}</span>


                                <a class="song_video" onclick="showSong(this)"
                                   href="{{route('show_song_'.$locale,[ 'slug' => prepareSlugUrl($song->id,$song->title)])}}">
                                    <img class="off" src="{{asset('public/images/video.png')}}" alt="Watch video">
                                    <img class="on" src="{{asset('public/images/video2.png')}}" alt="Watch video">
                                </a>

                                <a class="song_approve" onclick="approveSong(this,{{$song->id}})"> APPROVE </a>
                                <a class="song_skip" onclick="skipSong(this,{{$song->id}})"> SKIP </a>
                                <a class="song_duration" >{{gmdate("i:s", $song->duration)}} </a>
                            </div>
                        </li>
                    @empty
                        <li>No songs</li>
                    @endforelse
                </ul>
                <button class="approve_all" onclick="approveChecked()">Approve checked</button>
            </div>
        </div>
        @include("partials.footer")
    </div>

@endsection

@section('footer_scripts')
    @include('partials.player')
@endsection