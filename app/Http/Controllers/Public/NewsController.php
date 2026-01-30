<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::published();

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->get('category'));
            });
        }

        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->get('tag'));
            });
        }

        $news = $query->latest('published_at')->paginate(10);
        return view('public.news.index', compact('news'));
    }

    public function show($slug)
    {
        $item = News::where('slug', $slug)->published()->firstOrFail();
        return view('public.news.show', compact('item'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $news = News::published()->where('category_id', $category->id)->latest('published_at')->paginate(10);
        return view('public.news.index', compact('news'))->with('title', 'Category: ' . $category->name);
    }

    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        $news = News::published()->whereHas('tags', function ($q) use ($tag) {
            $q->where('tags.id', $tag->id);
        })->latest('published_at')->paginate(10);
        return view('public.news.index', compact('news'))->with('title', 'Tag: ' . $tag->name);
    }
}
