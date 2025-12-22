@extends('layouts.app')

@section('title', 'Construction Materials')

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
        <!-- Total Materials Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dashboard-card bg-oweru-gold text-oweru-dark text-center">
                <div class="card-icon mb-3">
                    <i class="fas fa-shopping-cart fa-2x"></i>
                </div>
                <h3 class="futura-font fw-bold mb-2">Total Materials</h3>
                <p class="display-6 fw-bold mb-0">{{ $materials->count() }}</p>
            </div>
        </div>

        <!-- Available Materials Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dashboard-card bg-oweru-blue text-white text-center">
                <div class="card-icon mb-3">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <h3 class="futura-font fw-bold mb-2">Available</h3>
                <p class="display-6 fw-bold mb-0">{{ $materials->where('is_available', true)->count() }}</p>
            </div>
        </div>

        <!-- Direct Purchases Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dashboard-card bg-oweru-secondary text-oweru-dark text-center">
                <div class="card-icon mb-3">
                    <i class="fas fa-credit-card fa-2x"></i>
                </div>
                <h3 class="futura-font fw-bold mb-2">Direct Purchases</h3>
                <p class="display-6 fw-bold mb-0">{{ $materials->sum('direct_purchases_count') ?? 0 }}</p>
            </div>
        </div>

        <!-- Lipa Kidogo Plans Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dashboard-card bg-oweru-dark text-white text-center">
                <div class="card-icon mb-3">
                    <i class="fas fa-calendar-alt fa-2x"></i>
                </div>
                <h3 class="futura-font fw-bold mb-2">Installment Plans</h3>
                <p class="display-6 fw-bold mb-0">{{ $materials->sum('lipa_kidogo_count') ?? 0 }}</p>
            </div>
        </div>

        <!-- Available Materials Section -->
        <div class="col-12">
            <div class="dashboard-section">
                <div class="section-header d-flex justify-content-between align-items-center mb-4">
                    <h3 class="futura-font fw-bold text-white mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Available Construction Materials
                    </h3>
                </div>

                @if(session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif

                @if($materials->count() > 0)
                    <div class="content-cards">
                        @foreach($materials as $material)
                            <div class="content-card">
                                <div class="card-header d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="futura-font fw-bold text-oweru-dark mb-0">{{ $material->name }}</h5>
                                    @if($material->is_available)
                                        <span class="badge bg-oweru-gold text-oweru-dark futura-font">Available</span>
                                    @else
                                        <span class="badge bg-secondary text-white futura-font">Unavailable</span>
                                    @endif
                                </div>
                                @if($material->sw_name)
                                <p class="text-oweru-gray poppins-font mb-1">{{ $material->sw_name }}</p>
                                @endif
                                <p class="text-oweru-gray poppins-font mb-2">{{ Str::limit($material->description, 100) }}</p>
                                <div class="card-details">
                                    <div class="detail-item">
                                        <i class="fas fa-money-bill me-1"></i>
                                        <span class="poppins-font">Price: TZS {{ number_format($material->price, 2) }}</span>
                                    </div>
                                    @if($material->image)
                                    <div class="detail-item">
                                        <i class="fas fa-image me-1"></i>
                                        <span class="poppins-font">Image Available</span>
                                    </div>
                                    @endif
                                </div>

                                <div class="mt-3 d-flex gap-2">
                                    @if($material->is_available)
                                        <!-- Direct Purchase Button -->
                                        <button type="button" class="btn btn-oweru-gold btn-sm flex-fill" data-bs-toggle="modal"
                                                data-bs-target="#directPurchaseModal{{ $material->id }}">
                                            <i class="fas fa-shopping-cart me-1"></i>Buy Now
                                        </button>

                                        <!-- Lipa Kidogo Button -->
                                        <button type="button" class="btn btn-oweru-secondary btn-sm flex-fill" data-bs-toggle="modal"
                                                data-bs-target="#lipaKidogoModal{{ $material->id }}">
                                            <i class="fas fa-calendar-alt me-1"></i>Lipa Kidogo
                                        </button>
                                    @else
                                        <button class="btn btn-secondary btn-sm w-100" disabled>
                                            <i class="fas fa-times me-1"></i>Unavailable
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-shopping-cart fa-3x text-oweru-gray mb-3"></i>
                        <p class="text-oweru-gray poppins-font mb-3">No materials available at the moment.</p>
                        <p class="text-oweru-gray poppins-font mb-4">Please check back later for new materials.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modals for Purchase Options -->
@foreach($materials as $material)
    <!-- Direct Purchase Modal -->
    <div class="modal fade" id="directPurchaseModal{{ $material->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Direct Purchase - {{ $material->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('materials.direct-purchase', $material->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="quantity{{ $material->id }}" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity{{ $material->id }}"
                                   name="quantity" value="1" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="delivery_address{{ $material->id }}" class="form-label">Delivery Address</label>
                            <textarea class="form-control" id="delivery_address{{ $material->id }}"
                                      name="delivery_address" rows="3" required
                                      placeholder="Enter your complete delivery address"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number{{ $material->id }}" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone_number{{ $material->id }}"
                                   name="phone_number" value="{{ auth()->user()->phone_number ?? '' }}" required>
                        </div>
                        <div class="alert alert-info">
                            <strong>Total Amount:</strong>
                            <span id="totalAmount{{ $material->id }}">TZS {{ number_format($material->price, 2) }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Pay Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lipa Kidogo Modal -->
    <div class="modal fade" id="lipaKidogoModal{{ $material->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lipa Kidogo - {{ $material->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('materials.lipa-kidogo', $material->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">User Type</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="user_type"
                                           id="businessman{{ $material->id }}" value="businessman" required>
                                    <label class="form-check-label" for="businessman{{ $material->id }}">Businessman</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="user_type"
                                           id="employed{{ $material->id }}" value="employed">
                                    <label class="form-check-label" for="employed{{ $material->id }}">Employed</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="payment_duration{{ $material->id }}" class="form-label">Payment Duration</label>
                            <select class="form-select" id="payment_duration{{ $material->id }}" name="payment_duration" required>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_date{{ $material->id }}" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date{{ $material->id }}"
                                   name="start_date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="alert alert-info">
                            <strong>Total Amount:</strong> TZS {{ number_format($material->price, 2) }}<br>
                            <small>Installment amounts will be calculated based on your preferences</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Pay Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

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

/* Color Classes */
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

/* Button Styles */
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

/* Logo Styling */
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

/* Background */
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

/* Cards */
.content-card {
    background: rgba(248, 248, 249, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

/* Navigation Cards */
.nav-card {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease;
    min-height: 100px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.nav-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Dashboard Cards */
.dashboard-card {
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card-icon {
    margin-bottom: 15px;
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
        margin-bottom: 10px;
    }
}

/* Fonts */
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

    // Update total amount when quantity changes in direct purchase modal
    @foreach($materials as $material)
    const quantityInput{{ $material->id }} = document.getElementById('quantity{{ $material->id }}');
    const totalAmountSpan{{ $material->id }} = document.getElementById('totalAmount{{ $material->id }}');
    const unitPrice{{ $material->id }} = {{ $material->price }};

    if (quantityInput{{ $material->id }} && totalAmountSpan{{ $material->id }}) {
        quantityInput{{ $material->id }}.addEventListener('input', function() {
            const quantity = parseInt(this.value) || 0;
            const totalAmount = quantity * unitPrice{{ $material->id }};
            totalAmountSpan{{ $material->id }}.textContent = 'TZS ' + totalAmount.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        });
    }
    @endforeach
});
</script>
@endsection
