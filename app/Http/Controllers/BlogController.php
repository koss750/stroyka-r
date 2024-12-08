<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(100);

        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Ensure tags is an array
        if (is_string($post->tags)) {
            $post->tags = explode(',', $post->tags);
        } elseif (!is_array($post->tags)) {
            $post->tags = [];
        }

        return view('blog.show', compact('post'));
    }
}
