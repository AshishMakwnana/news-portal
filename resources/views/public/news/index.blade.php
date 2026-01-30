@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6">{{ $title ?? 'Latest News' }}</h1>

        <div class="mb-4">
            <a href="{{ route('home') }}" class="text-sm text-gray-600 underline">All</a>
            @foreach (App\Models\Category::all() as $cat)
                <a href="{{ route('category.show', $cat->slug) }}" class="ml-3 text-sm text-blue-600">{{ $cat->name }}</a>
            @endforeach
        </div>

        <div class="space-y-6">
            @foreach ($news as $item)
                <article class="border p-4 rounded-lg">
                    <a href="{{ route('news.show', $item->slug) }}" class="text-xl font-semibold">{{ $item->title }}</a>
                    <p class="text-sm text-gray-500">{{ $item->published_at->format('M d, Y') }} •
                        {{ $item->category?->name }}</p>
                    <p class="mt-2 text-gray-700">{{ Str::limit($item->excerpt, 150) }}</p>
                    <div class="mt-2">
                        @foreach ($item->tags as $tag)
                            <a href="{{ route('tag.show', $tag->slug) }}"
                                class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $news->links() }}
        </div>
    </div>
@endsection
