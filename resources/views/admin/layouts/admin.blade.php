<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('pageTitle') | {{config('app.name',"")}} </title>
    <link rel="shortcut icon" href="{{asset('public/images/logo-min.png')}}" type="image/x-icon">

    <!-- Bootstrap -->
    <link href="{{url('')}}/public/admin/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{url('')}}/public/admin/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{url('')}}/public/admin/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{url('')}}/public/admin/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="{{url('')}}/public/admin/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{url('')}}/public/admin/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{url('')}}/public/admin/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Datatables -->
    <link href="{{url('')}}/public/admin/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="{{url('')}}/public/admin/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="{{url('')}}/public/admin/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="{{url('')}}/public/admin/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="{{url('')}}/public/admin/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <link href="{{url('')}}/public/admin/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{asset('public/admin/build/css/custom.css')}}" rel="stylesheet">
    <link href="{{asset('public/admin/css/style.css')}}" rel="stylesheet">

    <link rel="shortcut icon" href="{{config("app.url")}}/public/images/logo.png" type="image/png">

    <link rel="stylesheet" href="{{asset("public/admin/css/multi-select.css")}}">

    <!-- jQuery -->
    <script src="{{url('')}}/public/admin/vendors/jquery/dist/jquery.min.js"></script>
    <script src="{{asset("public/admin/js/jquery.multi-select.js")}}"></script>
    <script>
        function getBaseUrl() {
            return "{{ env('APP_URL') }}";
        }
    </script>
    @yield('headerScripts')

    <style>
        footer .pull-right {
            color: white;
        }
        .mce-branding-powered-by {
            opacity: 0 !important;
        }
    </style>
</head>

<body class="nav-md">
<form id="logout-form" action="{{ env('APP_URL')."logout"  }}" method="POST"
      style="display: none;">
    {{ csrf_field() }}
</form>
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="{{url('/admin/home')}}" class="site_title"><i class="fa fa-cogs"></i> <span>{{config('app.name',"Admin")}} | {{trans('translate.admin')}} </span></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile clearfix">
                   {{-- <div class="profile_pic">
                        <img src="{{auth()->user()->avatar_path}}" alt="..." class="img-circle profile_img">
                    </div>--}}
                    <div class="profile_info">
                        <span>{{trans('translate.welcome')}},</span>
                        <h2>{{ Auth::user()->email }}</h2>
                    </div>
                </div>
                <!-- /menu profile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        {{--<h3>General</h3>--}}
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-file-movie-o"></i> {{trans('translate.songs')}} <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{url('admin/songs/approve')}}">Approve songs</a></li>
                                 {{--   <li><a href="{{url('admin/songs/new')}}">{{trans('translate.add')}}</a></li>
                                    <li><a href="{{url('admin/songs')}}">{{trans('translate.all_songs')}}</a></li>--}}
                                </ul>
                            </li>
                            <li><a><i class="fa fa-users"></i> {{trans('translate.users')}} <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{url('admin/users')}}">{{trans('translate.all_users')}}</a></li>
                                </ul>
                            </li>
                          {{--  <li><a><i class="fa fa-list"></i> @lang('translate.nomenclatures') <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{url('admin/genres')}}">@lang('translate.genres')</a></li>
                                    <li><a href="{{url('admin/countries')}}">@lang('translate.countries')</a></li>
                                </ul>
                            </li>--}}

                            {{--<li><a><i class="fa fa-comment"></i> {{trans('translate.comments')}} <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{url('admin/comments/moderate')}}">{{trans('translate.moderate')}}</a></li>
                                </ul>
                            </li>--}}
                           {{-- <li><a><i class="fa  fa-file-text-o"></i> Pagini <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{url('admin/pages')}}">Toate paginile</a></li>
                                    <li><a href="{{url('admin/pages/new')}}">Adaugă pagină</a></li>
                                </ul>
                            </li>--}}
                            {{--<li><a><i class="fa  fa-picture-o"></i> Banere <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{url('admin/banners')}}">Toate banerele</a></li>

                                </ul>
                            </li>
                            <li><a><i class="fa  fa-money"></i> Pricelist <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{url('admin/prices')}}">Toate preţurile</a></li>

                                </ul>
                            </li>--}}
                            {{--<li><a><i class="fa  fa-briefcase"></i> Oferte de muncă <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{url('admin/offers')}}">Toate ofertele</a></li>

                                </ul>
                            </li>
                            <li><a><i class="fa  fa-calendar-check-o"></i> CV-uri <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{url('admin/cv')}}">Toate CV-urile</a></li>

                                </ul>
                            </li>--}}
                        </ul>
                    </div>
                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">

                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                {{--<img src="{{auth()->user()->avatar_path}}" alt="">--}}{{ auth()->user()->email }}
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li class="">
                                    <a target="_blank" href="{{env("APP_URL")}}" class="user-profile" >
                                        Website-ul public
                                    </a>
                                </li>
                                <li onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <a href="{{ env("APP_URL").'logout' }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main" style="max-height: inherit">
        <script>
            $( ".right_col" ).resize(function() {
                console.log("resized");
                debugger
            });
        </script>

            @yield('content')

        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template {{--by <a href="https://colorlib.com">Colorlib</a>--}}
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>


<!-- Bootstrap -->
<script src="{{url('')}}/public/admin/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="{{url('')}}/public/admin/vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="{{url('')}}/public/admin/vendors/nprogress/nprogress.js"></script>
<!-- Chart.js -->
<script src="{{url('')}}/public/admin/vendors/Chart.js/dist/Chart.min.js"></script>
<!-- gauge.js -->
<script src="{{url('')}}/public/admin/vendors/gauge.js/dist/gauge.min.js"></script>
<!-- bootstrap-progressbar -->
<script src="{{url('')}}/public/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
<!-- iCheck -->
<script src="{{url('')}}/public/admin/vendors/iCheck/icheck.min.js"></script>

<!-- Datatables -->
<script src="{{url('')}}/public/admin/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="{{url('')}}/public/admin/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/jszip/dist/jszip.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/pdfmake/build/vfs_fonts.js"></script>


<!-- Skycons -->
<script src="{{url('')}}/public/admin/vendors/skycons/skycons.js"></script>
<!-- Flot -->
<script src="{{url('')}}/public/admin/vendors/Flot/jquery.flot.js"></script>
<script src="{{url('')}}/public/admin/vendors/Flot/jquery.flot.pie.js"></script>
<script src="{{url('')}}/public/admin/vendors/Flot/jquery.flot.time.js"></script>
<script src="{{url('')}}/public/admin/vendors/Flot/jquery.flot.stack.js"></script>
<script src="{{url('')}}/public/admin/vendors/Flot/jquery.flot.resize.js"></script>
<!-- Flot plugins -->
<script src="{{url('')}}/public/admin/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
<script src="{{url('')}}/public/admin/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/flot.curvedlines/curvedLines.js"></script>
<!-- DateJS -->
<script src="{{url('')}}/public/admin/vendors/DateJS/build/date.js"></script>
<!-- JQVMap -->
<script src="{{url('')}}/public/admin/vendors/jqvmap/dist/jquery.vmap.js"></script>
<script src="{{url('')}}/public/admin/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
<script src="{{url('')}}/public/admin/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{url('')}}/public/admin/vendors/moment/min/moment.min.js"></script>
<script src="{{url('')}}/public/admin/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>


<!-- Custom Theme Scripts -->
<script src="{{url('')}}/public/admin/build/js/custom.js"></script>

@yield('footerScripts')
</body>
</html>


