@extends('layouts.layout')

@section('content')
<h4>登録問題数: {{ $count }}問</h4>

<h4>●最近追加された問題</h4>
@foreach($questions as $q)
<x-card :q="$q" />
@endforeach
{{-- <x-badges :answers="$answers" /> --}}
@endsection