<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::published()->latest('published_at')->paginate(10);
        return view('public.news.index', compact('news'));
    }

    public function show($slug)
    {
        $item = News::where('slug', $slug)->published()->firstOrFail();
        return view('public.news.show', compact('item'));
    }
}
