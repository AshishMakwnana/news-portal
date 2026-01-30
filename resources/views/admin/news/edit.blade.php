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
                            window.insertImage = function(url) {
                                editor.model.change(writer => {
                                    const image = writer.createElement('image', {
                                        src: url
                                    });
                                    editor.model.insertContent(image, editor.model.document.selection);
                                });
                            }
                        }).catch(e => console.error(e));
                    });
                </script>

                <script>
                    function openMediaModal() {
                        document.getElementById('media-modal').classList.remove('hidden');
                        fetchMedia(1);
                    }

                    function closeMediaModal() {
                        document.getElementById('media-modal').classList.add('hidden');
                    }

                    function fetchMedia(page = 1) {
                        fetch("{{ route('admin.media.list') }}?page=" + page)
                            .then(res => res.json())
                            .then(data => {
                                const list = document.getElementById('media-list');
                                list.innerHTML = '';
                                data.data.forEach(m => {
                                    const el = document.createElement('div');
                                    el.className = 'border p-2 text-center';
                                    el.innerHTML = `
                                        <img src="${m.thumbnail}" class="w-full h-24 object-cover mb-2">
                                        <div class="flex gap-2 justify-center">
                                            <button type="button" onclick="insertImageToEditor('${m.url}')" class="bg-blue-600 text-white px-2 py-1 rounded">Insert</button>
                                            <button type="button" onclick="copyUrl('${m.url}')" class="border px-2 py-1 rounded">Copy URL</button>
                                        </div>
                                    `;
                                    list.appendChild(el);
                                });

                                const pag = document.getElementById('media-pagination');
                                pag.innerHTML = '';
                                for (let i = 1; i <= data.last_page; i++) {
                                    const btn = document.createElement('button');
                                    btn.innerText = i;
                                    btn.className = 'px-2 py-1 border rounded mr-2';
                                    if (i === data.current_page) btn.classList.add('bg-gray-200');
                                    btn.onclick = () => fetchMedia(i);
                                    pag.appendChild(btn);
                                }
                            });
                    }

                    function insertImageToEditor(url) {
                        if (!window.editor) return alert('Editor not ready');
                        editor.model.change(writer => {
                            const image = writer.createElement('image', {
                                src: url
                            });
                            editor.model.insertContent(image, editor.model.document.selection);
                        });
                        closeMediaModal();
                    }

                    function copyUrl(url) {
                        navigator.clipboard.writeText(url).then(() => alert('Copied to clipboard'));
                    }
                </script>
            @endpush
            <div class="mb-4">
                <label class="block text-sm font-medium">Featured Image</label>
                <div class="flex items-center gap-4">
                    <input type="file" name="featured_image" class="mt-1">
                    <button type="button" onclick="openMediaModal()" class="text-blue-600 underline">Open Media
                        Library</button>
                </div>
                @if ($news->featured_image)
                    <div class="mt-2"><img src="{{ asset('storage/' . $news->featured_image) }}" class="w-48 rounded">
                    </div>
                @endif

                <!-- Media picker modal -->
                <div id="media-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
                    <div class="bg-white w-3/4 max-w-4xl p-4 rounded shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Media Library</h3>
                            <button type="button" onclick="closeMediaModal()" class="text-gray-600">Close</button>
                        </div>
                        <div id="media-list" class="grid grid-cols-4 gap-4"></div>
                        <div id="media-pagination" class="mt-4"></div>
                    </div>
                </div>
            </div>
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Category</label>
                    <select name="category_id" class="mt-1 block w-full border rounded p-2">
                        <option value="">— Select —</option>
                        @foreach (App\Models\Category::all() as $cat)
                            <option value="{{ $cat->id }}" {{ $news->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Tags</label>
                    <select name="tags[]" multiple class="mt-1 block w-full border rounded p-2">
                        @foreach (App\Models\Tag::all() as $tag)
                            <option value="{{ $tag->id }}" {{ $news->tags->contains($tag->id) ? 'selected' : '' }}>
                                {{ $tag->name }}</option>
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
