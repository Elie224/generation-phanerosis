<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow p-4 mb-8">
        <div class="container mx-auto flex justify-between">
            <a href="{{ url('/') }}" class="text-lg font-bold">{{ config('app.name') }}</a>
            @auth
                <span>{{ Auth::user()->name }}</span>
                <a href="{{ route('profile.show', Auth::user()->name) }}">Mon profil</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit">Déconnexion</button>
                </form>
            @endauth
            @guest
                <a href="{{ route('login') }}">Connexion</a>
                <a href="{{ route('register') }}">Inscription</a>
            @endguest
        </div>
    </nav>
    <main class="container mx-auto">
        @yield('content')
    </main>
</body>
</html>
