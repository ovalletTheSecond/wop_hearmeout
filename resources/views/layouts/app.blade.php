<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hear Me Out')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        header nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 1.5rem;
        }

        header h1 a {
            color: white;
            text-decoration: none;
        }

        header ul {
            list-style: none;
            display: flex;
            gap: 1.5rem;
        }

        header a {
            color: white;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        header a:hover {
            opacity: 0.8;
        }

        main {
            flex: 1;
            max-width: 1200px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        footer {
            background: #34495e;
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: auto;
        }

        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #2980b9;
        }

        .btn-danger {
            background: #e74c3c;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-success {
            background: #2ecc71;
        }

        .btn-success:hover {
            background: #27ae60;
        }

        .btn-secondary {
            background: #95a5a6;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .btn-google {
            background: #db4437;
        }

        .btn-google:hover {
            background: #c23321;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        input[type="file"] {
            margin-top: 0.5rem;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .notification {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 0.5rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification.unread {
            background: #cfe2ff;
            border-color: #0d6efd;
        }
    </style>
    @stack('styles')
</head>
<body>
    <header>
        <nav>
            <h1><a href="/">Hear Me Out</a></h1>
            <ul>
                @auth
                    <li><a href="{{ route('account') }}">Mon Crush</a></li>
                    <li><a href="{{ route('logout') }}">Déconnexion</a></li>
                @else
                    <li><a href="{{ route('login') }}">Connexion</a></li>
                @endauth
            </ul>
        </nav>
    </header>

    <main>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <footer>
        <p>&copy; 2025 Hear Me Out. Tous droits réservés.</p>
    </footer>

    @stack('scripts')
</body>
</html>
