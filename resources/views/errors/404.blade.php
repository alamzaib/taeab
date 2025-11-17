<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #1f3351;
        }
        .error-wrapper {
            max-width: 520px;
            padding: 40px 30px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        }
        .error-wrapper img {
            max-width: 280px;
            height: auto;
            margin-bottom: 20px;
        }
        .error-wrapper h1 {
            font-size: 36px;
            margin: 10px 0;
        }
        .error-wrapper p {
            font-size: 16px;
            color: #5f6c84;
        }
        .btn-primary {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #235181;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #1a3d63;
        }
    </style>
</head>
<body>
    <div class="error-wrapper">
        <img src="{{ asset('images/404-illustration.svg') }}" alt="Not found illustration">
        <h1>Oops! Page not found</h1>
        <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
        <a href="{{ url('/') }}" class="btn-primary">Back to Home</a>
    </div>
</body>
</html>

