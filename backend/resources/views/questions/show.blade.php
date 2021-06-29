@extends('layouts.layout')

@section('content')
<h1>問題詳細</h1>
<p>{{$question->content}}</p>

<h4>コメント一覧</h4>
@endsection