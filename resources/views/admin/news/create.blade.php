@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-12">
        <h1 class="text-2xl font-bold mb-6">Create News</h1>

        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium">Title</label>
                <input type="text" name="title" class="mt-1 block w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Excerpt</label>
                <textarea name="excerpt" class="mt-1 block w-full border rounded p-2"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Body</label>
                <textarea name="body" id="editor" rows="8" class="mt-1 block w-full border rounded p-2" required></textarea>
            </div>

            @push('scripts')
                <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        ClassicEditor.create(document.querySelector('#editor')).catch(e => console.error(e));
                    });
                </script>
            @endpush
            <div class="mb-4">
                <label class="block text-sm font-medium">Featured Image</label>
                <div class="flex items-center gap-4">
                    <input type="file" name="featured_image" class="mt-1">
                    <a href="{{ route('admin.media.index') }}" class="text-blue-600 underline">Open Media Library</a>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="mt-1 block w-full border rounded p-2">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                <a href="{{ route('admin.news.index') }}" class="text-gray-600">Cancel</a>
            </div>
        </form>
    </div>
@endsection
