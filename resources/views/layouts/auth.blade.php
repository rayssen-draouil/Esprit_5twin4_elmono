<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Connexion' }} · AquaSecure</title>
    @vite(['resources/css/app.css', 'resources/css/enhancements.css', 'resources/css/premium-overrides.css', 'resources/js/app.js'])
</head>
<body style="min-height:100vh;display:grid;place-items:center;background:#eaf6f3;padding:24px">
    <main class="panel" style="width:min(100%,460px);padding:36px">
        <a class="brand" href="{{ route('home') }}">
            <span class="brand-mark">A</span>
            <span>Aqua<span>Secure</span></span>
        </a>

        <h1 style="margin:28px 0 6px">{{ $heading ?? 'Bienvenue' }}</h1>
        <p style="color:var(--muted);margin-top:0">{{ $subtitle ?? '' }}</p>

        @if(session('success'))
            <p class="success-message">{{ session('success') }}</p>
        @endif

        @if($errors->any())
            <div class="error-message">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
