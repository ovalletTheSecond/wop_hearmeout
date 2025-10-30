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

        /* Dropdown styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            cursor: pointer;
            padding: 0.5rem 1rem;
            background: transparent;
            border: none;
            color: white;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropdown-toggle:hover {
            opacity: 0.8;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 200px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
            padding: 0.5rem 0;
            z-index: 1000;
            margin-top: 0.5rem;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-menu a {
            display: block;
            padding: 0.75rem 1rem;
            color: #2c3e50;
            text-decoration: none;
            transition: background 0.2s;
        }

        .dropdown-menu a:hover {
            background: #f8f9fa;
            opacity: 1;
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
                <li class="dropdown">
                    <button class="dropdown-toggle" onclick="toggleDropdown()">
                        Catégories ▾
                    </button>
                    <div class="dropdown-menu" id="categoryDropdown">
                        @php
                            $categories = \App\Models\Category::orderBy('name')->get();
                        @endphp
                        <a href="{{ route('home') }}">
                            Toutes les catégories
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('home', ['category' => $category->slug]) }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </li>
                @auth
                    <li><a href="{{ route('account') }}">Mon Crush</a></li>
                    <li><a href="{{ route('logout') }}">Déconnexion</a></li>
                @else
                    <li><a href="{{ route('login') }}">Connexion</a></li>
                @endauth
            </ul>
        </nav>
    </header>

    <script>
        function toggleDropdown() {
            document.getElementById('categoryDropdown').classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-toggle')) {
                var dropdowns = document.getElementsByClassName('dropdown-menu');
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>

    <main>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <footer style="background: #f8f9fa; padding: 2rem 0; margin-top: auto;">
        <div style="max-width: 800px; margin: 0 auto; padding: 0 1rem; text-align: center;">
            <div style="margin-bottom: 2rem;">
                <p style="color: #666; line-height: 1.6; margin-bottom: 1rem;">
                    S'il vous plaît, utilisez ce site de manière respectueuse. La création de multiples comptes dans le but de DDOS
                    entraînera la fermeture du site. Si vous souhaitez refaire un site similaire, faites-le, mais évitez
                    de compromettre cette plateforme pour tous.
                </p>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <a href="{{ route('support') }}"  style="display: inline-block; padding: 0.5rem 1rem;  color: black; text-decoration: underline; border-radius: 4px;">
                    Me soutenir 
                </a>
            </div>

            <div style="margin-bottom: 1rem;">
                <a href="mailto:oli.vallet0+hearmeout@gmail.com?subject=Demande%20de%20suppression%20RGPD" style="color: #666; text-decoration: underline;">
                    RGPD - Demander la suppression de mon compte
                </a>
            </div>
            
            <p style="color: #666;">&copy; 2025 Hear Me Out. Tous droits réservés.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
