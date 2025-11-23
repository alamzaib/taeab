<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::published()
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();
        $recentPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

        return view('blog.show', compact('post', 'recentPosts'));
    }
}
