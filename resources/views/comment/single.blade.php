<div class="col-lg-12 comment_single">
    <div class="comment_author_avatar">
        <img class="img-responsive img-rounded" src="{{$comment->user_avatar}}">
    </div>
    <div class="meta">
        <span class="author"><a>{{$comment->user_name}}</a></span>
        <span class="date">{{$comment->created_at->format("d.m.Y в H:i")}}</span>

        @if($comment->type == 1) <span class="type positive"> Положительный </span> @endif
        @if($comment->type == 2) <span class="type negative"> Отрицательный </span> @endif
    </div>
    @if($comment->title)
        <div class="title">{{$comment->title}}</div>
    @endif
    <div class="text">{{$comment->text}}</div>
</div>