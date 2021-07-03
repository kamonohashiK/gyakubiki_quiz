@extends('layouts.layout')

@section('content')
<b>登録問題数: {{ $count }}問</b>

<h3>最近追加された問題の答え</h3>

<x-badges :answers="$answers" />
@endsection