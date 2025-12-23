@extends('layouts.app')

@section('title', $material->name)

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

    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('materials.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left me-2"></i>Back to Materials
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        <!-- Material Details -->
        <div class="col-12 col-lg-8">
            <div class="dashboard-section">
                <div class="section-header mb-4">
                    <h3 class="futura-font fw-bold text-white mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>{{ $material->name }}
                    </h3>
                </div>

                <div class="content-card">
                    <div class="card-header d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="futura-font fw-bold text-oweru-dark mb-1">{{ $material->name }}</h4>
                            @if($material->sw_name)
                            <p class="text-oweru-gray poppins-font mb-1">{{ $material->sw_name }}</p>
                            @endif
                            <span class="badge bg-oweru-gold text-oweru-dark futura-font">Available</span>
                        </div>
                        <div class="text-end">
                            <h3 class="futura-font fw-bold text-oweru-gold mb-0">TZS {{ number_format($material->price, 2) }}</h3>
                        </div>
                    </div>

                    @if($material->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $material->image) }}" alt="{{ $material->name }}" class="img-fluid rounded">
                    </div>
                    @endif

                    <div class="mb-4">
                        <h5 class="futura-font fw-bold text-oweru-dark mb-2">Description</h5>
                        <p class="text-oweru-gray poppins-font">{{ $material->description }}</p>
                    </div>

                    <div class="card-details">
                        <div class="detail-item">
                            <i class="fas fa-money-bill me-1"></i>
                            <span class="poppins-font">Price: TZS {{ number_format($material->price, 2) }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-user me-1"></i>
                            <span class="poppins-font">Added by: {{ $material->creator->name ?? 'Admin' }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar me-1"></i>
                            <span class="poppins-font">Added: {{ $material->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Purchase Options -->
        <div class="col-12 col-lg-4">
            <div class="dashboard-section">
                <div class="section-header mb-4">
                    <h3 class="futura-font fw-bold text-white mb-0">
                        <i class="fas fa-credit-card me-2"></i>Purchase Options
                    </h3>
                </div>

                <div class="content-card">
                    <h5 class="futura-font fw-bold text-oweru-dark mb-3">Choose Your Payment Method</h5>

                    <div class="d-grid gap-3">
                        <!-- Direct Purchase Button -->
                        <button type="button" class="btn btn-oweru-gold btn-lg" data-bs-toggle="modal"
                                data-bs-target="#directPurchaseModal">
                            <i class="fas fa-shopping-cart me-2"></i>Buy Now
                            <br><small class="text-oweru-dark">Full Payment</small>
                        </button>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="futura-font fw-bold text-oweru-dark mb-2">Why Choose Oweru?</h6>
                        <ul class="list-unstyled mb-0 small">
                            <li><i class="fas fa-check text-oweru-gold me-2"></i>Quality Construction Materials</li>
                            <li><i class="fas fa-check text-oweru-gold me-2"></i>Secure Payment Processing</li>
                            <li><i class="fas fa-check text-oweru-gold me-2"></i>Flexible Payment Options</li>
                            <li><i class="fas fa-check text-oweru-gold me-2"></i>Reliable Delivery</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for Purchase Options -->
<!-- Direct Purchase Modal -->
<div class="modal fade" id="directPurchaseModal" tabindex="-1">
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
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity"
                               name="quantity" value="1" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="delivery_address" class="form-label">Delivery Address</label>
                        <textarea class="form-control" id="delivery_address"
                                  name="delivery_address" rows="3" required
                                  placeholder="Enter your complete delivery address"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone_number"
                               name="phone_number" value="{{ auth()->user()->phone_number ?? '' }}" required>
                    </div>
                    <div class="alert alert-info">
                        <strong>Total Amount:</strong>
                        <span id="totalAmount">TZS {{ number_format($material->price, 2) }}</span>
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

    // Update total amount when quantity changes in direct purchase modal
    const quantityInput = document.getElementById('quantity');
    const totalAmountSpan = document.getElementById('totalAmount');
    const unitPrice = {{ $material->price }};

    if (quantityInput && totalAmountSpan) {
        quantityInput.addEventListener('input', function() {
            const quantity = parseInt(this.value) || 0;
            const totalAmount = quantity * unitPrice;
            totalAmountSpan.textContent = 'TZS ' + totalAmount.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        });
    }
});
</script>
@endsection
