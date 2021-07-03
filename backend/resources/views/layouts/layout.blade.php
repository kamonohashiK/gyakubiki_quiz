<DOCTYPE HTML>
    <html lang="ja">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>タイトル</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>
        <nav class="navbar navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{route('top')}}">クイズ逆引き事典</a>

                <form class="d-flex" action="{{route('questions.index')}}" method="GET">
                    <input class="form-control me-2" type="text" name="answer" placeholder="問題を検索" aria-label="Search" />
                    <input type="submit" value="検索" class="btn btn-outline-success">
                </form>

                @if(auth::user())
                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    {{ __('ログアウト') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                @else
                <a href="{{ route('login') }}">{{ __('ログイン') }}</a>
                @endif
            </div>
        </nav>

        <div class="main">
            @if(session('success'))
            <p> {{ session('success') }}</p>
            @endif
            @yield('content')
        </div>
    </body>

    </html>