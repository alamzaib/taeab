<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $settings = \App\Models\Setting::getAll();
        $metaTitle = !empty($settings['meta_title']) ? $settings['meta_title'] : 'Job Portal UAE';
        $metaDescription = $settings['meta_description'] ?? '';
        $metaKeywords = $settings['meta_keywords'] ?? '';
        $metaDescriptionSection = trim($__env->yieldContent('meta_description'));
        $metaKeywordsSection = trim($__env->yieldContent('meta_keywords'));
    @endphp
    
    <title>@yield('title', $metaTitle)</title>
    
    @if(!empty($metaDescriptionSection))
    <meta name="description" content="{{ $metaDescriptionSection }}">
    @elseif(!empty($metaDescription))
    <meta name="description" content="{{ $metaDescription }}">
    @endif
    
    @if(!empty($metaKeywordsSection))
    <meta name="keywords" content="{{ $metaKeywordsSection }}">
    @elseif(!empty($metaKeywords))
    <meta name="keywords" content="{{ $metaKeywords }}">
    @endif
    
    @if(!empty($settings['favicon']))
    <link rel="icon" type="image/png" href="{{ Storage::url($settings['favicon']) }}">
    @else
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    @endif
    
    @if(!empty($settings['google_analytics_code']))
    {!! $settings['google_analytics_code'] !!}
    @endif
    
    @if(!empty($settings['custom_css']))
    {!! $settings['custom_css'] !!}
    @endif
    
    @if(!empty($settings['custom_javascript']))
    {!! $settings['custom_javascript'] !!}
    @endif
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .primary-color {
            background-color: #235181;
            color: white;
        }
        .primary-text {
            color: #235181;
        }
        .btn-primary {
            background-color: #235181;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #1a3d63;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 30px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        table th {
            text-align: left;
            color: #235181;
            font-weight: 600;
        }
        .pagination-wrapper {
            margin-top: 15px;
            display: flex;
            justify-content: center;
        }
        .pagination {
            display: flex;
            gap: 8px;
            list-style: none;
            padding-left: 0;
            margin: 0;
        }
        .pagination li {
            border-radius: 999px;
            overflow: hidden;
        }
        .pagination li a,
        .pagination li span {
            display: inline-block;
            padding: 8px 14px;
            border: 1px solid #e5e7eb;
            text-decoration: none;
            color: #1f2937;
            font-size: 14px;
            background: white;
        }
        .pagination li.active span {
            background-color: #235181;
            color: white;
            border-color: #235181;
        }
        .pagination li a:hover {
            background-color: #edf2f7;
        }
        .pagination li.disabled span {
            color: #9ca3af;
            background-color: #f3f4f6;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }
        .auth-wrapper {
            min-height: calc(100vh - 160px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            background: linear-gradient(135deg, rgba(35,81,129,0.08), rgba(35,81,129,0.02));
        }
        .auth-card {
            width: 100%;
            max-width: 900px;
            background: white;
            border-radius: 18px;
            box-shadow: 0 20px 50px rgba(15,23,42,0.08);
            overflow: hidden;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }
        .auth-panel {
            padding: 40px;
        }
        .auth-panel h2 {
            font-size: 28px;
            margin-bottom: 8px;
        }
        .auth-panel p.subtext {
            color: #6b7280;
            margin-bottom: 30px;
        }
        .auth-side {
            background: linear-gradient(135deg, #235181, #1a3d63);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 18px;
        }
        .auth-side h3 {
            font-size: 26px;
            margin: 0;
        }
        .auth-input {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 18px;
        }
        .auth-input label {
            font-weight: 600;
            color: #1f2937;
        }
        .auth-input input {
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            font-size: 15px;
        }
        .auth-dual {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
        }
        .auth-extra {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .auth-extra a {
            color: #235181;
            text-decoration: none;
            font-size: 14px;
        }
        .auth-extra a:hover {
            text-decoration: underline;
        }
        .social-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 12px;
            text-decoration: none;
            color: #1f2937;
            font-weight: 600;
            margin-bottom: 18px;
        }
        .social-button img {
            width: 20px;
            height: 20px;
        }
        @media (max-width: 768px) {
            .auth-panel, .auth-side {
                padding: 25px;
            }
            .auth-card {
                grid-template-columns: 1fr;
            }
        }
        .page-content {
            line-height: 1.7;
            color: #333;
        }
        .page-content img {
            max-width: 100%;
            height: auto;
        }
        .page-content h2,
        .page-content h3,
        .page-content h4 {
            color: #235181;
            margin-top: 20px;
        }
        .navbar {
            background-color: #235181;
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        .navbar-nav {
            display: flex;
            gap: 20px;
            list-style: none;
        }
        .navbar-nav a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        .navbar-nav a:hover {
            opacity: 0.8;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .form-row .form-group {
            flex: 1;
            min-width: 200px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-control:focus {
            outline: none;
            border-color: #235181;
            box-shadow: 0 0 0 2px rgba(35, 81, 129, 0.2);
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 12px;
            border-radius: 999px;
            color: white;
        }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-secondary { background-color: #6c757d; }
    </style>
    @stack('styles')
</head>
<body>
    @include('components.header')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    @stack('scripts')
</body>
        <button id="backToTopBtn" style="position: fixed; bottom: 30px; right: 30px; display: none; background-color: #235181; color: white; border: none; border-radius: 50%; width: 50px; height: 50px; font-size: 20px; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">↑</button>
        <script>
            const backToTopBtn = document.getElementById('backToTopBtn');
            window.addEventListener('scroll', () => {
                if (document.body.scrollTop > 400 || document.documentElement.scrollTop > 400) {
                    backToTopBtn.style.display = 'block';
                } else {
                    backToTopBtn.style.display = 'none';
                }
            });
            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        </script>
</html>

