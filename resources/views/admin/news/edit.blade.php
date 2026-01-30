@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-12">
        <h1 class="text-2xl font-bold mb-6">Edit News</h1>

        <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium">Title</label>
                <input type="text" name="title" value="{{ $news->title }}" class="mt-1 block w-full border rounded p-2"
                    required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Excerpt</label>
                <textarea name="excerpt" class="mt-1 block w-full border rounded p-2">{{ $news->excerpt }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Body</label>
                <textarea name="body" id="editor" rows="8" class="mt-1 block w-full border rounded p-2" required>{{ $news->body }}</textarea>
            </div>

            @push('scripts')
                <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        ClassicEditor.create(document.querySelector('#editor'), {
                            simpleUpload: {
                                uploadUrl: '{{ route('admin.media.upload') }}',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            }
                        }).then(editor => {
                            window.editor = editor;
                            window.insertImage = function(url){
                                editor.model.change( writer => {
                                    const image = writer.createElement('image', { src: url });
                                    editor.model.insertContent(image, editor.model.document.selection);
                                });
                            }
                        }).catch(e => console.error(e));
                    });
                </script>
            @endpush
            <div class="mb-4">
                <label class="block text-sm font-medium">Featured Image</label>
                <div class="flex items-center gap-4">
                    <input type="file" name="featured_image" class="mt-1">
                    <a href="{{ route('admin.media.index') }}" target="_blank" class="text-blue-600 underline">Open Media Library</a>
                </div
                @if ($news->featured_image)
                    <div class="mt-2"><img src="{{ asset('storage/' . $news->featured_image) }}" class="w-48 rounded">
                    </div>
                @endif
            </div>
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Category</label>
                    <select name="category_id" class="mt-1 block w-full border rounded p-2">
                        <option value="">— Select —</option>
                        @foreach(App\Models\Category::all() as $cat)
                            <option value="{{ $cat->id }}" {{ $news->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Tags</label>
                    <select name="tags[]" multiple class="mt-1 block w-full border rounded p-2">
                        @foreach(App\Models\Tag::all() as $tag)
                            <option value="{{ $tag->id }}" {{ $news->tags->contains($tag->id) ? 'selected' : '' }}>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="mt-1 block w-full border rounded p-2">
                    <option value="draft" {{ $news->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ $news->status == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ $news->status == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                <a href="{{ route('admin.news.index') }}" class="text-gray-600">Cancel</a>
            </div>
        </form>
    </div>
@endsection
