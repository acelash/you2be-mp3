{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends('admin.layouts.admin')

@section('pageTitle', trans('translate.moderation'))

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{trans('translate.moderation')}}</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
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

                        <table class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Text</th>
                                <th>Author</th>
                                <th>Movie</th>
                                <th>Created at</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $comment)
                                    <tr>
                                        <td>{{$comment->title}}</td>
                                        <td>{{$comment->text}}</td>
                                        <td>{{$comment->user_name}}</td>
                                        <td>
                                            <a target="_blank" href="{{route('show_movie',[ 'slug' => prepareSlugUrl($comment->movie_id,$comment->movie_title)])}}">
                                                {{$comment->movie_title}}
                                            </a>
                                        </td>
                                        <td>{{$comment->created_at->format("d.m.Y H:i")}}</td>
                                        <td class="actions">
                                            <a href="{{url('admin/comments/approve/'.$comment->id)}}">
                                                <button class="btn btn-success btn-xs"><i class="fa fa-check" aria-hidden="true"></i> Approve</button>
                                            </a>
                                            <a href="{{url('admin/comments/delete/'.$comment->id)}}">
                                                <button class="btn btn-warning btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                                            </a>
                                            <a href="{{url('admin/comments/block/'.$comment->id)}}">
                                                <button class="btn btn-danger btn-xs"><i class="fa fa-times" aria-hidden="true"></i> Block</button>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">No unchecked comments</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

    </script>

@endsection
