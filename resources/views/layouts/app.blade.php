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
    @endphp
    
    <title>@yield('title', $metaTitle)</title>
    
    @if(!empty($metaDescription))
    <meta name="description" content="{{ $metaDescription }}">
    @endif
    
    @if(!empty($metaKeywords))
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
</html>

