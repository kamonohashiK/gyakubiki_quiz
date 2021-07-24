@extends('layouts.layout')
@section('title', $query . $suffix)
@section('content')
<h4>
    <b>{{ $query }}</b>{{ $suffix }}
    @if(!$like)
        <a href="{{route('questions.new', ['answer' => $query])}}" class="btn btn-primary" style="margin:5px;">問題を作る</a>
    @endif
</h4>

@if($questions)
    @foreach($questions as $q)
        <x-card :q="$q" />
    @endforeach
@else
    <p>問題の登録がありませんでした。</p>
@endif
@endsection