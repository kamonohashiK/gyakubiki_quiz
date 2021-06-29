<a href="{{route('top')}}">クイズ逆引き事典</a>

<h3>問題を検索</h3>
<form action="{{route('questions.index')}}" method="GET">
    <input type="text" name="answer" />
    <input type="submit" value="検索">
</form>

<h1>{{$answer}}が答えになる問題</h1>