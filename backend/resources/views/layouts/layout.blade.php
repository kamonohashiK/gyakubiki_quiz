<DOCTYPE HTML>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <title>タイトル</title>
    </head>

    <body>
        <header class="header">
            <a href="{{route('top')}}">クイズ逆引き事典</a>

            <h3>問題を検索</h3>
            <form action="{{route('questions.index')}}" method="GET">
                <input type="text" name="answer" />
                <input type="submit" value="検索">
            </form>
        </header>
        <div class="main">
            @yield('content')
        </div>
    </body>

    </html>