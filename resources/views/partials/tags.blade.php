<div class="col-lg-4 ">
    <div class="tags">
        <h2>Hot Tags</h2>
        @forelse($tags as $tag)
            <a class="tag" href="{{route('show_tag_'.$locale,[ 'slug' => prepareSlugUrl($tag->id,$tag->name)])}}">{{$tag->name}}</a>
        @empty
            <li>No tags</li>
        @endforelse
    </div>
</div>