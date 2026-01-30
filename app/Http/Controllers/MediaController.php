<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Modifiers\CoverModifier;

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

        // create thumbnail
        $thumbnailName = 'media/thumbnails/' . pathinfo($path, PATHINFO_BASENAME);
        $thumbnailFullPath = storage_path('app/public/' . $thumbnailName);

        // ensure thumbnails directory exists
        Storage::disk('public')->makeDirectory('media/thumbnails');

        $manager = ImageManager::gd();
        $image = $manager->read($file->getPathname());
        $image->modify(new CoverModifier(300, 200));

        // encode image and store via Storage so tests (Storage::fake) work correctly
        $encoded = $image->encodeByPath($thumbnailFullPath);
        Storage::disk('public')->put($thumbnailName, $encoded->toString());

        $media = Media::create([
            'user_id' => auth()->id(),
            'filename' => $path,
            'thumbnail' => $thumbnailName,
            'disk' => 'public',
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        // CKEditor expects a JSON response with 'url'
        return response()->json([
            'url' => asset('storage/' . $path),
            'thumbnail' => asset('storage/' . $thumbnailName),
        ]);
    }

    public function destroy(Media $media)
    {
        // delete files from disk
        Storage::disk($media->disk)->delete($media->filename);
        if ($media->thumbnail) {
            Storage::disk($media->disk)->delete($media->thumbnail);
        }
        $media->delete();

        return back()->with('success', 'Media deleted.');
    }

    public function list(Request $request)
    {
        $media = Media::latest()->paginate(20);
        $items = $media->getCollection()->transform(function ($m) {
            return [
                'id' => $m->id,
                'url' => $m->url,
                'thumbnail' => $m->thumbnail_url,
                'alt' => $m->alt,
            ];
        });

        return response()->json([
            'data' => $items,
            'current_page' => $media->currentPage(),
            'last_page' => $media->lastPage(),
            'per_page' => $media->perPage(),
            'total' => $media->total(),
        ]);
    }
}
