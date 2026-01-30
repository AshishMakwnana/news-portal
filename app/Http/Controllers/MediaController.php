<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::latest()->paginate(20);
        return view('admin.media.index', compact('media'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|file|image|max:5120',
        ]);

        $file = $request->file('upload');
        $path = $file->store('media', 'public');

        $media = Media::create([
            'user_id' => auth()->id(),
            'filename' => $path,
            'disk' => 'public',
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        // CKEditor expects a JSON response with 'url'
        return response()->json(['url' => asset('storage/' . $path)]);
    }

    public function destroy(Media $media)
    {
        // delete file from disk
        Storage::disk($media->disk)->delete($media->filename);
        $media->delete();

        return back()->with('success', 'Media deleted.');
    }
}
