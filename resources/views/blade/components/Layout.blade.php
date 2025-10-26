<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Larafony Notes Pro+' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 2rem;
            font-size: 2.5rem;
        }
        form {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        input, textarea, button {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        button {
            background: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }
        button:hover {
            background: #2980b9;
        }
        article {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        article h2 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        article p {
            margin-bottom: 1rem;
            color: #555;
        }
        small {
            color: #7f8c8d;
        }
        .tags {
            margin: 1rem 0;
        }
        .tag {
            display: inline-block;
            background: #ecf0f1;
            padding: 0.3rem 0.8rem;
            margin-right: 0.5rem;
            border-radius: 20px;
            font-size: 0.9rem;
            color: #555;
        }
        details {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #ecf0f1;
        }
        summary {
            cursor: pointer;
            font-weight: 600;
            color: #3498db;
            margin-bottom: 1rem;
        }
        .comment {
            background: #f8f9fa;
            padding: 0.8rem;
            margin-bottom: 0.8rem;
            border-radius: 4px;
        }
        .comment b {
            color: #2c3e50;
        }
        hr {
            margin: 2rem 0;
            border: none;
            border-top: 2px solid #ecf0f1;
        }
        .alert {
            padding: 1.25rem;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            margin-bottom: 1.25rem;
            border-radius: 4px;
        }
        .notes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.25rem;
        }
        .note-card {
            background: white;
            padding: 1.25rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .note-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0.625rem;
        }
        .note-content {
            color: #555;
            line-height: 1.6;
            margin-bottom: 0.9375rem;
        }
        .note-meta {
            font-size: 0.875rem;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 0.625rem;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 1.25rem;
            color: #2196f3;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        {!! $slot !!}
    </div>
</body>
</html>
