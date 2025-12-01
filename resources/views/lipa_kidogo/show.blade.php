@extends('layouts.app')

@section('title', 'Lipa Kidogo Plan Details - Mjengo Challenge')

@section('content')
<style>
    /* Lipa Kidogo Show Styles */
    :root {
        --oweru-dark: #09172A;
        --oweru-gold: #C89128;
        --oweru-light: #F8F8F9;
        --oweru-blue: #2D3A58;
        --oweru-secondary: #E5B972;
        --oweru-gray: #889898;
    }

    .lipa-kidogo-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .plan-header {
        background: linear-gradient(135deg, var(--oweru-dark) 0%, var(--oweru-blue) 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .plan-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-completed {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-overdue {
        background: #f8d7da;
        color: #721c24;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .installment-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .installment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .installment-paid {
        border-color: #28a745;
        background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
    }

    .installment-pending {
        border-color: #ffc107;
        background: linear-gradient(135deg, #fffef8 0%, #fefae6 100%);
    }

    .installment-overdue {
        border-color: #dc3545;
        background: linear-gradient(135deg, #fff8f8 0%, #feeaea 100%);
    }

    .installment-failed {
        border-color: #6c757d;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .progress-bar {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--oweru-gold) 0%, var(--oweru-secondary) 100%);
        transition: width 0.3s ease;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--oweru-dark);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--oweru-gray);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .btn-gold {
        background: linear-gradient(135deg, var(--oweru-gold) 0%, var(--oweru-secondary) 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-gold:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(200, 145, 40, 0.3);
        color: white;
    }

    .material-info {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .section-title {
        color: var(--oweru-dark);
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--oweru-gold);
    }
</style>

<div class="lipa-kidogo-container">
    <div class="container">
        <!-- Plan Header -->
        <div class="plan-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">
                        <i class="fas fa-shopping-bag me-2"></i>
                        {{ $lipaKidogo->material->name }}
                    </h2>
                    <p class="mb-0 opacity-75">
                        Lipa Kidogo Plan - Started {{ $lipaKidogo->created_at->format('M j, Y') }}
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="status-badge status-{{ $lipaKidogo->status }}">
                        {{ ucfirst($lipaKidogo->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Payment Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-value">TZS {{ number_format($lipaKidogo->total_amount) }}</div>
                    <div class="stat-label">Total Amount</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-value">TZS {{ number_format($lipaKidogo->paid_amount ?? 0) }}</div>
                    <div class="stat-label">Amount Paid</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-value">TZS {{ number_format($lipaKidogo->total_amount - ($lipaKidogo->paid_amount ?? 0)) }}</div>
                    <div class="stat-label">Remaining</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-value">{{ $lipaKidogo->installments->count() }}</div>
                    <div class="stat-label">Total Installments</div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="plan-card">
            <h4 class="section-title">
                <i class="fas fa-chart-line"></i>
                Payment Progress
            </h4>
            <div class="progress-bar mb-3">
                <div class="progress-fill" style="width: {{ $lipaKidogo->paid_amount ? min(100, ($lipaKidogo->paid_amount / $lipaKidogo->total_amount) * 100) : 0 }}%"></div>
            </div>
            <div class="row text-center">
                <div class="col-md-4">
                    <strong>{{ $lipaKidogo->installments->where('status', 'paid')->count() }}</strong> of {{ $lipaKidogo->installments->count() }} installments paid
                </div>
                <div class="col-md-4">
                    <strong>{{ number_format($lipaKidogo->paid_amount ? ($lipaKidogo->paid_amount / $lipaKidogo->total_amount) * 100 : 0, 1) }}%</strong> complete
                </div>
                <div class="col-md-4">
                    <strong>{{ $lipaKidogo->installments->where('status', 'pending')->count() }}</strong> installments remaining
                </div>
            </div>
        </div>

        <!-- Material Information -->
        <div class="material-info">
            <h4 class="section-title">
                <i class="fas fa-info-circle"></i>
                Material Details
            </h4>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Material:</strong> {{ $lipaKidogo->material->name }}</p>
                    <p><strong>Description:</strong> {{ $lipaKidogo->material->description ?? 'No description available' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Category:</strong> {{ $lipaKidogo->material->category ?? 'General' }}</p>
                    <p><strong>Unit Price:</strong> TZS {{ number_format($lipaKidogo->material->price) }}</p>
                </div>
            </div>
        </div>

        <!-- Installment History -->
        <div class="plan-card">
            <h4 class="section-title">
                <i class="fas fa-list-ul"></i>
                Installment History
            </h4>

            @forelse($lipaKidogo->installments as $installment)
                <div class="installment-card installment-{{ $installment->status }}">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div class="text-center">
                                <div class="h5 mb-0">#{{ $installment->installment_number }}</div>
                                <small class="text-muted">Installment</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h6 mb-0">TZS {{ number_format($installment->amount) }}</div>
                                <small class="text-muted">Amount</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="mb-0">{{ $installment->due_date->format('M j, Y') }}</div>
                                <small class="text-muted">Due Date</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <span class="status-badge status-{{ $installment->status }}">
                                    {{ ucfirst($installment->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            @if($installment->status === 'paid')
                                <div class="text-center">
                                    <div class="text-success">
                                        <i class="fas fa-check-circle fa-lg"></i>
                                    </div>
                                    <small class="text-muted">{{ $installment->paid_at?->format('M j, Y') }}</small>
                                </div>
                            @elseif($installment->status === 'pending' && $installment->due_date->isPast())
                                <div class="text-center">
                                    <div class="text-danger">
                                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                                    </div>
                                    <small class="text-muted">Overdue</small>
                                    <form action="{{ route('lipa_kidogo.pay_installment', [$lipaKidogo->id, $installment->id]) }}" method="POST" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="payment_type" value="installment">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-credit-card me-1"></i>Pay Now
                                        </button>
                                    </form>
                                </div>
                            @elseif($installment->status === 'pending')
                                <div class="text-center">
                                    <div class="text-warning">
                                        <i class="fas fa-clock fa-lg"></i>
                                    </div>
                                    <small class="text-muted">Pending</small>
                                    <form action="{{ route('lipa_kidogo.pay_installment', [$lipaKidogo->id, $installment->id]) }}" method="POST" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="payment_type" value="installment">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-credit-card me-1"></i>Pay Now
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="text-center">
                                    <div class="text-secondary">
                                        <i class="fas fa-times-circle fa-lg"></i>
                                    </div>
                                    <small class="text-muted">{{ ucfirst($installment->status) }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No installments found for this plan.</p>
                </div>
            @endforelse
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('dashboard') }}" class="btn btn-gold">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
