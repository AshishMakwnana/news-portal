@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-12">
        <h1 class="text-3xl font-bold mb-4">{{ $item->title }}</h1>
        <p class="text-sm text-gray-500 mb-4">By {{ $item->author->name }} •
            {{ $item->published_at?->format('M d, Y') ?? '' }}</p>

        @if ($item->featured_image)
            <img src="{{ asset('storage/' . $item->featured_image) }}" alt="" class="w-full rounded mb-6">
        @endif

        <div class="prose max-w-none">{!! $item->body !!}</div>

        <div class="mt-10">
            <h2 class="text-xl font-semibold mb-4">Comments ({{ $item->comments()->count() }})</h2>

            <div class="space-y-4">
                @foreach ($item->approvedComments()->get() as $comment)
                    <div class="border rounded p-3">
                        <div class="text-sm text-gray-600">{{ $comment->user->name ?? 'Guest' }} •
                            {{ $comment->created_at->diffForHumans() }}</div>
                        <div class="mt-2">{!! $comment->body !!}</div>
                        @auth
                            @if (auth()->id() === $comment->user_id)
                                <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-sm text-red-600">Delete</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @endforeach
            </div>

            @auth
                <div class="mt-6">
                    <form action="{{ route('news.comments.store', $item) }}" method="POST">
                        @csrf
                        <label class="block text-sm font-medium">Add a comment</label>
                        <textarea name="body" id="comment-body" rows="4" class="mt-1 block w-full border rounded p-2" required></textarea>
                        <div class="mt-3">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded">Submit</button>
                        </div>
                    </form>
                </div>
            @else
                <p class="mt-6">Please <a href="{{ route('login') }}" class="text-blue-600">login</a> to comment.</p>
            @endauth
        </div>
    </div>
@endsection
