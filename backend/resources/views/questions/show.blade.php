@extends('layouts.layout')

@section('content')
<h4>
    <a href="{{ route('questions.index', ['answer' => $question->answer->name]) }}">{{ $question->answer->name }}</a>が答えになる問題
    <a href="{{ route('questions.new', ['answer' => $question->answer->name]) }}" class="btn btn-primary">問題を作る</a>
</h4>

<p>{!! convertLink(htmlentities($question->content)) !!}</p>
@if(Auth::user() && Auth::user()->id == $question->user_id)
<form id="delete-form" action="{{route('questions.delete', $question)}}" method="post">
    @csrf
    @method('DELETE')
    <a href="{{route('questions.edit', $question)}}" class="btn btn-primary">編集</a>
    <input class="btn btn-danger" type="submit" value="削除">
</form>
@endif

@if(Auth::user())
<form action="{{ route('comments.create', $question) }}" method="POST">
    @csrf
    <div class="input-group mb-3">
        <input name="comment" type="text" class="form-control" placeholder="コメントを投稿" aria-label="Example text with button addon" aria-describedby="button-addon1">
        <button class="btn btn-success" type="submit" id="button-addon1">投稿</button>
    </div>
</form>
@else
<p>コメントを投稿するにはログインしてください。</p>
@endif

<ul class="list-group">
    @foreach($question->comments as $c)
    <li class="list-group-item">{{ $c->content }} {{ date('Y年n月j日 H:i:s', strtotime($c->created_at)) }} {{ $c->user->name }}</li>
    @endforeach
</ul>

@endsection