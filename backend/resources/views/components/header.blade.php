<nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{route('top')}}">クイズ逆引き事典</a>

        <form class="row" action="{{route('questions.index')}}" method="GET">
            <div class="col-auto">
                <input class="form-control me-2" type="text" name="answer" placeholder="検索ワード" aria-label="Search" value="{{ session()->get('query') }}"/>
            </div>
            <div class="col-auto">
                <div class="form-check form-switch">
                    <input class="form-check-input" name="like" type="checkbox" id="flexSwitchCheckChecked" value="1" @if(session()->get('like')) checked @endif>
                    <label class="form-check-label" for="flexSwitchCheckChecked">を含む</label>
                </div>
            </div>
            <div class="col-auto">
                <input type="submit" value="検索" class="btn btn-outline-success">
            </div>
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