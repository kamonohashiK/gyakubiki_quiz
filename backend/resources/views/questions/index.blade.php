@extends('layouts.layout')

@section('content')
    <h1>{{ $query }}が答えになる問題</h1>
    <a href="{{route('questions.new', ['answer' => $query])}}">{{ $query }}が答えになる問題を作る</a>
    @if($answer)
        @foreach($answer->questions as $q)
        <div>
            <p>{{$q->content}}</p>
            <small>作者名</small>
            <small>{{$q->updated_at}}</small>
            <a href="{{route('questions.show', $q->id)}}">詳細</a>
        </div>
        @endforeach
    @else
        <p>問題の登録がありませんでした。</p>
    @endif
@endsection