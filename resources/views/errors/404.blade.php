<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        {{--<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">--}}
        <title>404 - {{ $exception->getMessage() ?: 'Страница не найдена' }}</title>
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: /*'Lato',*/  sans-serif;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
            .to_home {
                text-decoration: none;
                background: #B0BEC5;
                padding: 10px 20px;
                color: white;
                font-size: 23px;
                border-radius: 5px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <img src="{{asset('public/images/404.png')}}">
                <div class="title">{{ $exception->getMessage() ?: 'Страница не найдена' }}</div>
                <a class="to_home" href="{{url('/')}}">Вернуться на сайт</a>
            </div>
        </div>
    </body>
</html>
