<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mjengo Challenge - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
            color: #2c3e50;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #2c3e50;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 0.75rem 1rem;
        }
        .sidebar .nav-link:hover {
            background-color: #34495e;
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: #3498db;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-hard-hat"></i> Mjengo Challenge
            </a>
            @auth
            <div class="navbar-nav ms-auto d-flex align-items-center">
                <span class="navbar-text me-3">
                    Welcome, {{ Auth::user()->username }}
                </span>
                <form method="POST" action="{{ route('lang.switch') }}" class="d-inline me-3" id="lang-switch-form">
                    @csrf
                    <select name="locale" onchange="document.getElementById('lang-switch-form').submit()" class="form-select form-select-sm">
                        <option value="en" {{ session('locale', app()->getLocale()) == 'en' ? 'selected' : '' }}>English</option>
                        <option value="sw" {{ session('locale') == 'sw' ? 'selected' : '' }}>Swahili</option>
                    </select>
                </form>
                <form method="POST" action="{{ route('logout') }}" class="d-inline" id="logout-form">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </nav>

    @auth
    <div class="container-fluid">
        <div class="row">
            @if(! View::hasSection('no_sidebar') || strtolower(View::getSection('no_sidebar')) !== 'true')
                <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="position-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                   href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('challenges.*') ? 'active' : '' }}"
                                   href="{{ route('challenges.index') }}">
                                    <i class="fas fa-trophy me-2"></i> Challenges
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('materials.*') ? 'active' : '' }}"
                                   href="{{ route('materials.index') }}">
                                    <i class="fas fa-bricks me-2"></i> Materials
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('groups.*') ? 'active' : '' }}"
                                   href="{{ route('groups.index') }}">
                                    <i class="fas fa-users me-2"></i> Groups
                                </a>
                            </li>
                            @if(Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}"
                                   href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-cog me-2"></i> Admin Panel
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </nav>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    @yield('content')
                </main>
            @else
                <main class="col-12 px-md-4">
                    @yield('content')
                </main>
            @endif
        </div>
    </div>
    @else
    <main>
        @yield('content')
    </main>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
