@extends('layouts.layout')

@section('content')
<h1>問題詳細</h1>
<p>{{$question->content}}</p>
@if(Auth::user() && Auth::user()->id == $question->user_id)
<a href="{{route('questions.edit', $question)}}">編集</a>
<form id="delete-form" action="{{route('questions.delete', $question)}}" method="post">
    @csrf
    @method('DELETE')
    <input type="submit" value="削除">
</form>
@endif

<h4>コメント一覧</h4>
<ul>
@foreach($question->comments as $c)
<li>{{ $c->content }} {{ $c->created_at}} {{ $c->user->name }}</li>
@endforeach
</ul>

@if(Auth::user())
<b>コメントする</b>
<form action="{{ route('comments.create', $question) }}" method="POST">
    @csrf
    <textarea name="comment" id="" cols="30" rows="10"></textarea>
    <input type="submit" value="投稿">
</form>
@else
<p>コメントを投稿するにはログインしてください。</p>
@endif

@endsection