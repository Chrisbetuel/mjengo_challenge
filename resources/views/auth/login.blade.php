@extends('layouts.app')

@section('title', 'Login')

@section('content')
<!-- Slideshow Background -->
<div class="slideshow-background">
    <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1600585154340-ffff5c5d0e0e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2073&q=80');"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1571080648414-c6b36f3a0139?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80');"></div>
    <div class="overlay"></div>
</div>

<div class="container py-5 position-relative">
    <!-- Header with Oweru Logo -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <div class="oweru-logo">
                <span class="oweru-o">O</span>
                <span class="oweru-text">weru</span>
            </div>
            <p class="text-light mt-2 fs-5">Smart Investments. Safe Returns.</p>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="row align-items-center min-vh-100">
        <div class="col-lg-6">
            <div class="hero-content">
                <h1 class="display-3 fw-bold text-white mb-3 futura-font">Welcome Back</h1>
                <p class="lead mb-4 text-white fs-2 fw-light poppins-font">Continue Your Construction Journey</p>
                <p class="text-light mb-4 fs-5 poppins-font">Sign in to access your account and continue building your dream.</p>

                <div class="mt-4">
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg shadow-sm hover-grow">
                        <i class="fas fa-user-plus me-2"></i>Don't have an account? Register
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="content-card bg-oweru-light rounded-4 p-5 shadow-lg">
                <h3 class="text-center text-oweru-dark mb-4 futura-font">Sign In</h3>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="username" class="block text-oweru-dark text-sm font-bold mb-2 futura-font">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}"
                               class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-oweru-gold"
                               required autofocus>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-oweru-dark text-sm font-bold mb-2 futura-font">Password</label>
                        <input type="password" name="password" id="password"
                               class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-oweru-gold"
                               required>
                    </div>

                    <div class="flex items-center justify-center mb-4">
                        <button type="submit" class="btn btn-oweru-gold btn-lg text-oweru-dark fw-bold shadow glow-button">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </div>

                    <div class="text-center">
                        <a href="{{ url('/forgot-password') }}" class="text-sm text-oweru-blue hover:text-oweru-gold futura-font">
                            Forgot your password?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Oweru Brand Colors */
:root {
    --oweru-dark: #09172A;
    --oweru-gold: #C89128;
    --oweru-light: #F8F8F9;
    --oweru-blue: #2D3A58;
    --oweru-secondary: #E5B972;
    --oweru-gray: #889898;
}

.bg-oweru-dark { background-color: var(--oweru-dark) !important; }
.bg-oweru-gold { background-color: var(--oweru-gold) !important; }
.bg-oweru-light { background-color: var(--oweru-light) !important; }
.bg-oweru-blue { background-color: var(--oweru-blue) !important; }
.bg-oweru-secondary { background-color: var(--oweru-secondary) !important; }

.text-oweru-dark { color: var(--oweru-dark) !important; }
.text-oweru-gold { color: var(--oweru-gold) !important; }
.text-oweru-light { color: var(--oweru-light) !important; }
.text-oweru-blue { color: var(--oweru-blue) !important; }
.text-oweru-secondary { color: var(--oweru-secondary) !important; }
.text-oweru-gray { color: var(--oweru-gray) !important; }

.btn-oweru-gold {
    background-color: var(--oweru-gold);
    border-color: var(--oweru-gold);
    color: var(--oweru-dark);
    font-weight: 600;
}

.btn-oweru-gold:hover {
    background-color: #b58120;
    border-color: #b58120;
    color: var(--oweru-dark);
}

/* Oweru Logo Styling */
.oweru-logo {
    font-family: 'Futura PT', sans-serif;
    font-size: 3rem;
    font-weight: 700;
    display: inline-block;
}

.oweru-o {
    color: var(--oweru-gold);
    font-size: 3.5rem;
}

.oweru-text {
    color: var(--oweru-light);
    font-weight: 600;
}

/* Typography */
.futura-font {
    font-family: 'Futura PT', Arial, sans-serif;
}

.poppins-font {
    font-family: 'Poppins', Arial, sans-serif;
}

/* Slideshow Background */
.slideshow-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
}

.slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0;
    transition: opacity 1.5s ease-in-out;
}

.slide.active {
    opacity: 1;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(9, 23, 42, 0.85) 0%, rgba(45, 58, 88, 0.8) 100%);
}

.min-vh-100 {
    min-height: 100vh;
}

.content-card {
    backdrop-filter: blur(10px);
    background: rgba(248, 248, 249, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.glow-button {
    position: relative;
    overflow: hidden;
}

.glow-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.glow-button:hover::before {
    left: 100%;
}

.hover-grow {
    transition: all 0.3s ease;
}

.hover-grow:hover {
    transform: scale(1.05);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .display-3 {
        font-size: 2.5rem;
    }

    .lead.fs-2 {
        font-size: 1.5rem !important;
    }

    .content-card {
        margin-top: 2rem;
    }

    .oweru-logo {
        font-size: 2rem;
    }

    .oweru-o {
        font-size: 2.5rem;
    }
}

/* Import Fonts */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
/* Futura PT is a premium font - using Arial as fallback */
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Slideshow functionality
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;

    function nextSlide() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }

    // Change slide every 5 seconds
    setInterval(nextSlide, 5000);

    // Add scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe content card
    const contentCard = document.querySelector('.content-card');
    if (contentCard) {
        contentCard.style.opacity = '0';
        contentCard.style.transform = 'translateX(30px)';
        contentCard.style.transition = 'all 0.8s ease';
        observer.observe(contentCard);
    }
});
</script>
@endsection
