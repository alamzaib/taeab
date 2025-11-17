@extends('layouts.app')

@section('title', $page->meta_title ?: $page->title)
@section('meta_description', $page->meta_description ?? '')

@section('content')
<div class="container">
    <div class="card">
        <h1 class="primary-text" style="font-size: 32px; margin-bottom: 20px;">{{ $page->title }}</h1>
        <div class="page-content">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection

