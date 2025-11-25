@extends('layouts.app')

@section('no_sidebar', true)

@section('title', 'Create Challenge - Mjengo Challenge')

@section('content')
<style>
    /* Mjengo Admin Create Challenge Styles */
    :root {
        --mjengo-primary: #2c3e50;
        --mjengo-secondary: #3498db;
        --mjengo-accent: #e74c3c;
        --mjengo-success: #27ae60;
        --mjengo-warning: #f39c12;
        --mjengo-info: #17a2b8;
        --mjengo-light: #ecf0f1;
        --mjengo-dark: #2c3e50;
        --mjengo-gray: #95a5a6;
        --mjengo-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --mjengo-gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .create-challenge-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        border: none;
        overflow: hidden;
    }

    .card-header-gradient {
        background: var(--mjengo-gradient);
        border: none;
        padding: 2rem;
    }

    .form-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border: none;
        margin-bottom: 2rem;
    }

    .preview-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border: none;
        border-radius: 15px;
        color: white;
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--mjengo-secondary);
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        transform: translateY(-2px);
    }

    .form-control.is-valid {
        border-color: var(--mjengo-success);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2327ae60' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    }

    .form-label {
        font-weight: 600;
        color: var(--mjengo-dark);
        margin-bottom: 0.5rem;
    }

    .required-star {
        color: var(--mjengo-accent);
    }

    .btn-primary {
        background: var(--mjengo-gradient);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-outline-secondary {
        border: 2px solid var(--mjengo-gray);
        border-radius: 12px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background-color: var(--mjengo-gray);
        color: white;
        transform: translateY(-2px);
    }

    .preview-badge {
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .stat-card {
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }

    .floating-label {
        position: relative;
    }

    .floating-label .form-control {
        padding-top: 1.5rem;
    }

    .floating-label label {
        position: absolute;
        top: 0.5rem;
        left: 1rem;
        font-size: 0.8rem;
        color: var(--mjengo-gray);
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .floating-label .form-control:focus + label,
    .floating-label .form-control:not(:placeholder-shown) + label {
        top: 0.2rem;
        font-size: 0.7rem;
        color: var(--mjengo-secondary);
    }

    .feature-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: var(--mjengo-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .animate-preview {
        transition: all 0.5s ease;
    }

    .animate-preview.update {
        animation: pulse 0.6s ease;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }

    /* Hide sidebar completely */
    .sidebar {
        display: none !important;
    }

    .col-md-3, .col-lg-2 {
        display: none !important;
    }

    .col-md-9, .col-lg-10 {
        width: 100% !important;
        margin-left: 0 !important;
        padding-left: 0 !important;
    }
</style>

<div class="create-challenge-page">
    <div class="container">
        <!-- Header Section -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <div class="text-white mb-3">
                    <i class="fas fa-trophy fa-3x mb-3"></i>
                    <h1 class="display-5 fw-bold">Create New Challenge</h1>
                    <p class="lead opacity-90">Start a new savings journey for your community</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <!-- Main Form Card -->
                <div class="main-card">
                    <!-- Card Header -->
                    <div class="card-header-gradient text-white">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="h4 mb-2 fw-bold">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Challenge Details
                                </h2>
                                <p class="mb-0 opacity-90">Fill in the details to create your new savings challenge</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <a href="{{ route('admin.challenges') }}" class="btn btn-light btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" title="Back to Challenges List">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Challenges
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4">
                        <form action="{{ route('admin.challenges.store') }}" method="POST" aria-label="Create Challenge Form">
                            @csrf

                            <!-- Basic Information -->
                            <div class="form-card p-4 mb-4">
                                <h4 class="mb-4 text-primary">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Basic Information
                                </h4>
                                
                                <div class="row gy-4">
                                    <div class="col-md-6">
                                        <div class="floating-label">
                                            <input type="text" class="form-control @error('name') is-invalid @else is-valid @enderror"
                                                   id="name" name="name" value="{{ old('name') }}"
                                                   placeholder=" " required aria-required="true"
                                                   aria-describedby="nameHelp" aria-invalid="@error('name')true@else false @enderror">
                                            <label for="name">Challenge Name <span class="required-star">*</span></label>
                                            <div id="nameHelp" class="form-text text-muted mt-2">Give your challenge a descriptive and engaging name</div>
                                            @error('name')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="floating-label">
                                            <input type="number" class="form-control @error('daily_amount') is-invalid @else is-valid @enderror"
                                                   id="daily_amount" name="daily_amount" value="{{ old('daily_amount') }}"
                                                   placeholder=" " min="1000" step="100" required aria-required="true"
                                                   aria-describedby="amountHelp"
                                                   aria-invalid="@error('daily_amount')true@else false @enderror">
                                            <label for="daily_amount">Daily Amount (TZS) <span class="required-star">*</span></label>
                                            @error('daily_amount')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text text-muted mt-2" id="amountHelp">Minimum amount is 1,000 TZS</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gy-4 mt-2">
                                    <div class="col-md-4">
                                        <div class="floating-label">
                                            <input type="number" class="form-control @error('max_participants') is-invalid @else is-valid @enderror"
                                                   id="max_participants" name="max_participants" value="{{ old('max_participants', 30) }}"
                                                   placeholder=" " min="1" max="90" required aria-required="true"
                                                   aria-describedby="participantsHelp"
                                                   aria-invalid="@error('max_participants')true@else false @enderror">
                                            <label for="max_participants">Max Participants <span class="required-star">*</span></label>
                                            @error('max_participants')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text text-muted mt-2" id="participantsHelp">Between 1 and 90 participants</div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="floating-label">
                                            <input type="date" class="form-control @error('start_date') is-invalid @else is-valid @enderror"
                                                   id="start_date" name="start_date" value="{{ old('start_date') }}"
                                                   min="{{ date('Y-m-d') }}" required aria-required="true"
                                                   aria-invalid="@error('start_date')true@else false @enderror">
                                            <label for="start_date">Start Date <span class="required-star">*</span></label>
                                            @error('start_date')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="floating-label">
                                            <input type="date" class="form-control @error('end_date') is-invalid @else is-valid @enderror"
                                                   id="end_date" name="end_date" value="{{ old('end_date') }}"
                                                   min="{{ date('Y-m-d') }}" required aria-required="true"
                                                   aria-invalid="@error('end_date')true@else false @enderror">
                                            <label for="end_date">End Date <span class="required-star">*</span></label>
                                            @error('end_date')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description Section -->
                            <div class="form-card p-4 mb-4">
                                <h4 class="mb-4 text-primary">
                                    <i class="fas fa-align-left me-2"></i>
                                    Challenge Description
                                </h4>
                                <div class="floating-label">
                                    <textarea class="form-control @error('description') is-invalid @else is-valid @enderror"
                                              id="description" name="description" rows="4"
                                              placeholder=" " required aria-required="true"
                                              aria-describedby="descriptionHelp" aria-invalid="@error('description')true@else false @enderror">{{ old('description') }}</textarea>
                                    <label for="description">Challenge Description <span class="required-star">*</span></label>
                                    <div id="descriptionHelp" class="form-text text-muted mt-2">Describe the challenge objectives, rules, benefits, and any special instructions</div>
                                    @error('description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-12 d-flex gap-3">
                                    <button type="submit" class="btn btn-primary btn-lg px-5" aria-label="Create Challenge Button">
                                        <i class="fas fa-plus me-2"></i>Create Challenge
                                    </button>
                                    <a href="{{ route('admin.challenges') }}" class="btn btn-outline-secondary btn-lg px-5" aria-label="Cancel Button">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Live Preview Card -->
                <div class="preview-card mt-4 p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-3 fw-bold">
                                <i class="fas fa-eye me-2"></i>
                                Live Preview
                            </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="stat-card">
                                        <h5 id="preview-name" class="fw-bold mb-2">Challenge Name</h5>
                                        <p class="small opacity-90 mb-3" id="preview-description">Challenge description will appear here...</p>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <span class="preview-badge" id="preview-amount">0 TZS/day</span>
                                            <span class="preview-badge" id="preview-participants">0 participants</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="stat-card">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <small class="opacity-90">Start Date</small>
                                                <div class="fw-bold" id="preview-start">Not set</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="opacity-90">End Date</small>
                                                <div class="fw-bold" id="preview-end">Not set</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="opacity-90">Duration</small>
                                                <div class="fw-bold" id="preview-duration">0 days</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="opacity-90">Total per Person</small>
                                                <div class="fw-bold" id="preview-total">0 TZS</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="feature-icon mx-auto">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h5 class="fw-bold mt-2">Ready to Launch</h5>
                            <p class="small opacity-90">Your challenge preview looks great!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, textarea');
    const previewElements = {
        name: document.getElementById('preview-name'),
        description: document.getElementById('preview-description'),
        amount: document.getElementById('preview-amount'),
        participants: document.getElementById('preview-participants'),
        start: document.getElementById('preview-start'),
        end: document.getElementById('preview-end'),
        duration: document.getElementById('preview-duration'),
        total: document.getElementById('preview-total')
    };

    function animatePreviewUpdate(element) {
        element.classList.add('animate-preview', 'update');
        setTimeout(() => {
            element.classList.remove('update');
        }, 600);
    }

    function formatDate(dateString) {
        if (!dateString) return 'Not set';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    }

    function updatePreview() {
        const name = document.getElementById('name').value || 'Challenge Name';
        const description = document.getElementById('description').value || 'Challenge description will appear here...';
        const amount = parseFloat(document.getElementById('daily_amount').value) || 0;
        const participants = parseInt(document.getElementById('max_participants').value) || 0;
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        // Update preview elements with animation
        if(previewElements.name.textContent !== name) {
            previewElements.name.textContent = name;
            animatePreviewUpdate(previewElements.name);
        }
        if(previewElements.description.textContent !== description) {
            previewElements.description.textContent = description;
            animatePreviewUpdate(previewElements.description);
        }
        if(previewElements.amount.textContent !== `${amount.toLocaleString()} TZS/day`) {
            previewElements.amount.textContent = `${amount.toLocaleString()} TZS/day`;
            animatePreviewUpdate(previewElements.amount);
        }
        if(previewElements.participants.textContent !== `${participants} participants`) {
            previewElements.participants.textContent = `${participants} participants`;
            animatePreviewUpdate(previewElements.participants);
        }

        const formattedStart = formatDate(startDate);
        if(previewElements.start.textContent !== formattedStart) {
            previewElements.start.textContent = formattedStart;
            animatePreviewUpdate(previewElements.start);
        }

        const formattedEnd = formatDate(endDate);
        if(previewElements.end.textContent !== formattedEnd) {
            previewElements.end.textContent = formattedEnd;
            animatePreviewUpdate(previewElements.end);
        }

        // Calculate duration and total
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            
            if(previewElements.duration.textContent !== `${diffDays} days`) {
                previewElements.duration.textContent = `${diffDays} days`;
                animatePreviewUpdate(previewElements.duration);
            }

            const totalExpected = (amount * diffDays).toLocaleString();
            if(previewElements.total.textContent !== `${totalExpected} TZS`) {
                previewElements.total.textContent = `${totalExpected} TZS`;
                animatePreviewUpdate(previewElements.total);
            }
        } else {
            if(previewElements.duration.textContent !== '0 days') {
                previewElements.duration.textContent = '0 days';
                animatePreviewUpdate(previewElements.duration);
            }
            if(previewElements.total.textContent !== '0 TZS') {
                previewElements.total.textContent = '0 TZS';
                animatePreviewUpdate(previewElements.total);
            }
        }
    }

    // Add event listeners to all inputs
    inputs.forEach(input => {
        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Set minimum end date based on start date
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const minEndDate = new Date(startDate);
        minEndDate.setDate(minEndDate.getDate() + 1);
        
        document.getElementById('end_date').min = minEndDate.toISOString().split('T')[0];
        
        if (!document.getElementById('end_date').value || new Date(document.getElementById('end_date').value) <= startDate) {
            const defaultEndDate = new Date(startDate);
            defaultEndDate.setDate(defaultEndDate.getDate() + 30);
            document.getElementById('end_date').value = defaultEndDate.toISOString().split('T')[0];
        }
        
        updatePreview();
    });

    // Initial preview update
    updatePreview();
});
</script>
@endpush
@endsection