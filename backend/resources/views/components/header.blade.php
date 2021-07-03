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