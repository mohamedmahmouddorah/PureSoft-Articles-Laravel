<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Artical System')</title>
    <!-- Bootstrap 5 CSS (LTR) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        .reply-form { display: none; margin-top: 15px; animation: fadeIn 0.3s; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('articles.index') }}"><i class="bi bi-house-door-fill"></i></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto align-items-center">
                @auth
                    <span class="nav-link text-info">Welcome, <a href="{{ route('users.show', Auth::user()) }}" class="text-info text-decoration-none fw-bold">{{ Auth::user()->username }}</a></span>
                    <a href="{{ route('articles.create') }}" class="nav-link">Create Article</a>
                    <a href="{{ route('users.index') }}" class="nav-link">Users</a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm ms-3 glow-on-hover">Logout</button>
                    </form>
                @else
                    <a href="{{ route('users.index') }}" class="nav-link">Users</a>
                    <a href="{{ route('login') }}" class="btn btn-info btn-sm ms-3 glow-on-hover">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm ms-2 glow-on-hover">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<div style="height: 80px;"></div> <!-- Spacer for fixed navbar -->

<div class="container flex-grow-1">
    @if(session('success'))
        <div class="alert alert-success text-center mt-3 alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger text-center mt-3 alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @yield('content')
</div>

@stack('scripts')

</script>
</body>
</html>
