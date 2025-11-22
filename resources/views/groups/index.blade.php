@extends('layouts.app')

@section('title', 'Groups')

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

    <!-- Groups Section -->
    <div class="row g-4">
        <div class="col-12">
            <div class="dashboard-section">
                <div class="section-header d-flex justify-content-between align-items-center mb-4">
                    <h3 class="futura-font fw-bold text-white mb-0">
                        <i class="fas fa-users me-2"></i>Groups
                    </h3>
                    <a href="{{ route('groups.create') }}" class="btn btn-oweru-gold futura-font fw-bold">
                        <i class="fas fa-plus me-2"></i>Create Group
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if($groups->count() > 0)
                    <div class="row g-3">
                        @foreach($groups as $group)
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="content-card">
                                    <div class="card-header d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="futura-font fw-bold text-oweru-dark mb-0">
                                            <a href="{{ route('groups.show', $group) }}" class="text-oweru-dark hover:text-oweru-gold text-decoration-none">
                                                {{ $group->name }}
                                            </a>
                                        </h5>
                                        @if($group->isUserLeader(Auth::id()))
                                            <span class="badge bg-oweru-blue text-white futura-font">Leader</span>
                                        @elseif($group->isUserMember(Auth::id()))
                                            <span class="badge bg-success text-white futura-font">Member</span>
                                        @endif
                                    </div>

                                    <p class="text-oweru-gray poppins-font mb-3">{{ $group->description }}</p>

                                    <div class="card-details mb-3">
                                        <div class="detail-item">
                                            <i class="fas fa-user me-1"></i>
                                            <span class="poppins-font">Leader: {{ $group->leader->name }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-users me-1"></i>
                                            <span class="poppins-font">{{ $group->getMemberCount() }}/{{ $group->max_members }} members</span>
                                        </div>
                                    </div>

                                    @if($group->challenge)
                                        <div class="bg-oweru-light border border-oweru-gold rounded-lg p-3 mb-3">
                                            <p class="text-sm text-oweru-dark futura-font fw-bold">Challenge: {{ $group->challenge->name }}</p>
                                        </div>
                                    @endif

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('groups.show', $group) }}" class="btn btn-oweru-gray btn-sm futura-font fw-bold">
                                            View Details
                                        </a>

                                        @if(!$group->isUserMember(Auth::id()) && $group->hasAvailableSlots())
                                            <form action="{{ route('groups.join', $group) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm futura-font fw-bold">
                                                    Join
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-users fa-3x text-oweru-gray mb-3"></i>
                        <h3 class="futura-font fw-bold text-oweru-dark mb-2">No Groups Available</h3>
                        <p class="text-oweru-gray poppins-font mb-4">Be the first to create a group and start building together!</p>
                        <a href="{{ route('groups.create') }}" class="btn btn-oweru-gold futura-font fw-bold">
                            Create Your First Group
                        </a>
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

.btn-oweru-gray {
    background-color: var(--oweru-gray);
    border-color: var(--oweru-gray);
    color: white;
    font-weight: 600;
}

.btn-oweru-gray:hover {
    background-color: #6b7b7b;
    border-color: #6b7b7b;
    color: white;
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

    .dashboard-section {
        padding: 1.5rem;
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
