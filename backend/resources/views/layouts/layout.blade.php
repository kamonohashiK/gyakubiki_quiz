<DOCTYPE HTML>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <title>タイトル</title>
    </head>

    <body>
        <header class="header">
            <a href="{{route('top')}}">クイズ逆引き事典</a>

            @if(auth::user())
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                {{ __('ログアウト') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            @else
            <a class="nav-link" href="{{ route('login') }}">{{ __('ログイン') }}</a>
            @endif

            <h3>問題を検索</h3>
            <form action="{{route('questions.index')}}" method="GET">
                <input type="text" name="answer" />
                <input type="submit" value="検索">
            </form>
        </header>
        <div class="main">
            @if(session('success'))
            <p> {{ session('success') }}</p>
            @endif
            @yield('content')
        </div>
    </body>

    </html>