@extends('layouts.layout')

@section('content')
<h4>
    <b>{{ $query }}</b>が答えになる問題
    <a href="{{route('questions.new', ['answer' => $query])}}" class="btn btn-primary" style="margin:5px;">問題を作る</a>
</h4>
@if($answer)
    @foreach($answer->questions as $q)
        <x-card :q="$q" />
    @endforeach
@else
    <p>問題の登録がありませんでした。</p>
@endif
@endsection