@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-12">
        <h1 class="text-3xl font-bold mb-2">{{ $news->title }}</h1>
        <p class="text-sm text-gray-500 mb-4">By {{ $news->author->name }} • {{ $news->published_at?->format('M d, Y') }}</p>

        @if ($news->featured_image)
            <img src="{{ asset('storage/' . $news->featured_image) }}" alt="" class="w-full rounded mb-6">
        @endif

        <div class="prose max-w-none">{!! $news->body !!}</div>

        <div class="mt-8">
            <h2 class="text-xl font-semibold">Comments</h2>
            <div class="mt-4 space-y-4">
                @foreach ($news->comments()->latest()->get() as $comment)
                    <div class="border p-3 rounded">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">{{ $comment->user?->name ?? 'Guest' }} •
                                {{ $comment->created_at->diffForHumans() }}</div>
                            <div class="flex items-center gap-2">
                                @if (!$comment->approved)
                                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                                        @csrf
                                        <button class="text-sm text-green-600">Approve</button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-sm text-red-600">Delete</button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-2">{!! $comment->body !!}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
