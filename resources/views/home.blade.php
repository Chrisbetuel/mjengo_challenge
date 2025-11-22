@extends('layouts.app')

@section('title', 'Home')

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
                <h1 class="display-3 fw-bold text-white mb-3 futura-font">Mjengo Challenge</h1>
                <p class="lead mb-4 text-white fs-2 fw-light poppins-font">Build Your Dream Step by Step Through Collective Savings</p>
                <p class="text-light mb-4 fs-5 poppins-font">Join thousands of Tanzanians in achieving their construction goals through our innovative savings challenge platform.</p>
                
                <div class="mt-4 hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-oweru-gold btn-lg me-3 shadow-lg glow-button">
                        <i class="fas fa-user-plus me-2"></i>Get Started
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg shadow-sm hover-grow">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </a>
                </div>

                <!-- Stats -->
                <div class="row mt-5 pt-3">
                    <div class="col-4 text-center">
                        <div class="stat-item">
                            <div class="stat-number text-oweru-gold fw-bold fs-3 futura-font">10K+</div>
                            <div class="stat-label text-light poppins-font">Active Users</div>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="stat-item">
                            <div class="stat-number text-oweru-gold fw-bold fs-3 futura-font">5M+</div>
                            <div class="stat-label text-light poppins-font">Saved</div>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="stat-item">
                            <div class="stat-number text-oweru-gold fw-bold fs-3 futura-font">500+</div>
                            <div class="stat-label text-light poppins-font">Projects</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="content-card bg-oweru-light rounded-4 p-5 shadow-lg">
                <h3 class="text-center text-oweru-dark mb-4 futura-font">Start Building Today</h3>
                <div class="feature-highlights">
                    <div class="feature-item d-flex align-items-center mb-4">
                        <div class="feature-icon bg-oweru-gold rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-trophy text-white"></i>
                        </div>
                        <div>
                            <h5 class="text-oweru-dark mb-1 futura-font">Daily Challenges</h5>
                            <p class="text-oweru-gray mb-0 poppins-font">Participate in daily savings challenges</p>
                        </div>
                    </div>
                    <div class="feature-item d-flex align-items-center mb-4">
                        <div class="feature-icon bg-oweru-blue rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-bricks text-white"></i>
                        </div>
                        <div>
                            <h5 class="text-oweru-dark mb-1 futura-font">Material Purchase</h5>
                            <p class="text-oweru-gray mb-0 poppins-font">Buy materials directly or in installments</p>
                        </div>
                    </div>
                    <div class="feature-item d-flex align-items-center mb-4">
                        <div class="feature-icon bg-oweru-secondary rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div>
                            <h5 class="text-oweru-dark mb-1 futura-font">Group Savings</h5>
                            <p class="text-oweru-gray mb-0 poppins-font">Save together for bigger projects</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mt-5 pt-5">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold text-white display-5 futura-font">How It Works</h2>
            <p class="text-light fs-5 poppins-font">Three simple ways to achieve your construction goals</p>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="feature-card card h-100 text-center border-0 shadow-lg">
                <div class="card-body p-5">
                    <div class="feature-icon-wrapper mb-4">
                        <div class="feature-icon bg-oweru-gold bg-gradient rounded-3 d-inline-flex align-items-center justify-content-center">
                            <i class="fas fa-trophy fa-2x text-white"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold fs-4 text-oweru-dark futura-font">Daily Challenges</h5>
                    <p class="card-text text-oweru-gray fs-6 poppins-font">Participate in daily savings challenges with your community</p>
                    <div class="feature-hover">
                        <span class="text-oweru-gold">Learn More →</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-card card h-100 text-center border-0 shadow-lg">
                <div class="card-body p-5">
                    <div class="feature-icon-wrapper mb-4">
                        <div class="feature-icon bg-oweru-blue bg-gradient rounded-3 d-inline-flex align-items-center justify-content-center">
                            <i class="fas fa-bricks fa-2x text-white"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold fs-4 text-oweru-dark futura-font">Material Purchase</h5>
                    <p class="card-text text-oweru-gray fs-6 poppins-font">Buy construction materials directly or through installments</p>
                    <div class="feature-hover">
                        <span class="text-oweru-blue">Learn More →</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-card card h-100 text-center border-0 shadow-lg">
                <div class="card-body p-5">
                    <div class="feature-icon-wrapper mb-4">
                        <div class="feature-icon bg-oweru-secondary bg-gradient rounded-3 d-inline-flex align-items-center justify-content-center">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold fs-4 text-oweru-dark futura-font">Group Savings</h5>
                    <p class="card-text text-oweru-gray fs-6 poppins-font">Form groups and save together for bigger projects</p>
                    <div class="feature-hover">
                        <span class="text-oweru-secondary">Learn More →</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="row mt-5 pt-5">
        <div class="col-12">
            <div class="cta-section bg-oweru-dark bg-opacity-85 rounded-4 p-5 text-center text-white shadow-lg backdrop-blur">
                <h3 class="fw-bold mb-3 futura-font">Ready to Start Your Construction Journey?</h3>
                <p class="mb-4 opacity-90 poppins-font">Join thousands of successful builders today</p>
                <a href="{{ route('register') }}" class="btn btn-oweru-gold btn-lg text-oweru-dark fw-bold shadow">
                    <i class="fas fa-rocket me-2"></i>Start Building Now
                </a>
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

.feature-icon {
    width: 50px;
    height: 50px;
    flex-shrink: 0;
}

.feature-card {
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    background: rgba(248, 248, 249, 0.95);
}

.feature-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 25px 50px rgba(0,0,0,0.15) !important;
}

.feature-icon-wrapper .feature-icon {
    width: 100px;
    height: 100px;
    transition: all 0.4s ease;
}

.feature-card:hover .feature-icon {
    transform: scale(1.1) rotate(5deg);
}

.feature-hover {
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
}

.feature-card:hover .feature-hover {
    opacity: 1;
    transform: translateY(0);
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

.backdrop-blur {
    backdrop-filter: blur(10px);
}

.stat-item {
    position: relative;
    padding: 20px 0;
}

.stat-item::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 25%;
    width: 50%;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--oweru-gold), transparent);
}

.hero-buttons {
    position: relative;
    z-index: 2;
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

    // Observe feature cards
    document.querySelectorAll('.feature-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
    
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

@section('footer')
<!-- Footer -->
<footer class="footer bg-oweru-dark text-white py-5 mt-5">
    <div class="container-fluid">
        <div class="row g-4">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-logo mb-3">
                    <span class="oweru-o">O</span>
                    <span class="oweru-text">weru</span>
                </div>
                <p class="text-light mb-3 futura-font">Smart Investments. Safe Returns.</p>
                <p class="text-oweru-gray poppins-font small mb-3">
                    Empowering Tanzanians with innovative financial solutions for building a brighter future through collective savings and investment opportunities.
                </p>
                <div class="social-links">
                    <a href="#" class="text-oweru-gold me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-oweru-gold me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-oweru-gold me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-oweru-gold"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="futura-font fw-bold text-white mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-oweru-gray hover-oweru-gold text-decoration-none poppins-font">Home</a></li>
                    <li class="mb-2"><a href="{{ route('challenges.index') }}" class="text-oweru-gray hover-oweru-gold text-decoration-none poppins-font">Challenges</a></li>
                    <li class="mb-2"><a href="{{ route('materials.index') }}" class="text-oweru-gray hover-oweru-gold text-decoration-none poppins-font">Materials</a></li>
                    <li class="mb-2"><a href="{{ route('groups.index') }}" class="text-oweru-gray hover-oweru-gold text-decoration-none poppins-font">Groups</a></li>
                    @auth
                    <li class="mb-2"><a href="{{ route('dashboard') }}" class="text-oweru-gray hover-oweru-gold text-decoration-none poppins-font">Dashboard</a></li>
                    @endauth
                </ul>
            </div>

            <!-- Services -->
            <div class="col-lg-2 col-md-6">
                <h5 class="futura-font fw-bold text-white mb-3">Services</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><span class="text-oweru-gray poppins-font">Savings Challenges</span></li>
                    <li class="mb-2"><span class="text-oweru-gray poppins-font">Lipa Kidogo Plans</span></li>
                    <li class="mb-2"><span class="text-oweru-gray poppins-font">Group Investments</span></li>
                    <li class="mb-2"><span class="text-oweru-gray poppins-font">Material Purchases</span></li>
                    <li class="mb-2"><span class="text-oweru-gray poppins-font">Financial Education</span></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-2 col-md-6">
                <h5 class="futura-font fw-bold text-white mb-3">Contact</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt text-oweru-gold me-2"></i>
                        <span class="text-oweru-gray poppins-font small">Dar es Salaam, Tanzania</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone text-oweru-gold me-2"></i>
                        <span class="text-oweru-gray poppins-font small">+255 XXX XXX XXX</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-envelope text-oweru-gold me-2"></i>
                        <span class="text-oweru-gray poppins-font small">info@oweru.co.tz</span>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="col-lg-2 col-md-6">
                <h5 class="futura-font fw-bold text-white mb-3">Newsletter</h5>
                <p class="text-oweru-gray poppins-font small mb-3">Stay updated with our latest news and offers.</p>
                <form class="newsletter-form">
                    <div class="input-group">
                        <input type="email" class="form-control bg-oweru-blue border-0 text-white poppins-font" placeholder="Your email" style="background-color: var(--oweru-blue) !important;">
                        <button class="btn btn-oweru-gold futura-font fw-bold" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Bottom -->
        <hr class="my-4" style="border-color: var(--oweru-gray);">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="text-oweru-gray poppins-font small mb-0">
                    &copy; {{ date('Y') }} Oweru. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="#" class="text-oweru-gray hover-oweru-gold text-decoration-none poppins-font small me-3">Privacy Policy</a>
                <a href="#" class="text-oweru-gray hover-oweru-gold text-decoration-none poppins-font small me-3">Terms of Service</a>
                <a href="#" class="text-oweru-gray hover-oweru-gold text-decoration-none poppins-font small">Support</a>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer Styles */
.footer {
    background: linear-gradient(135deg, var(--oweru-dark) 0%, var(--oweru-blue) 100%);
    border-top: 3px solid var(--oweru-gold);
}

.footer-logo {
    font-family: 'Futura PT', sans-serif;
    font-size: 2rem;
    font-weight: 700;
    display: inline-block;
}

.footer .oweru-o {
    color: var(--oweru-gold);
    font-size: 2.5rem;
}

.footer .oweru-text {
    color: var(--oweru-light);
    font-weight: 600;
}

.social-links a {
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.social-links a:hover {
    color: var(--oweru-gold) !important;
    transform: translateY(-2px);
}

.hover-oweru-gold:hover {
    color: var(--oweru-gold) !important;
}

.newsletter-form .form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(200, 145, 40, 0.25);
    border-color: var(--oweru-gold);
}

.newsletter-form .input-group .btn {
    border-radius: 0 0.375rem 0.375rem 0;
}

/* Responsive Footer */
@media (max-width: 768px) {
    .footer .row > div {
        text-align: center;
        margin-bottom: 2rem;
    }

    .footer .col-md-6.text-md-end {
        text-align: center !important;
        margin-top: 1rem;
    }

    .social-links {
        justify-content: center;
    }
}
</style>
@endsection
