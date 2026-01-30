@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6">Latest News</h1>

        <div class="space-y-6">
            @foreach ($news as $item)
                <article class="border p-4 rounded-lg">
                    <a href="{{ route('news.show', $item->slug) }}" class="text-xl font-semibold">{{ $item->title }}</a>
                    <p class="text-sm text-gray-500">{{ $item->published_at->format('M d, Y') }}</p>
                    <p class="mt-2 text-gray-700">{{ Str::limit($item->excerpt, 150) }}</p>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $news->links() }}
        </div>
    </div>
@endsection
