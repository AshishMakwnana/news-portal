@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-12">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Media Library</h1>
    </div>

    <div class="bg-white shadow rounded p-6">
        <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="upload" accept="image/*" required>
            <button class="ml-2 bg-blue-600 text-white px-3 py-1 rounded">Upload</button>
        </form>

        <div class="mt-6 grid grid-cols-4 gap-4">
            @foreach($media as $m)
                <div class="border rounded p-2">
                    <img src="{{ $m->url }}" class="w-full h-32 object-cover rounded">
                    <div class="mt-2 flex items-center justify-between">
                        <div class="text-xs text-gray-500">{{ $m->mime_type }}</div>
                        <form action="{{ route('admin.media.destroy', $m) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="text-sm text-red-600">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $media->links() }}</div>
    </div>
</div>
@endsection