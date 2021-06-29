<h1>top</h1>

<h3>問題を検索</h3>
<form action="{{route('questions.index')}}" method="GET">
    <input type="text" name="answer" />
    <input type="submit" value="検索">
</form>

<h3>最近追加された問題の答え</h3>
<ul>
    <li><a href="{{route('questions.index', ['answer' => 'hoge'])}}">hoge</a></li>
    <li><a href="{{route('questions.index', ['answer' => '愛媛県'])}}">愛媛県</a></li>
    <li><a href="{{route('questions.index', ['answer' => 'ランドルト環'])}}">ランドルト環</a></li>
    <li><a href="{{route('questions.index', ['answer' => '新垣結衣'])}}">新垣結衣</a></li>
    <li><a href="{{route('questions.index', ['answer' => '15'])}}">15</a></li>
</ul>