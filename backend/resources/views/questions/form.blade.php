@extends('layouts.layout')

@section('content')
<h1>{{ $edit ? '問題編集' : '問題新規作成' }}</h1>
<p>答え: {{ $query }}</p>

@if($edit)
<form action="" method="POST">
@else
<form action="{{ route('questions.create') }}" method="POST">
@endif
    @csrf
    <input type="hidden" name="answer" value="{{ $query }}">
    <textarea name="question" cols="30" rows="10">{{ $content }}</textarea>
    <input type="submit" value="作成">
</form>
@endsection