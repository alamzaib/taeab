@extends('layouts.app')

@section('title', 'Blog - Job Portal UAE')

@section('content')
<section class="blog-hero">
    <div class="blog-hero-content">
        <p class="blog-hero-eyebrow">Insights & Stories</p>
        <h1 class="blog-hero-title">From Our Recruiting Desk</h1>
        <p class="blog-hero-subtitle">Tips, trends, and success stories to help you navigate the UAE job market.</p>
    </div>
</section>

<div class="blog-container">
    <div class="blog-grid">
        @forelse($posts as $post)
            <article class="blog-card">
                @if($post->featured_image)
                    <div class="blog-card-image">
                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}">
                    </div>
                @endif
                <div class="blog-card-body">
                    <p class="blog-card-date">
                        {{ optional($post->published_at ?? $post->created_at)->format('M d, Y') }}
                    </p>
                    <h2 class="blog-card-title">
                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                    </h2>
                    <p class="blog-card-excerpt">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="blog-card-link">
                        Read Article
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
            </article>
        @empty
            <div class="blog-empty">
                <h3>No blog posts yet</h3>
                <p>Check back soon for the latest insights and updates.</p>
            </div>
        @endforelse
    </div>

    <div class="blog-pagination">
        {{ $posts->links() }}
    </div>
</div>

<style>
.blog-hero {
    background: linear-gradient(135deg, #235181 0%, #1a3d63 100%);
    padding: 80px 20px;
    text-align: center;
    color: white;
}

.blog-hero-content {
    max-width: 900px;
    margin: 0 auto;
}

.blog-hero-eyebrow {
    text-transform: uppercase;
    letter-spacing: .4em;
    font-size: 12px;
    opacity: .7;
    margin-bottom: 12px;
}

.blog-hero-title {
    font-size: clamp(36px, 5vw, 52px);
    margin: 0 0 16px;
    font-weight: 700;
}

.blog-hero-subtitle {
    font-size: 18px;
    margin: 0;
    opacity: .9;
}

.blog-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 60px 20px;
}

.blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}

.blog-card {
    background: white;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(15, 23, 42, 0.1);
    display: flex;
    flex-direction: column;
}

.blog-card-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.blog-card-body {
    padding: 24px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.blog-card-date {
    font-size: 13px;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: #94a3b8;
    margin: 0 0 10px;
}

.blog-card-title {
    font-size: 22px;
    margin: 0 0 12px;
    color: #0f172a;
}

.blog-card-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s ease;
}

.blog-card-title a:hover {
    color: #235181;
}

.blog-card-excerpt {
    color: #475569;
    flex: 1;
    margin: 0 0 20px;
}

.blog-card-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #235181;
    text-decoration: none;
    font-weight: 600;
}

.blog-card-link svg {
    transition: transform 0.2s ease;
}

.blog-card-link:hover svg {
    transform: translateX(4px);
}

.blog-empty {
    text-align: center;
    padding: 60px;
    background: #f8fafc;
    border-radius: 20px;
    grid-column: 1 / -1;
}

.blog-pagination {
    margin-top: 40px;
}

@media (max-width: 640px) {
    .blog-card-image img {
        height: 160px;
    }
}
</style>
@endsection

