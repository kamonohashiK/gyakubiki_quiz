@extends('layouts.layout')

@section('content')
<h4>
    @if($like)
    <b>{{ $query }}</b>が答えに含まれる問題
    @else
    <b>{{ $query }}</b>が答えになる問題
    <a href="{{route('questions.new', ['answer' => $query])}}" class="btn btn-primary" style="margin:5px;">問題を作る</a>
    @endif
</h4>
@if($like && $answer)
    @if($answer->count())
        @foreach($answer as $a)
            @foreach($a->questions as $q)
                <x-card :q="$q" />
            @endforeach
        @endforeach
    @else
        <p>問題の登録がありませんでした。</p>
    @endif
@elseif($answer)
    @foreach($answer->questions as $q)
        <x-card :q="$q" />
    @endforeach
@else
    <p>問題の登録がありませんでした。</p>
@endif
@endsection