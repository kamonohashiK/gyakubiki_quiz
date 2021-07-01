@extends('layouts.layout')

@section('content')
<h1>問題詳細</h1>
<p>{{$question->content}}</p>
@if(Auth::user() && Auth::user()->id == $question->user_id)
<a href="{{route('questions.edit', $question)}}">編集</a>
@endif

<h4>コメント一覧</h4>
@endsection