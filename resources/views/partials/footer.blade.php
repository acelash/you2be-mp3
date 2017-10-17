<div class="row light-primary-color footer">

   {{-- <div class="col-md-4 footer_block">
        <div class="title">Смотреть</div>
        <ul>
            <li><a href="{{url('movies')}}">Все фильмы</a></li>
            <li><a href="{{route('catalog_filtered',['slug'=>'year','id'=>date("Y",time())])}}">Новые фильмы {{date("Y",time())}}</a></li>
            <li><a href="{{url('movies?sort=popular')}}">Популярные фильмы</a></li>
        </ul>
    </div>
    <div class="col-md-4 footer_block">
        <div class="title">Полезное</div>
        <ul>
            <li><a href="{{route('rules')}}">Правила сайта</a></li>
            <li><a>Карта сайта</a></li>
        </ul>
    </div>--}}
    <span class="contacts"><a href="{{route('copyrights_'.$locale)}}">@lang('words.copyrights')</a></span>

        <!--LiveInternet counter-->
        <script type="text/javascript">
            document.write("<a class='counter_li' href='//www.liveinternet.ru/click' "+
                "target=_blank><img src='//counter.yadro.ru/hit?t45.6;r"+
                escape(document.referrer)+((typeof(screen)=="undefined")?"":
                    ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
                        screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
                ";h"+escape(document.title.substring(0,80))+";"+Math.random()+
                "' alt='' title='LiveInternet' "+
                "border='0' width='31' height='31'><\/a>")
        </script><!--/LiveInternet-->
    <span class="copyrights">&copy; 2017 mp3cloud.su</span>

</div>