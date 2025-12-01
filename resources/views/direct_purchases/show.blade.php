@extends('layouts.app')

@section('title', 'Direct Purchase Details - Mjengo Challenge')

@section('content')
<style>
    /* Direct Purchase Details Styles */
    :root {
        --oweru-dark: #09172A;
        --oweru-gold: #C89128;
        --oweru-light: #F8F8F9;
        --oweru-blue: #2D3A58;
        --oweru-secondary: #E5B972;
        --oweru-gray: #889898;
    }

    .purchase-details-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .purchase-card {
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
        text-transform: uppercase;
    }

    .status-paid { background: #d4edda; color: #155724; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-shipped { background: #cce5ff; color: #004085; }
    .status-delivered { background: #d1ecf1; color: #0c5460; }
    .status-cancelled { background: #f8d7da; color: #721c24; }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #eee;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: var(--oweru-dark);
    }

    .detail-value {
        color: var(--oweru-gray);
    }

    .material-info {
        background: linear-gradient(135deg, var(--oweru-light) 0%, #f1f3f4 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1rem 0;
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

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--oweru-gold);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
        padding-left: 1rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2.5rem;
        top: 0.5rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--oweru-gold);
        border: 3px solid white;
        box-shadow: 0 0 0 2px var(--oweru-gold);
    }

    .timeline-item.active::before {
        background: var(--oweru-secondary);
    }

    .timeline-date {
        font-size: 0.875rem;
        color: var(--oweru-gray);
        margin-bottom: 0.25rem;
    }

    .timeline-title {
        font-weight: 600;
        color: var(--oweru-dark);
        margin-bottom: 0.25rem;
    }

    .timeline-description {
        font-size: 0.875rem;
        color: var(--oweru-gray);
    }

    @media (max-width: 768px) {
        .purchase-details-container {
            padding: 1rem 0;
        }

        .purchase-card {
            padding: 1rem;
        }

        .detail-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>

<div class="purchase-details-container">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="mb-2">Direct Purchase Details</h1>
                        <p class="text-muted mb-0">Order #{{ $directPurchase->id }}</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Purchase Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="purchase-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Purchase Status</h3>
                        <span class="status-badge status-{{ $directPurchase->status }}">
                            {{ ucfirst($directPurchase->status) }}
                        </span>
                    </div>

                    <!-- Status Timeline -->
                    <div class="timeline">
                        <div class="timeline-item {{ in_array($directPurchase->status, ['pending', 'paid', 'shipped', 'delivered']) ? 'active' : '' }}">
                            <div class="timeline-date">{{ $directPurchase->created_at->format('M d, Y H:i') }}</div>
                            <div class="timeline-title">Order Placed</div>
                            <div class="timeline-description">Your order has been received and is being processed.</div>
                        </div>

                        @if(in_array($directPurchase->status, ['paid', 'shipped', 'delivered']))
                        <div class="timeline-item {{ in_array($directPurchase->status, ['paid', 'shipped', 'delivered']) ? 'active' : '' }}">
                            <div class="timeline-date">{{ $directPurchase->updated_at->format('M d, Y H:i') }}</div>
                            <div class="timeline-title">Payment Confirmed</div>
                            <div class="timeline-description">Payment has been successfully processed.</div>
                        </div>
                        @endif

                        @if(in_array($directPurchase->status, ['shipped', 'delivered']))
                        <div class="timeline-item {{ in_array($directPurchase->status, ['shipped', 'delivered']) ? 'active' : '' }}">
                            <div class="timeline-date">{{ $directPurchase->updated_at->format('M d, Y H:i') }}</div>
                            <div class="timeline-title">Order Shipped</div>
                            <div class="timeline-description">Your order has been shipped and is on its way.</div>
                        </div>
                        @endif

                        @if($directPurchase->status === 'delivered')
                        <div class="timeline-item active">
                            <div class="timeline-date">{{ $directPurchase->updated_at->format('M d, Y H:i') }}</div>
                            <div class="timeline-title">Order Delivered</div>
                            <div class="timeline-description">Your order has been successfully delivered.</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Purchase Details -->
            <div class="col-lg-8 mb-4">
                <div class="purchase-card">
                    <h3 class="mb-4">Purchase Information</h3>

                    <div class="detail-row">
                        <span class="detail-label">Purchase Date:</span>
                        <span class="detail-value">{{ $directPurchase->created_at->format('F d, Y \a\t H:i') }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Payment Reference:</span>
                        <span class="detail-value">{{ $directPurchase->payment_reference ?? 'N/A' }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Quantity:</span>
                        <span class="detail-value">{{ $directPurchase->quantity }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Unit Price:</span>
                        <span class="detail-value">TZS {{ number_format($directPurchase->unit_price, 2) }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Total Amount:</span>
                        <span class="detail-value fw-bold text-primary">TZS {{ number_format($directPurchase->total_amount, 2) }}</span>
                    </div>

                    <!-- Material Information -->
                    <div class="material-info">
                        <h5 class="mb-3">Material Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Material:</span>
                                    <span class="detail-value">{{ $directPurchase->material->name }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Category:</span>
                                    <span class="detail-value">{{ $directPurchase->material->category ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Description:</span>
                            <span class="detail-value">{{ $directPurchase->material->description }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="col-lg-4 mb-4">
                <div class="purchase-card">
                    <h3 class="mb-4">Delivery Information</h3>

                    <div class="detail-row">
                        <span class="detail-label">Delivery Address:</span>
                        <span class="detail-value">{{ $directPurchase->delivery_address }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Phone Number:</span>
                        <span class="detail-value">{{ $directPurchase->phone_number }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Delivery Status:</span>
                        <span class="detail-value">
                            @if($directPurchase->status === 'delivered')
                                <span class="badge bg-success">Delivered</span>
                            @elseif($directPurchase->status === 'shipped')
                                <span class="badge bg-info">Shipped</span>
                            @elseif($directPurchase->status === 'paid')
                                <span class="badge bg-warning">Processing</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </span>
                    </div>

                    @if($directPurchase->status === 'delivered')
                        <div class="mt-3 p-3 bg-light rounded">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Delivered Successfully!</strong>
                            <p class="mb-0 small text-muted">Your order has been delivered to the specified address.</p>
                        </div>
                    @elseif($directPurchase->status === 'shipped')
                        <div class="mt-3 p-3 bg-info bg-opacity-10 rounded">
                            <i class="fas fa-truck text-info me-2"></i>
                            <strong>On the Way!</strong>
                            <p class="mb-0 small text-muted">Your order is being delivered. Track your package for updates.</p>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="purchase-card">
                    <h3 class="mb-4">Actions</h3>

                    <div class="d-grid gap-2">
                        <a href="tel:{{ $directPurchase->phone_number }}" class="btn btn-outline-primary">
                            <i class="fas fa-phone me-2"></i>Call Support
                        </a>

                        @if($directPurchase->status === 'delivered')
                            <button class="btn btn-oweru-gold" disabled>
                                <i class="fas fa-star me-2"></i>Rate Purchase
                            </button>
                        @endif

                        <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-shopping-cart me-2"></i>Browse More Materials
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
