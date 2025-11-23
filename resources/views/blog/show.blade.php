@extends('layouts.app')

@section('title', $post->meta_title ?: $post->title)
@section('meta_description', $post->meta_description ?? '')

@section('content')
<section class="blog-post-hero" @if($post->featured_image) style="background-image: linear-gradient(135deg, rgba(15,23,42,.6), rgba(15,23,42,.85)), url('{{ Storage::url($post->featured_image) }}');" @endif>
    <div class="blog-post-hero-content">
        <p class="blog-post-date">{{ optional($post->published_at ?? $post->created_at)->format('F d, Y') }}</p>
        <h1 class="blog-post-title">{{ $post->title }}</h1>
        @if($post->excerpt)
            <p class="blog-post-subtitle">{{ $post->excerpt }}</p>
        @endif
    </div>
</section>

<div class="blog-post-container">
    <div class="blog-post-layout">
        <article class="blog-post-content-card">
            <div class="blog-post-body">
                {!! $post->content !!}
            </div>
        </article>

        <aside class="blog-post-sidebar">
            <div class="blog-sidebar-card">
                <h3>Share this article</h3>
                <div class="blog-share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener" aria-label="Share on Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2v-2.9h2v-2.2c0-2 1.2-3.1 3-3.1.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2v2h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/>
                        </svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener" aria-label="Share on X">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4.5 3h4.1l3.3 5.1L15.5 3h4l-5.8 7.5L20 21h-4.1l-3.7-5.8L8 21H4l6-7.9z"/>
                        </svg>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener" aria-label="Share on LinkedIn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4.98 3.5C4.98 4.88 3.88 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1 4.98 2.12 4.98 3.5zM.22 8.41H4.7V21H.22zM8.57 8.41h4.2v1.71h.06c.58-1.1 2-2.26 4.13-2.26 4.41 0 5.23 2.9 5.23 6.67V21H17.7v-5.5c0-1.31-.02-3-1.82-3-1.82 0-2.1 1.42-2.1 2.9V21H8.57z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="blog-sidebar-card">
                <h3>Recent articles</h3>
                <ul class="blog-recent-list">
                    @foreach($recentPosts as $recent)
                        <li>
                            <a href="{{ route('blog.show', $recent->slug) }}">{{ $recent->title }}</a>
                            <span>{{ optional($recent->published_at ?? $recent->created_at)->format('M d, Y') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>
</div>

<style>
.blog-post-hero {
    background: linear-gradient(135deg, #235181, #1a3d63);
    background-size: cover;
    background-position: center;
    padding: 100px 20px;
    color: white;
    text-align: center;
}

.blog-post-hero-content {
    max-width: 900px;
    margin: 0 auto;
}

.blog-post-date {
    text-transform: uppercase;
    letter-spacing: .35em;
    font-size: 12px;
    margin-bottom: 16px;
    opacity: .8;
}

.blog-post-title {
    font-size: clamp(36px, 5vw, 60px);
    margin: 0 0 16px;
    line-height: 1.2;
}

.blog-post-subtitle {
    font-size: 20px;
    margin: 0;
    opacity: .9;
}

.blog-post-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 60px 20px;
}

.blog-post-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
    align-items: start;
}

.blog-post-content-card {
    background: white;
    border-radius: 24px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
    padding: 48px;
}

.blog-post-body {
    color: #0f172a;
    line-height: 1.8;
    font-size: 18px;
}

.blog-post-body h2,
.blog-post-body h3 {
    margin-top: 32px;
    color: #1a3d63;
}

.blog-post-body p {
    margin-bottom: 18px;
}

.blog-post-body img {
    max-width: 100%;
    border-radius: 16px;
    margin: 24px 0;
}

.blog-post-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
    position: sticky;
    top: 30px;
}

.blog-sidebar-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
}

.blog-sidebar-card h3 {
    margin: 0 0 16px;
    color: #0f172a;
}

.blog-share-buttons a {
    width: 42px;
    height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    border: 1px solid #cbd5f5;
    margin-right: 10px;
    color: #1a3d63;
    text-decoration: none;
    transition: all .2s ease;
}

.blog-share-buttons a:hover {
    background: #1a3d63;
    color: #fff;
}

.blog-recent-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.blog-recent-list li a {
    color: #0f172a;
    font-weight: 600;
    text-decoration: none;
}

.blog-recent-list li span {
    display: block;
    font-size: 13px;
    color: #94a3b8;
    margin-top: 4px;
}

@media (max-width: 992px) {
    .blog-post-layout {
        grid-template-columns: 1fr;
    }

    .blog-post-sidebar {
        position: static;
    }
}

@media (max-width: 640px) {
    .blog-post-content-card {
        padding: 28px;
    }
}
</style>
@endsection

