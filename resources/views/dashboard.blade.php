@extends('layouts.app')

@section('title', 'Dashboard - Mjengo Challenge')

@section('content')
<style>
    /* Dashboard Styles */
    :root {
        --oweru-dark: #09172A;
        --oweru-gold: #C89128;
        --oweru-light: #F8F8F9;
        --oweru-blue: #2D3A58;
        --oweru-secondary: #E5B972;
        --oweru-gray: #889898;
    }

    .dashboard-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .welcome-section {
        background: linear-gradient(135deg, var(--oweru-dark) 0%, var(--oweru-blue) 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        transition: all 0.3s ease;
        text-align: center;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin: 0 auto 1rem;
    }

    .stats-icon.gold { background: var(--oweru-gold); }
    .stats-icon.blue { background: var(--oweru-blue); }
    .stats-icon.green { background: var(--oweru-secondary); }
    .stats-icon.purple { background: var(--oweru-dark); }

    .challenge-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .challenge-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .material-card {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.5rem;
        border-left: 4px solid var(--oweru-gold);
        transition: all 0.3s ease;
    }

    .material-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .feedback-form {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .btn-oweru-gold {
        background: var(--oweru-gold);
        border: none;
        color: var(--oweru-dark);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-oweru-gold:hover {
        background: #b58120;
        color: var(--oweru-dark);
        transform: translateY(-2px);
    }

    .progress {
        height: 8px;
        border-radius: 4px;
    }

    .section-title {
        color: var(--oweru-dark);
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--oweru-gray);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .navigation-links {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .nav-link-item {
        display: inline-block;
        margin: 0.25rem;
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .nav-link-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .nav-link-item.dashboard { background: var(--oweru-gold); color: var(--oweru-dark); }
    .nav-link-item.challenges { background: var(--oweru-blue); color: white; }
    .nav-link-item.materials { background: var(--oweru-secondary); color: var(--oweru-dark); }
    .nav-link-item.groups { background: var(--oweru-dark); color: white; }
    .nav-link-item.logout { background: #dc3545; color: white; }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem 0;
        }

        .stats-card {
            margin-bottom: 1rem;
        }

        .navigation-links {
            padding: 1rem;
        }

        .nav-link-item {
            display: block;
            margin: 0.5rem 0;
            text-align: center;
        }
    }
</style>

<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="mb-2">Welcome back, {{ Auth::user()->username }}!</h1>
                    <p class="mb-0 opacity-75">Here's your progress overview and quick actions.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex align-items-center justify-content-lg-end gap-2">
                        <i class="fas fa-calendar text-warning"></i>
                        <span>{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="navigation-links">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-2">
                        <a href="{{ route('dashboard') }}" class="nav-link-item dashboard">
                            <i class="fas fa-chart-line me-2"></i>Dashboard
                        </a>
                        <a href="{{ route('challenges.index') }}" class="nav-link-item challenges">
                            <i class="fas fa-trophy me-2"></i>Challenges
                        </a>
                        <a href="{{ route('materials.index') }}" class="nav-link-item materials">
                            <i class="fas fa-shopping-cart me-2"></i>Materials
                        </a>
                        <a href="{{ route('penalties.index') }}" class="nav-link-item" style="background: #dc3545; color: white;">
                            <i class="fas fa-exclamation-triangle me-2"></i>Penalties
                        </a>
                        <a href="{{ route('groups.index') }}" class="nav-link-item groups">
                            <i class="fas fa-users me-2"></i>Groups
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link-item logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="stats-card">
                    <div class="stats-icon gold">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3 class="mb-2">{{ $activeChallenges->count() }}</h3>
                    <p class="mb-0 text-muted">Active Challenges</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="stats-card">
                    <div class="stats-icon blue">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3 class="mb-2">TZS {{ number_format($totalSavings, 2) }}</h3>
                    <p class="mb-0 text-muted">Total Savings</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="stats-card">
                    <div class="stats-icon green">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h3 class="mb-2">{{ $lipaKidogoPlans->count() }}</h3>
                    <p class="mb-0 text-muted">Active Plans</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a href="{{ route('penalties.index') }}" class="text-decoration-none">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: #dc3545;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3 class="mb-2">{{ Auth::user()->penalties()->whereIn('status', ['active', 'overdue'])->count() }}</h3>
                        <p class="mb-0 text-muted">Active Penalties</p>
                        <small class="text-danger">Click to view details</small>
                    </div>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Active Challenges -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="challenge-card">
                    <h4 class="section-title">
                        <i class="fas fa-star"></i>My Active Challenges
                    </h4>
                    @if($activeChallenges->count() > 0)
                        @foreach($activeChallenges as $participant)
                            <div class="mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $participant->challenge->name }}</h6>
                                    <span class="badge bg-success">{{ ucfirst($participant->status) }}</span>
                                </div>
                                <p class="text-muted small mb-2">{{ $participant->challenge->description }}</p>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small class="text-muted">Daily Amount</small>
                                        <div class="fw-bold">TZS {{ number_format($participant->challenge->daily_amount, 2) }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Duration</small>
                                        <div class="fw-bold">{{ $participant->challenge->start_date->format('M d') }} - {{ $participant->challenge->end_date->format('M d') }}</div>
                                    </div>
                                </div>
                                @php
                                    $paid = $participant->getTotalPaid();
                                    $totalDays = $participant->challenge->start_date->diffInDays($participant->challenge->end_date);
                                    $currentDays = $participant->challenge->start_date->diffInDays(now());
                                    $progress = $totalDays > 0 ? min(100, ($currentDays / $totalDays) * 100) : 0;
                                    $debtBreakdown = $participant->debt_breakdown ?? $participant->getDebtBreakdown();
                                @endphp
                                <div class="mt-2">
                                    <small class="text-muted">Progress: {{ round($progress) }}% (TZS {{ number_format($paid, 2) }} paid)</small>
                                    <div class="progress mt-1">
                                        <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>

                                @if($participant->has_debt)
                                <div class="mt-2 p-2 bg-warning bg-opacity-10 border border-warning rounded">
                                    <small class="text-warning fw-bold">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Debt: TZS {{ number_format($debtBreakdown['accumulated_debt'], 2) }}
                                        ({{ $debtBreakdown['missed_days'] }} missed days)
                                    </small>
                                </div>
                                @endif
                                <div class="mt-3 d-flex gap-2">
                                    <a href="{{ route('challenges.show', $participant->challenge) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                    
                                </div>

                                <!-- Payment Modal -->
                                <div class="modal fade" id="paymentModal{{ $participant->id }}" tabindex="-1" aria-labelledby="paymentModalLabel{{ $participant->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="paymentModalLabel{{ $participant->id }}">Pay for {{ $participant->challenge->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <form action="{{ route('challenges.payment', $participant->challenge->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="payment_type" value="direct">
                                                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                                                <i class="fas fa-credit-card me-2"></i>
                                                                Pay Direct<br>
                                                                <small>TZS {{ number_format($participant->challenge->daily_amount, 2) }}</small>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <div class="col-12">
                                                        <form action="{{ route('challenges.payment', $participant->challenge->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="payment_type" value="lipa_kidogo">
                                                            <button type="submit" class="btn btn-success btn-lg w-100">
                                                                <i class="fas fa-calendar-alt me-2"></i>
                                                                Lipa Kidogo<br>
                                                                <small>{{ $participant->challenge->duration_days }} installments</small>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Lipa Kidogo allows you to pay the full challenge amount in installments over {{ $participant->challenge->duration_days }} days.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-trophy"></i>
                            <p>No active challenges yet. Join a challenge to start saving!</p>
                            <a href="{{ route('challenges.index') }}" class="btn btn-oweru-gold btn-sm">Browse Challenges</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available Challenges -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="challenge-card">
                    <h4 class="section-title">
                        <i class="fas fa-plus-circle"></i>Available Challenges
                    </h4>
                    @if($availableChallenges->count() > 0)
                        @foreach($availableChallenges->take(3) as $challenge)
                            <div class="mb-3 p-3 border rounded">
                                <h6 class="mb-1">{{ $challenge->name }}</h6>
                                <p class="text-muted small mb-2">{{ Str::limit($challenge->description, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">TZS {{ number_format($challenge->daily_amount, 2) }}/day</span>
                                    <a href="{{ route('challenges.show', $challenge) }}" class="btn btn-oweru-gold btn-sm">Join Now</a>
                                </div>
                            </div>
                        @endforeach
                        @if($availableChallenges->count() > 3)
                            <div class="text-center mt-3">
                                <a href="{{ route('challenges.index') }}" class="btn btn-outline-primary btn-sm">View All Challenges</a>
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <p>No challenges available at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Payments and Materials Row -->
        <div class="row mb-4">
            <!-- Recent Payments -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="challenge-card">
                    <h4 class="section-title">
                        <i class="fas fa-credit-card"></i>Recent Payments
                    </h4>
                    @if($recentPayments->count() > 0)
                        @foreach($recentPayments as $payment)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                                <div>
                                    <div class="fw-bold">{{ $payment->participant->challenge->name }}</div>
                                    <small class="text-muted">{{ $payment->created_at->format('M d, Y') }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success">TZS {{ number_format($payment->amount, 2) }}</div>
                                    <small class="badge bg-{{ $payment->status == 'paid' ? 'success' : 'warning' }}">{{ ucfirst($payment->status) }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-receipt"></i>
                            <p>No recent payments found.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available Materials -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="challenge-card">
                    <h4 class="section-title">
                        <i class="fas fa-tools"></i>Available Materials
                    </h4>
                    @if($challengeMaterials->count() > 0)
                        @foreach($challengeMaterials->take(4) as $material)
                            <div class="material-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $material->name }}</h6>
                                        <p class="text-muted small mb-1">{{ Str::limit($material->description, 60) }}</p>
                                        <div class="fw-bold text-primary">TZS {{ number_format($material->price, 2) }}</div>
                                    </div>
                                    <a href="{{ route('materials.show', $material) }}" class="btn btn-oweru-gold btn-sm">View</a>
                                </div>
                            </div>
                        @endforeach
                        @if($challengeMaterials->count() > 4)
                            <div class="text-center mt-3">
                                <a href="{{ route('materials.index') }}" class="btn btn-outline-primary btn-sm">View All Materials</a>
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="fas fa-tools"></i>
                            <p>No materials available for your challenges.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Purchases and Feedback Row -->
        <div class="row">
            <!-- Direct Purchases -->
            <div class="col-12 col-lg-4 mb-4">
                <div class="challenge-card">
                    <h4 class="section-title">
                        <i class="fas fa-credit-card"></i>My Direct Purchases
                    </h4>
                    @if($directPurchases->count() > 0)
                        @foreach($directPurchases as $purchase)
                            <div class="mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $purchase->material->name }}</h6>
                                    <span class="badge bg-{{ $purchase->status == 'paid' ? 'success' : ($purchase->status == 'shipped' ? 'info' : ($purchase->status == 'delivered' ? 'primary' : 'warning')) }}">{{ ucfirst($purchase->status) }}</span>
                                </div>
                                <p class="text-muted small mb-2">{{ Str::limit($purchase->material->description, 60) }}</p>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small class="text-muted">Quantity</small>
                                        <div class="fw-bold">{{ $purchase->quantity }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Total</small>
                                        <div class="fw-bold text-success">TZS {{ number_format($purchase->total_amount, 2) }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">{{ $purchase->created_at->format('M d, Y') }}</small>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('direct_purchases.show', $purchase->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-credit-card"></i>
                            <p>No direct purchases yet. Buy materials with full payment!</p>
                            <a href="{{ route('materials.index') }}" class="btn btn-oweru-gold btn-sm">Browse Materials</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Lipa Kidogo Plans -->
            <div class="col-12 col-lg-4 mb-4">
                <div class="challenge-card">
                    <h4 class="section-title">
                        <i class="fas fa-calendar-alt"></i>My Lipa Kidogo Plans
                    </h4>
                    @if($lipaKidogoPlans->count() > 0)
                        @foreach($lipaKidogoPlans as $plan)
                            <div class="mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $plan->material->name }}</h6>
                                    <span class="badge bg-{{ $plan->status == 'active' ? 'success' : ($plan->status == 'completed' ? 'primary' : 'warning') }}">{{ ucfirst($plan->status) }}</span>
                                </div>
                                <p class="text-muted small mb-2">{{ Str::limit($plan->material->description, 60) }}</p>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small class="text-muted">Installments</small>
                                        <div class="fw-bold">{{ $plan->num_installments }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Per Installment</small>
                                        <div class="fw-bold text-success">TZS {{ number_format($plan->installment_amount, 2) }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Paid: TZS {{ number_format($plan->paid_amount, 2) }} / TZS {{ number_format($plan->total_amount, 2) }}</small>
                                    @php
                                        $progress = $plan->total_amount > 0 ? ($plan->paid_amount / $plan->total_amount) * 100 : 0;
                                    @endphp
                                    <div class="progress mt-1" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">{{ $plan->created_at->format('M d, Y') }}</small>
                                </div>
                                <div class="mt-3 d-flex gap-2">
                                    <a href="{{ route('lipa_kidogo.show', $plan->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View Plan
                                    </a>
                                    @if($plan->status == 'active')
                                        @php
                                            $nextInstallment = $plan->installments()->where('status', 'pending')->orderBy('due_date')->first();
                                        @endphp
                                        @if($nextInstallment)
                                            <form action="{{ route('lipa_kidogo.pay_installment', $plan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-credit-card me-1"></i>Pay Installment
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('lipa_kidogo.index') }}" class="btn btn-outline-primary btn-sm">View All Plans</a>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-alt"></i>
                            <p>No Lipa Kidogo plans yet. Buy materials in installments!</p>
                            <a href="{{ route('materials.index') }}" class="btn btn-oweru-gold btn-sm">Browse Materials</a>
                        </div>
                    @endif
                </div>
            </div>



            <!-- Feedback Form -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="feedback-form">
                    <h4 class="section-title mb-4">
                        <i class="fas fa-comments"></i>Share Your Feedback
                    </h4>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <form action="{{ route('dashboard.feedback.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Feedback Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Select type...</option>
                                <option value="general">General Feedback</option>
                                <option value="challenge">Challenge Related</option>
                                <option value="material">Material Purchase</option>
                                <option value="payment">Payment Issues</option>
                                <option value="technical">Technical Support</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-oweru-gold w-100">Submit Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
