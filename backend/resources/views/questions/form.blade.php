@extends('layouts.layout')

@section('content')
<h4>{{ $edit ? '問題編集' : '問題新規作成' }}</h4>
<p>答え: {{ $query }}</p>

@if($edit)
<form action="" method="POST">
@else
<form action="{{ route('questions.create') }}" method="POST">
@endif
    @csrf
    <input type="hidden" name="answer" value="{{ $query }}">
    <div class="mb-3">
        <textarea name="question" class="form-control" required style="height:80px;">{{ $content }}</textarea>
    </div>
    <input type="submit" value="{{ $edit ? '編集' : '作成' }}" class="btn btn-success">
</form>
@endsection