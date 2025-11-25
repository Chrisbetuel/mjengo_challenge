@extends('layouts.app')

@section('title', 'Create Group')

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

    <!-- Create Group Section -->
    <div class="row g-4">
        <div class="col-12">
            <div class="dashboard-section">
                <div class="section-header d-flex justify-content-between align-items-center mb-4">
                    <h3 class="futura-font fw-bold text-white mb-0">
                        <i class="fas fa-users me-2"></i>Create New Group
                    </h3>
                </div>

                <div class="bg-white p-4 rounded-lg shadow">
                    <form action="{{ route('groups.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-oweru-dark mb-2 futura-font">Group Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-oweru-gray rounded-md focus:outline-none focus:ring-2 focus:ring-oweru-gold" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-oweru-dark mb-2 futura-font">Description</label>
                            <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-oweru-gray rounded-md focus:outline-none focus:ring-2 focus:ring-oweru-gold" placeholder="Describe your group's purpose and goals...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-oweru-dark mb-2 futura-font">Challenge</label>
                            <select id="challenge_id" name="challenge_id" class="w-full px-3 py-2 border border-oweru-gray rounded-md focus:outline-none focus:ring-2 focus:ring-oweru-gold">
                                <option value="">Select a challenge (optional)</option>
                                @foreach($challenges as $challenge)
                                    <option value="{{ $challenge->id }}" {{ old('challenge_id') == $challenge->id ? 'selected' : '' }}>
                                        {{ $challenge->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="toggle-new-challenge" class="mt-2 text-oweru-blue hover:text-oweru-gold text-sm futura-font">
                                Or create a new challenge
                            </button>
                            @error('challenge_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Challenge Form (Hidden by default) -->
                        <div id="new-challenge-form" class="hidden bg-oweru-light p-4 rounded-lg mb-4">
                            <h3 class="text-lg font-medium mb-4 futura-font text-oweru-dark">Create New Challenge</h3>

                            <div class="mb-4">
                                <label for="challenge_name" class="block text-sm font-medium text-oweru-dark mb-2 futura-font">Challenge Name</label>
                                <input type="text" id="challenge_name" name="challenge_name" value="{{ old('challenge_name') }}" class="w-full px-3 py-2 border border-oweru-gray rounded-md focus:outline-none focus:ring-2 focus:ring-oweru-gold">
                                @error('challenge_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="challenge_description" class="block text-sm font-medium text-oweru-dark mb-2 futura-font">Challenge Description</label>
                                <textarea id="challenge_description" name="challenge_description" rows="3" class="w-full px-3 py-2 border border-oweru-gray rounded-md focus:outline-none focus:ring-2 focus:ring-oweru-gold" placeholder="Describe the challenge goals...">{{ old('challenge_description') }}</textarea>
                                @error('challenge_description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-oweru-dark mb-2 futura-font">Start Date</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="w-full px-3 py-2 border border-oweru-gray rounded-md focus:outline-none focus:ring-2 focus:ring-oweru-gold">
                                    @error('start_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-oweru-dark mb-2 futura-font">End Date</label>
                                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="w-full px-3 py-2 border border-oweru-gray rounded-md focus:outline-none focus:ring-2 focus:ring-oweru-gold">
                                    @error('end_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="target_amount" class="block text-sm font-medium text-oweru-dark mb-2 futura-font">Target Amount (TZS)</label>
                                    <input type="number" id="target_amount" name="target_amount" min="1" step="0.01" value="{{ old('target_amount') }}" class="w-full px-3 py-2 border border-oweru-gray rounded-md focus:outline-none focus:ring-2 focus:ring-oweru-gold">
                                    @error('target_amount')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="max_participants" class="block text-sm font-medium text-oweru-dark mb-2 futura-font">Max Participants</label>
                                    <input type="number" id="max_participants" name="max_participants" min="2" max="100" value="{{ old('max_participants', 10) }}" class="w-full px-3 py-2 border border-oweru-gray rounded-md focus:outline-none focus:ring-2 focus:ring-oweru-gold">
                                    @error('max_participants')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="max_members" class="block text-sm font-medium text-oweru-dark mb-2 futura-font">Maximum Members</label>
                            <input type="number" id="max_members" name="max_members" min="2" max="50" value="{{ old('max_members', 10) }}" class="w-full px-3 py-2 border border-oweru-gray rounded-md focus:outline-none focus:ring-2 focus:ring-oweru-gold" required>
                            @error('max_members')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800 futura-font">
                                        Group Approval Required
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700 poppins-font">
                                        <p>Your group will be created with a "pending" status and will not be visible to other users until approved by an administrator.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" class="btn btn-oweru-gold text-oweru-dark futura-font fw-bold">
                                Create Group
                            </button>
                            <a href="{{ route('groups.index') }}" class="btn btn-oweru-gray text-white futura-font fw-bold">
                                Cancel
                            </a>
                        </div>
                    </form>
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

    // Toggle new challenge form
    document.getElementById('toggle-new-challenge').addEventListener('click', function() {
        const form = document.getElementById('new-challenge-form');
        const select = document.getElementById('challenge_id');

        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
            select.value = ''; // Clear selection when creating new challenge
            this.textContent = 'Use existing challenge instead';
        } else {
            form.classList.add('hidden');
            this.textContent = 'Or create a new challenge';
        }
    });

    // Auto-hide new challenge form if existing challenge is selected
    document.getElementById('challenge_id').addEventListener('change', function() {
        const form = document.getElementById('new-challenge-form');
        const button = document.getElementById('toggle-new-challenge');

        if (this.value !== '') {
            form.classList.add('hidden');
            button.textContent = 'Or create a new challenge';
        }
    });
});
</script>
@endsection
