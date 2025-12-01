@extends('layouts.app')

@section('title', __('messages.title'))

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
    <!-- Language Switcher -->
    <div class="language-switcher text-end mb-3">
        <form id="language-form" action="{{ route('language.switch') }}" method="POST" class="d-inline">
            @csrf
            <select name="locale" onchange="document.getElementById('language-form').submit()" class="form-select d-inline w-auto">
                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                <option value="sw" {{ app()->getLocale() == 'sw' ? 'selected' : '' }}>Swahili</option>
            </select>
        </form>
    </div>
    <!-- Header with Oweru Logo -->
    <div class="row mb-5" style="margin-bottom: 3rem;">
        <div class="col-12 text-center" style="text-align: center;">
            <div class="oweru-logo">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="Oweru Logo" 
                     style="width:120px; height:auto; object-fit:contain;">
            </div>

            <p class="text-light mt-2 fs-5" 
               style="color:#ffffff; margin-top:10px; font-size:1.2rem;">
               {{ __('messages.smart_investments') }}
            </p>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="row align-items-center min-vh-100">
        <div class="col-lg-6">
            <div class="hero-content">
                <h1 class="display-3 fw-bold text-white mb-3 futura-font">{{ __('messages.mjengo_challenge') }}</h1>
                <p class="lead mb-4 text-white fs-2 fw-light poppins-font">{{ __('messages.build_dream') }}</p>
                <p class="text-light mb-4 fs-5 poppins-font">{{ __('messages.join_community') }}</p>
                
                <div class="mt-4 hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-oweru-gold btn-lg me-3 shadow-lg glow-button">
                        <i class="fas fa-user-plus me-2"></i>{{ __('messages.get_started') }}
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg shadow-sm hover-grow">
                        <i class="fas fa-sign-in-alt me-2"></i>{{ __('messages.login') }}
                    </a>
                </div>

                <!-- Stats -->
                <div class="row mt-5 pt-3">
                    <div class="col-4 text-center">
                        <div class="stat-item">
                            <div class="stat-number text-oweru-gold fw-bold fs-3 futura-font">10K+</div>
                            <div class="stat-label text-light poppins-font">{{ __('messages.active_users') }}</div>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="stat-item">
                            <div class="stat-number text-oweru-gold fw-bold fs-3 futura-font">5M+</div>
                            <div class="stat-label text-light poppins-font">{{ __('messages.saved') }}</div>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="stat-item">
                            <div class="stat-number text-oweru-gold fw-bold fs-3 futura-font">500+</div>
                            <div class="stat-label text-light poppins-font">{{ __('messages.projects') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="content-card bg-oweru-light rounded-4 p-5 shadow-lg">
                <h3 class="text-center text-oweru-dark mb-4 futura-font">{{ __('messages.start_building_today') }}</h3>
                <div class="feature-highlights">
                    <div class="feature-item d-flex align-items-center mb-4">
                        <div class="feature-icon bg-oweru-gold rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-trophy text-white"></i>
                        </div>
                        <div>
                            <h5 class="text-oweru-dark mb-1 futura-font">{{ __('messages.daily_challenges') }}</h5>
                            <p class="text-oweru-gray mb-0 poppins-font">{{ __('messages.daily_challenges_desc') }}</p>
                        </div>
                    </div>
                    <div class="feature-item d-flex align-items-center mb-4">
                        <div class="feature-icon bg-oweru-blue rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-bricks text-white"></i>
                        </div>
                        <div>
                            <h5 class="text-oweru-dark mb-1 futura-font">{{ __('messages.material_purchase') }}</h5>
                            <p class="text-oweru-gray mb-0 poppins-font">{{ __('messages.material_purchase_desc') }}</p>
                        </div>
                    </div>
                    <div class="feature-item d-flex align-items-center mb-4">
                        <div class="feature-icon bg-oweru-secondary rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div>
                            <h5 class="text-oweru-dark mb-1 futura-font">{{ __('messages.lipa_kidogo') }}</h5>
                            <p class="text-oweru-gray mb-0 poppins-font">{{ __('messages.lipa_kidogo_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="row mt-5 pt-5">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold text-white display-5 futura-font">{{ __('messages.what_our_users_say') }}</h2>
            <p class="text-light fs-5 poppins-font">{{ __('messages.trusted_by_thousands') }}</p>
        </div>
        
        <div class="col-12">
            <div class="testimonials-container">
                <div class="testimonials-slider">
                    @foreach($testimonials as $testimonial)
                    <div class="testimonial-card {{ $loop->first ? 'active' : '' }}">
                        <div class="testimonial-content">
                            <div class="rating-stars mb-3">
                                <!-- Rating not available in feedback, show default stars -->
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-oweru-gold"></i>
                                @endfor
                            </div>
                            <p class="testimonial-text">"{{ $testimonial->message }}"</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    {{ strtoupper(substr($testimonial->user->username, 0, 1)) }}
                                </div>
                                <div class="author-info">
                                    <h6 class="author-name mb-1 futura-font">{{ $testimonial->user->username }}</h6>
                                    <p class="author-role text-oweru-gray mb-0 poppins-font">Oweru User</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Testimonial Navigation Dots -->
                <div class="testimonial-dots">
                    @foreach($testimonials as $testimonial)
                    <span class="dot {{ $loop->first ? 'active' : '' }}" data-index="{{ $loop->index }}"></span>
                    @endforeach
                </div>

                <!-- Add Testimonial Button -->
                @auth
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-oweru-outline" data-bs-toggle="modal" data-bs-target="#addTestimonialModal">
                        <i class="fas fa-plus me-2"></i>{{ __('messages.share_your_experience') }}
                    </button>
                </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mt-5 pt-5">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold text-white display-5 futura-font">{{ __('messages.how_it_works') }}</h2>
            <p class="text-light fs-5 poppins-font">{{ __('messages.three_simple_ways') }}</p>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="feature-card card h-100 text-center border-0 shadow-lg">
                <div class="card-body p-5">
                    <div class="feature-icon-wrapper mb-4">
                        <div class="feature-icon bg-oweru-gold bg-gradient rounded-3 d-inline-flex align-items-center justify-content-center">
                            <i class="fas fa-trophy fa-2x text-white"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold fs-4 text-oweru-dark futura-font">{{ __('messages.daily_challenges') }}</h5>
                    <p class="card-text text-oweru-gray fs-6 poppins-font">{{ __('messages.daily_challenges_desc') }}</p>
                    <div class="feature-hover">
                        <span class="text-oweru-gold">{{ __('messages.learn_more') }}</span>
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
                    <h5 class="card-title fw-bold fs-4 text-oweru-dark futura-font">{{ __('messages.material_purchase') }}</h5>
                    <p class="card-text text-oweru-gray fs-6 poppins-font">{{ __('messages.material_purchase_desc') }}</p>
                    <div class="feature-hover">
                        <span class="text-oweru-blue">{{ __('messages.learn_more') }}</span>
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
                    <h5 class="card-title fw-bold fs-4 text-oweru-dark futura-font">{{ __('messages.group_savings') }}</h5>
                    <p class="card-text text-oweru-gray fs-6 poppins-font">{{ __('messages.group_savings_desc') }}</p>
                    <div class="feature-hover">
                        <span class="text-oweru-secondary">{{ __('messages.learn_more') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="row mt-5 pt-5">
        <div class="col-12">
            <div class="cta-section bg-oweru-dark bg-opacity-85 rounded-4 p-5 text-center text-white shadow-lg backdrop-blur">
                <h3 class="fw-bold mb-3 futura-font">{{ __('messages.ready_to_start') }}</h3>
                <p class="mb-4 opacity-90 poppins-font">{{ __('messages.join_today') }}</p>
                <a href="{{ route('register') }}" class="btn btn-oweru-gold btn-lg text-oweru-dark fw-bold shadow">
                    <i class="fas fa-rocket me-2"></i>{{ __('messages.start_building_now') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Add Testimonial Modal -->
@auth
<div class="modal fade" id="addTestimonialModal" tabindex="-1" aria-labelledby="addTestimonialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-oweru-dark text-white">
                <h5 class="modal-title futura-font" id="addTestimonialModalLabel">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.share_your_experience') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('testimonials.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="rating" class="form-label futura-font">{{ __('messages.your_rating') }}</label>
                        <div class="rating-input">
                            @for($i = 1; $i <= 5; $i++)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ $i == 5 ? 'checked' : '' }}>
                            <label for="star{{ $i }}" class="star-label">
                                <i class="fas fa-star"></i>
                            </label>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label futura-font">{{ __('messages.your_testimonial') }}</label>
                        <textarea class="form-control" id="content" name="content" rows="5" 
                                  placeholder="{{ __('messages.tell_us_about_experience') }}" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-oweru-gold">{{ __('messages.submit_testimonial') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth

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

.btn-oweru-outline {
    border: 2px solid var(--oweru-gold);
    color: var(--oweru-gold);
    background: transparent;
    font-weight: 600;
}

.btn-oweru-outline:hover {
    background: var(--oweru-gold);
    color: var(--oweru-dark);
}

/* Testimonials Styles */
.testimonials-container {
    max-width: 800px;
    margin: 0 auto;
    position: relative;
}

.testimonials-slider {
    position: relative;
    height: 300px;
    overflow: hidden;
}

.testimonial-card {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    opacity: 0;
    transform: translateX(50px);
    transition: all 0.8s ease;
    pointer-events: none;
}

.testimonial-card.active {
    opacity: 1;
    transform: translateX(0);
    pointer-events: all;
}

.testimonial-content {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2.5rem;
    text-align: center;
    box-shadow: 0 15px 40px rgba(9, 23, 42, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.rating-stars {
    font-size: 1.5rem;
}

.testimonial-text {
    font-size: 1.2rem;
    line-height: 1.6;
    color: var(--oweru-dark);
    font-style: italic;
    margin-bottom: 2rem;
    font-family: 'Poppins', sans-serif;
}

.testimonial-author {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--oweru-gradient-gold);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--oweru-dark);
    font-weight: 600;
    font-size: 1.2rem;
}

.author-name {
    color: var(--oweru-dark);
    margin: 0;
}

.author-role {
    font-size: 0.9rem;
}

.testimonial-dots {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--oweru-gray);
    cursor: pointer;
    transition: all 0.3s ease;
}

.dot.active {
    background: var(--oweru-gold);
    transform: scale(1.2);
}

.dot:hover {
    background: var(--oweru-gold);
}

/* Rating Input Styles */
.rating-input {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.rating-input input[type="radio"] {
    display: none;
}

.star-label {
    font-size: 2rem;
    color: var(--oweru-gray);
    cursor: pointer;
    transition: color 0.2s ease;
}

.rating-input input[type="radio"]:checked ~ .star-label,
.star-label:hover,
.star-label:hover ~ .star-label {
    color: var(--oweru-gold);
}

.rating-input input[type="radio"]:checked + .star-label {
    color: var(--oweru-gold);
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
    
    .testimonial-content {
        padding: 1.5rem;
    }
    
    .testimonial-text {
        font-size: 1rem;
    }
    
    .testimonials-slider {
        height: 350px;
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
    
    // Testimonials functionality
const testimonialCards = document.querySelectorAll('.testimonial-card');
const dots = document.querySelectorAll('.dot');

// Only initialize testimonials if they exist on the page
if (testimonialCards.length > 0 && dots.length > 0) {
    let currentTestimonial = 0;
    let testimonialInterval;
    
    function showTestimonial(index) {
        // Hide all testimonials
        testimonialCards.forEach(card => card.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Show selected testimonial (with bounds checking)
        if (testimonialCards[index]) {
            testimonialCards[index].classList.add('active');
        }
        if (dots[index]) {
            dots[index].classList.add('active');
        }
        currentTestimonial = index;
    }
    
    function nextTestimonial() {
        const nextIndex = (currentTestimonial + 1) % testimonialCards.length;
        showTestimonial(nextIndex);
    }
    
    // Start automatic rotation (3 seconds) only if there are multiple testimonials
    if (testimonialCards.length > 1) {
        testimonialInterval = setInterval(nextTestimonial, 3000);
        
        // Add click event to dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                clearInterval(testimonialInterval);
                showTestimonial(index);
                // Restart automatic rotation
                testimonialInterval = setInterval(nextTestimonial, 3000);
            });
        });
        
        // Pause rotation on hover
        const testimonialsContainer = document.querySelector('.testimonials-slider');
        if (testimonialsContainer) {
            testimonialsContainer.addEventListener('mouseenter', () => {
                clearInterval(testimonialInterval);
            });
            
            testimonialsContainer.addEventListener('mouseleave', () => {
                testimonialInterval = setInterval(nextTestimonial, 3000);
            });
        }
    } else {
        // If only one testimonial, ensure it's visible
        showTestimonial(0);
    }
} else {
    console.log('No testimonials found on this page');
}

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

// Observe testimonials - observe all testimonial cards
document.querySelectorAll('.testimonial-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    card.style.transition = 'all 0.8s ease';
    observer.observe(card);
});
});
</script>
@endsection