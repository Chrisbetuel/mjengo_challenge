@extends('layouts.app')

@section('title', 'Dashboard')

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
                    <a href="{{ route('dashboard') }}" class="nav-card bg-oweru-gold text-oweru-dark">
                        <i class="fas fa-chart-line fa-lg mb-2"></i>
                        <span class="futura-font fw-bold">Dashboard</span>
                    </a>
                    <a href="{{ route('challenges.index') }}" class="nav-card bg-oweru-blue text-white">
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
        <!-- Total Savings Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dashboard-card bg-oweru-gold text-oweru-dark text-center">
                <div class="card-icon mb-3">
                    <i class="fas fa-piggy-bank fa-2x"></i>
                </div>
                <h3 class="futura-font fw-bold mb-2">Total Savings</h3>
                <p class="display-6 fw-bold mb-0">TZS {{ number_format($totalSavings, 2) }}</p>
            </div>
        </div>

        <!-- Active Challenges Count -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dashboard-card bg-oweru-blue text-white text-center">
                <div class="card-icon mb-3">
                    <i class="fas fa-trophy fa-2x"></i>
                </div>
                <h3 class="futura-font fw-bold mb-2">Active Challenges</h3>
                <p class="display-6 fw-bold mb-0">{{ $activeChallenges->count() }}</p>
            </div>
        </div>

        <!-- Lipa Kidogo Plans -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dashboard-card bg-oweru-secondary text-oweru-dark text-center">
                <div class="card-icon mb-3">
                    <i class="fas fa-calendar-alt fa-2x"></i>
                </div>
                <h3 class="futura-font fw-bold mb-2">Active Plans</h3>
                <p class="display-6 fw-bold mb-0">{{ $lipaKidogoPlans->count() }}</p>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dashboard-card bg-oweru-dark text-white text-center">
                <div class="card-icon mb-3">
                    <i class="fas fa-receipt fa-2x"></i>
                </div>
                <h3 class="futura-font fw-bold mb-2">Recent Payments</h3>
                <p class="display-6 fw-bold mb-0">{{ $recentPayments->count() }}</p>
            </div>
        </div>

        <!-- Active Challenges Section -->
        <div class="col-12 col-lg-6">
            <div class="dashboard-section">
                <div class="section-header d-flex justify-content-between align-items-center mb-4">
                    <h3 class="futura-font fw-bold text-white mb-0">
                        <i class="fas fa-trophy me-2"></i>Active Challenges
                    </h3>
                    <a href="{{ route('challenges.index') }}" class="btn btn-sm btn-oweru-gold futura-font">View All</a>
                </div>
                
                @if($activeChallenges->count() > 0)
                    <div class="content-cards">
                        @foreach($activeChallenges as $participant)
                            <div class="content-card">
                                <div class="card-header d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="futura-font fw-bold text-oweru-dark mb-0">{{ $participant->challenge->name }}</h5>
                                    <span class="badge bg-oweru-gold text-oweru-dark futura-font">{{ ucfirst($participant->status) }}</span>
                                </div>
                                <p class="text-oweru-gray poppins-font mb-2">{{ $participant->challenge->description }}</p>
                                <div class="card-details">
                                    <div class="detail-item">
                                        <i class="fas fa-bullseye me-1"></i>
                                        <span class="poppins-font">Target: TZS {{ number_format($participant->challenge->target_amount, 2) }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-clock me-1"></i>
                                        <span class="poppins-font">Duration: {{ $participant->challenge->duration }} months</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-trophy fa-3x text-oweru-gray mb-3"></i>
                        <p class="text-oweru-gray poppins-font mb-3">No active challenges found.</p>
                        <a href="{{ route('challenges.index') }}" class="btn btn-oweru-gold futura-font">Explore Challenges</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Payments Section -->
        <div class="col-12 col-lg-6">
            <div class="dashboard-section">
                <div class="section-header d-flex justify-content-between align-items-center mb-4">
                    <h3 class="futura-font fw-bold text-white mb-0">
                        <i class="fas fa-receipt me-2"></i>Recent Payments
                    </h3>
                    <a href="#" class="btn btn-sm btn-oweru-gold futura-font">View All</a>
                </div>
                
                @if($recentPayments->count() > 0)
                    <div class="content-cards">
                        @foreach($recentPayments as $payment)
                            <div class="content-card">
                                <div class="card-header d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="futura-font fw-bold text-oweru-dark mb-0">Payment #{{ $payment->id }}</h5>
                                    <span class="badge bg-success text-white futura-font">{{ ucfirst($payment->status) }}</span>
                                </div>
                                <div class="card-details">
                                    <div class="detail-item">
                                        <i class="fas fa-calendar me-1"></i>
                                        <span class="poppins-font">{{ $payment->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-money-bill me-1"></i>
                                        <span class="poppins-font">Amount: TZS {{ number_format($payment->amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-receipt fa-3x text-oweru-gray mb-3"></i>
                        <p class="text-oweru-gray poppins-font mb-3">No recent payments found.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Lipa Kidogo Plans Section -->
        <div class="col-12">
            <div class="dashboard-section">
                <div class="section-header d-flex justify-content-between align-items-center mb-4">
                    <h3 class="futura-font fw-bold text-white mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Lipa Kidogo Plans
                    </h3>
                    <a href="{{ route('materials.index') }}" class="btn btn-sm btn-oweru-gold futura-font">View All</a>
                </div>
                
                @if($lipaKidogoPlans->count() > 0)
                    <div class="row g-3">
                        @foreach($lipaKidogoPlans as $plan)
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="plan-card">
                                    <div class="plan-header d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="futura-font fw-bold text-oweru-dark mb-0">{{ $plan->material->name }}</h5>
                                        <span class="badge bg-oweru-blue text-white futura-font">{{ ucfirst($plan->status) }}</span>
                                    </div>
                                    <p class="text-oweru-gray poppins-font mb-3 small">{{ $plan->material->description }}</p>
                                    <div class="plan-details">
                                        <div class="detail-row">
                                            <span class="poppins-font">Total Amount:</span>
                                            <span class="futura-font fw-bold">TZS {{ number_format($plan->total_amount, 2) }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="poppins-font">Installment:</span>
                                            <span class="futura-font fw-bold">TZS {{ number_format($plan->installment_amount, 2) }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="poppins-font">Installments:</span>
                                            <span class="futura-font fw-bold">{{ $plan->num_installments }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="poppins-font">Started:</span>
                                            <span class="futura-font fw-bold">{{ $plan->start_date->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-calendar-alt fa-3x text-oweru-gray mb-3"></i>
                        <p class="text-oweru-gray poppins-font mb-3">No Lipa Kidogo plans found.</p>
                        <a href="{{ route('materials.index') }}" class="btn btn-oweru-gold futura-font">Browse Materials</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Groups Section -->
        <div class="col-12">
            <div class="dashboard-section">
                <div class="section-header d-flex justify-content-between align-items-center mb-4">
                    <h3 class="futura-font fw-bold text-white mb-0">
                        <i class="fas fa-users me-2"></i>Groups
                    </h3>
                    <a href="{{ route('groups.create') }}" class="btn btn-sm btn-oweru-gold futura-font">Create Group</a>
                </div>
                
                <div class="empty-state text-center py-5">
                    <i class="fas fa-users fa-3x text-oweru-gray mb-3"></i>
                    <p class="text-oweru-gray poppins-font mb-3">You haven't joined any groups yet.</p>
                    <p class="text-oweru-gray poppins-font mb-4">Join or create groups to collaborate with other users on challenges and savings goals.</p>
                    <a href="{{ route('groups.create') }}" class="btn btn-oweru-gold futura-font">Create Your First Group</a>
                </div>
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
.bg-oweru-gray { background-color: var(--oweru-gray) !important; }

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

/* Oweru Logo */
.oweru-logo {
    font-family: 'Futura PT', sans-serif;
    font-size: 2.5rem;
    font-weight: 700;
    display: inline-block;
}

.oweru-o {
    color: var(--oweru-gold);
    font-size: 3rem;
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

/* Background Slideshow */
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

/* Navigation Cards */
.nav-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 120px;
    height: 100px;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.nav-card:hover {
    transform: translateY(-5px);
    text-decoration: none;
    border-color: var(--oweru-gold);
}

/* Dashboard Cards */
.dashboard-card {
    padding: 2rem 1rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: none;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15);
}

.card-icon {
    opacity: 0.9;
}

/* Dashboard Sections */
.dashboard-section {
    background: rgba(248, 248, 249, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 2rem;
    border: 1px solid rgba(255,255,255,0.2);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.section-header {
    border-bottom: 2px solid var(--oweru-gold);
    padding-bottom: 1rem;
}

/* Content Cards */
.content-cards {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.content-card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid var(--oweru-gold);
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.content-card:hover {
    transform: translateX(5px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

.plan-card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    border-top: 4px solid var(--oweru-blue);
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    height: 100%;
    transition: all 0.3s ease;
}

.plan-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

/* Card Details */
.card-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-item {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.plan-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
    padding: 0.25rem 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.detail-row:last-child {
    border-bottom: none;
}

/* Empty States */
.empty-state {
    padding: 3rem 2rem;
    text-align: center;
    background: rgba(255,255,255,0.5);
    border-radius: 10px;
    border: 2px dashed var(--oweru-gray);
}

/* Full width layout - no sidebar */
.container-fluid {
    padding-left: 15px !important;
    padding-right: 15px !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    width: 100% !important;
}

/* Responsive */
@media (max-width: 768px) {
    .oweru-logo {
        font-size: 2rem;
    }
    
    .oweru-o {
        font-size: 2.5rem;
    }
    
    .nav-card {
        width: 100px;
        height: 90px;
    }
    
    .dashboard-card {
        padding: 1.5rem 1rem;
    }
    
    .display-6 {
        font-size: 1.8rem;
    }
}

/* Import Fonts */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
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
    
    // Force full width layout
    document.body.style.overflowX = 'hidden';
    const mainContainer = document.querySelector('.container-fluid');
    if (mainContainer) {
        mainContainer.style.maxWidth = '100%';
        mainContainer.style.marginLeft = '0';
        mainContainer.style.marginRight = '0';
    }
});
</script>
@endsection