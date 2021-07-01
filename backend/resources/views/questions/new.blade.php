@extends('layouts.layout')

@section('content')
<h1>問題新規作成</h1>
<p>答え: {{ $query }}</p>
<form action="{{ route('questions.create') }}" method="POST">
    @csrf
    <input type="hidden" name="answer" value="{{ $query }}">
    <textarea name="question" cols="30" rows="10"></textarea>
    <input type="submit" value="作成">
</form>
@endsection