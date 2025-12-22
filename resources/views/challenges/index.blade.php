@extends('layouts.app')

@section('title', 'Challenges')

@section('content')
<!-- Remove sidebar by ensuring no sidebar content is included -->
<style>
    /* Hide sidebar if it exists in layout */
    .sidebar,
    [class*="sidebar"],
    .col-md-3,
    .col-lg-2,
    .navbar-vertical {
        display: none !important;
    }

    /* Adjust main content to take full width */
    .main-content,
    .col-md-9,
    .col-lg-10,
    .content-wrapper {
        width: 100% !important;
        margin-left: 0 !important;
        padding-left: 0 !important;
    }
</style>

<!-- Slideshow Background -->
<div class="slideshow-background">
    <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1600585154340-ffff5c5d0e0e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2073&q=80');"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1571080648414-c6b36f3a0139?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80');"></div>
    <div class="overlay"></div>
</div>

<div class="container-fluid py-4 position-relative min-vh-100">
    <!-- Oweru Header -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="oweru-logo mb-2">
                <span class="oweru-o">O</span>
                <span class="oweru-text">weru</span>
            </div>
            <p class="text-light mb-0 futura-font">Smart Investments. Safe Returns.</p>
        </div>
    </div>

    <!-- Navigation Cards -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="navigation-cards">
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('dashboard') }}" class="nav-card bg-oweru-blue text-white">
                        <i class="fas fa-chart-line fa-lg mb-2"></i>
                        <span class="futura-font fw-bold">Dashboard</span>
                    </a>
                    <a href="{{ route('challenges.index') }}" class="nav-card bg-oweru-gold text-oweru-dark">
                        <i class="fas fa-trophy fa-lg mb-2"></i>
                        <span class="futura-font fw-bold">Challenges</span>
                    </a>
                    <a href="{{ route('materials.index') }}" class="nav-card bg-oweru-secondary text-oweru-dark">
                        <i class="fas fa-shopping-cart fa-lg mb-2"></i>
                        <span class="futura-font fw-bold">Materials</span>
                    </a>
                    <a href="{{ route('groups.index') }}" class="nav-card bg-oweru-dark text-white">
                        <i class="fas fa-users fa-lg mb-2"></i>
                        <span class="futura-font fw-bold">Groups</span>
                    </a>
                    @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-card bg-oweru-gray text-white">
                        <i class="fas fa-cog fa-lg mb-2"></i>
                        <span class="futura-font fw-bold">Admin Panel</span>
                    </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-card bg-danger text-white border-0">
                            <i class="fas fa-sign-out-alt fa-lg mb-2"></i>
                            <span class="futura-font fw-bold">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="row g-4">
        <!-- Total Challenges Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dashboard-card bg-oweru-gold text-oweru-dark text-center">
                <div class="card-icon mb-3">
                    <i class="fas fa-trophy fa-2x"></i>
                </div>
                <h3 class="futura-font fw-bold mb-2">Total Challenges</h3>
                <p class="display-6 fw-bold mb-0">{{ $challenges->count() }}</p>
            </div>
        </div>

        <!-- My Active Challenges Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dashboard-card bg-oweru-blue text-white text-center">
                <div class="card-icon mb-3">
                    <i class="fas fa-star fa-2x"></i>
                </div>
                <h3 class="futura-font fw-bold mb-2">My Challenges</h3>
                <p class="display-6 fw-bold mb-0">{{ $userChallenges->count() }}</p>
            </div>
        </div>

        <!-- Total Participants Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dashboard-card bg-oweru-secondary text-oweru-dark text-center">
                <div class="card-icon mb-3">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <h3 class="futura-font fw-bold mb-2">Total Participants</h3>
                <p class="display-6 fw-bold mb-0">{{ $challenges->sum('active_participants_count') }}</p>
            </div>
        </div>

        <!-- Available Slots Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dashboard-card bg-oweru-dark text-white text-center">
                <div class="card-icon mb-3">
                    <i class="fas fa-plus-circle fa-2x"></i>
                </div>
                <h3 class="futura-font fw-bold mb-2">Available Slots</h3>
                <p class="display-6 fw-bold mb-0">{{ $challenges->sum(function($challenge) { return $challenge->max_participants - $challenge->active_participants_count; }) }}</p>
            </div>
        </div>

        <!-- My Active Challenges Section -->
        @if($userChallenges->count() > 0)
        <div class="col-12 col-lg-6">
            <div class="dashboard-section">
                <div class="section-header d-flex justify-content-between align-items-center mb-4">
                    <h3 class="futura-font fw-bold text-white mb-0">
                        <i class="fas fa-star me-2"></i>My Active Challenges
                    </h3>
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-oweru-gold futura-font">View Dashboard</a>
                </div>

                <div class="content-cards">
                    @foreach($userChallenges as $participant)
                        <div class="content-card">
                            <div class="card-header d-flex justify-content-between align-items-start mb-2">
                                <h5 class="futura-font fw-bold text-oweru-dark mb-0">{{ $participant->challenge->name }}</h5>
                                <span class="badge bg-oweru-gold text-oweru-dark futura-font">{{ ucfirst($participant->status) }}</span>
                            </div>
                            <p class="text-oweru-gray poppins-font mb-2">{{ $participant->challenge->description }}</p>
                            <div class="card-details">
                                <div class="detail-item">
                                    <i class="fas fa-money-bill me-1"></i>
                                    <span class="poppins-font">Daily: TZS {{ number_format($participant->challenge->daily_amount, 2) }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-calendar me-1"></i>
                                    <span class="poppins-font">Duration: {{ $participant->challenge->start_date->format('M d, Y') }} - {{ $participant->challenge->end_date->format('M d, Y') }}</span>
                                </div>
                                <div class="detail-item">
                                    @php
                                        $paid = $participant->getTotalPaid();
                                        $totalDays = $participant->challenge->start_date->diffInDays($participant->challenge->end_date);
                                        $currentDays = $participant->challenge->start_date->diffInDays(now());
                                        $progress = $totalDays > 0 ? min(100, ($currentDays / $totalDays) * 100) : 0;
                                    @endphp
                                    <i class="fas fa-chart-line me-1"></i>
                                    <span class="poppins-font">Progress: {{ round($progress) }}% (TZS {{ number_format($paid, 2) }} paid)</span>
                                </div>
                            </div>
                            <div class="progress mt-3">
                                <div class="progress-bar bg-oweru-gold" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Available Challenges Section -->
        <div class="col-12 {{ $userChallenges->count() > 0 ? 'col-lg-6' : '' }}">
            <div class="dashboard-section">
                <div class="section-header d-flex justify-content-between align-items-center mb-4">
                    <h3 class="futura-font fw-bold text-white mb-0">
                        <i class="fas fa-trophy me-2"></i>Available Challenges
                    </h3>
                </div>

                @if(session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                @endif

                @if($challenges->count() > 0)
                    <div class="content-cards">
                        @foreach($challenges as $challenge)
                            <div class="content-card">
                                <div class="card-header d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="futura-font fw-bold text-oweru-dark mb-0">{{ $challenge->name }}</h5>
                                    <span class="badge bg-oweru-gold text-oweru-dark futura-font">Active</span>
                                </div>
                                <p class="text-oweru-gray poppins-font mb-2">{{ $challenge->description }}</p>
                                <div class="card-details">
                                    <div class="detail-item">
                                        <i class="fas fa-money-bill me-1"></i>
                                        <span class="poppins-font">Daily: TZS {{ number_format($challenge->daily_amount, 2) }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-calendar me-1"></i>
                                        <span class="poppins-font">Duration: {{ $challenge->start_date->format('M d, Y') }} - {{ $challenge->end_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-users me-1"></i>
                                        <span class="poppins-font">Participants: {{ $challenge->active_participants_count }}/{{ $challenge->max_participants }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-user me-1"></i>
                                        <span class="poppins-font">Created by: {{ $challenge->creator->username }}</span>
                                    </div>
                                </div>

                                @php
                                    $availableSlots = $challenge->max_participants - $challenge->active_participants_count;
                                @endphp

                                <div class="mt-3">
                                    @if(auth()->check() && $challenge->participants->where('user_id', auth()->id())->where('status', 'active')->count() > 0)
                                        <button class="btn btn-oweru-secondary btn-sm w-100" disabled>
                                            <i class="fas fa-check me-1"></i>Already Joined
                                        </button>
                                    @elseif($availableSlots > 0)
                                        <form action="{{ route('challenges.index', $challenge->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-oweru-gold btn-sm w-100 text-oweru-dark">
                                                <i class="fas fa-plus me-1"></i>Join Challenge
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm w-100" disabled>
                                            <i class="fas fa-times me-1"></i>Challenge Full
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-trophy fa-3x text-oweru-gray mb-3"></i>
                        <p class="text-oweru-gray poppins-font mb-3">No active challenges available at the moment.</p>
                        <p class="text-oweru-gray poppins-font mb-4">Please check back later for new challenges.</p>
                    </div>
                @endif
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

.btn-oweru-secondary {
    background-color: var(--oweru-secondary);
    border-color: var(--oweru-secondary);
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

.challenge-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.1);
}

.challenge-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
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

.hero-buttons {
    position: relative;
    z-index: 2;
}

.challenges-grid {
    max-height: 60vh;
    overflow-y: auto;
    padding-right: 10px;
}

.challenges-grid::-webkit-scrollbar {
    width: 6px;
}

.challenges-grid::-webkit-scrollbar-track {
    background: rgba(248, 248, 249, 0.5);
    border-radius: 3px;
}

.challenges-grid::-webkit-scrollbar-thumb {
    background: var(--oweru-gold);
    border-radius: 3px;
}

.challenges-grid::-webkit-scrollbar-thumb:hover {
    background: #b58120;
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

    .challenges-grid {
        max-height: 50vh;
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

    // Observe challenge cards
    document.querySelectorAll('.challenge-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        observer.observe(card);
    });
});
</script>
@endsection
