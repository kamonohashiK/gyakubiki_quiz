@extends('layouts.layout')

@section('content')
    <h1>{{ $answer }}が答えになる問題</h1>

    @for($i=0;$i<=10;$i++)
    <div>
        <p>問題文のサンプル</p>
        <small>作者名</small>
        <small>編集日</small>
        <a href="{{route('questions.show', $i)}}">詳細</a>
    </div>
    @endfor
@endsection