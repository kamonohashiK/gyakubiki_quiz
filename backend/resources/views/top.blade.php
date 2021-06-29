@extends('layouts.layout')

@section('content')
<h3>最近追加された問題の答え</h3>

<ul>
@foreach($answers as $a)
    <li><a href="{{route('questions.index', ['answer' => $a->name])}}">{{$a->name}}</a></li>
@endforeach
</ul>
@endsection