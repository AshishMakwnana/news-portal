@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12">
    <h1 class="text-3xl font-bold mb-4">{{ $item->title }}</h1>
    <p class="text-sm text-gray-500 mb-4">By {{ $item->author->name }} • {{ $item->published_at->format('M d, Y') }}</p>

    @if($item->featured_image)
        <img src="{{ asset('storage/' . $item->featured_image) }}" alt="" class="w-full rounded mb-6">
    @endif

    <div class="prose max-w-none">{!! nl2br(e($item->body)) !!}</div>
</div>
@endsection