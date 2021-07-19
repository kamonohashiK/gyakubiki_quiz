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
            <textarea name="question" class="form-control" required style="height:80px;" placeholder="10字以上100字以内" v-model="question"></textarea>
        </div>
        <p><span v-bind:class="{ invalid: !valid }">@{{question.length}}</span>/100</p>
        <input type="submit" value="{{ $edit ? '編集' : '作成' }}" class="btn btn-success" :disabled="!valid">
    </form>

    <script>
        new Vue({
            el: "#app",
            data: {
                question: '{{ $content }}',
            },
            computed: {
                valid() {
                    return this.question.length >= 10 && this.question.length <= 100;
                }
            }
        });
    </script>
@endsection