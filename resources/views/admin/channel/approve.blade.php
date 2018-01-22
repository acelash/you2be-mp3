@extends('layouts.app')

@section('pageTitle', "Approving channel | mp3cloud.su")
@section('pageDescription', trans('words.home_description'))

@section('content')
    <style>

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

        .song_approve {
            background: green;
        }

        .song_skip {
            background: #3e74ff;
        }
        .approve_box,.approve_label {
            margin: 15px 0;
            padding: 0;
            vertical-align: top;
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
                url: getBaseUrl() + "admin/channels/store_approve/" + track_id + "/" + type,
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
                    @forelse($channels as $channel)
                        <li id="song_{{$channel->id}}">
                            <div data-song_id="{{$channel->id}}"
                                 class="track">
                                <label onclick="return select4approving(this)" class="approve_label" for="song_{{$channel->id}}">
                                    <input checked onclick="return select4approving(this)" id="song_{{$channel->id}}" type="checkbox" class="approve_box" value="{{$channel->id}}">
                                </label>
                                <a class="song_name">{{$channel->channel_id}}</a>

                                <a class="song_approve" onclick="approveSong(this,{{$channel->id}})"> APPROVE </a>
                                <a target="_blank" href="https://www.youtube.com/channel/{{$channel->channel_id}}" class="song_skip" onclick="skipSong(this,{{$channel->id}})"> VIEW </a>
                            </div>
                        </li>
                    @empty
                        <li>No channels</li>
                    @endforelse
                </ul>
                <button class="approve_all" onclick="approveChecked()">Approve checked</button>
            </div>
        </div>
        @include("partials.footer")
    </div>

@endsection