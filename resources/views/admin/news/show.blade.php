@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12">
    <h1 class="text-3xl font-bold mb-2">{{ $news->title }}</h1>
    <p class="text-sm text-gray-500 mb-4">By {{ $news->author->name }} • {{ $news->published_at?->format('M d, Y') }}</p>

    @if($news->featured_image)
        <img src="{{ asset('storage/' . $news->featured_image) }}" alt="" class="w-full rounded mb-6">
    @endif

    <div class="prose max-w-none">{!! nl2br(e($news->body)) !!}</div>
</div>
@endsection