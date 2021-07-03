@extends('layouts.layout')

@section('content')
<div>
    <p>ワードを指定すると、そのワードが答えになるクイズの問題文を探せるWebサービスです。</p>
    <p>ヘッダーの検索フォームに調べたい単語を入力して、お気に入りの問題を見つけたり、問題を投稿したりしてみてください。</p>
</div>
<h4>登録問題数: {{ $count }}問</h4>

<h4>●最近追加された問題</h4>
@foreach($questions as $q)
<x-card :q="$q" />
@endforeach
{{-- <x-badges :answers="$answers" /> --}}
@endsection