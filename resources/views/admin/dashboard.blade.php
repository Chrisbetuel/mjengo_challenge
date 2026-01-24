@extends('layouts.app')

@section('title', 'Admin Dashboard - Mjengo Challenge')

@section('content')
<style>
    /* Admin Dashboard Styles */
    :root {
        --oweru-dark: #09172A;
        --oweru-gold: #C89128;
        --oweru-light: #F8F8F9;
        --oweru-blue: #2D3A58;
        --oweru-secondary: #E5B972;
        --oweru-gray: #889898;
    }

    .admin-dashboard {
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
        margin-bottom: 1rem;
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

    .stats-icon.users { background: var(--oweru-gold); }
    .stats-icon.challenges { background: var(--oweru-blue); }
    .stats-icon.payments { background: var(--oweru-secondary); }
    .stats-icon.materials { background: var(--oweru-dark); }
    .stats-icon.purchases { background: #28a745; }
    .stats-icon.lipa { background: #17a2b8; }

    .quick-action-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
    }

    .quick-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        text-decoration: none;
        color: inherit;
    }

    .action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        margin-bottom: 1rem;
    }

    .activity-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-bottom: 1rem;
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #eee;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1rem;
        font-size: 0.875rem;
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

    .section-title {
        color: var(--oweru-dark);
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .admin-dashboard {
            padding: 1rem 0;
        }

        .stats-card {
            margin-bottom: 1rem;
        }
    }
</style>

<div class="admin-dashboard">
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="mb-2">Admin Dashboard</h1>
                    <p class="mb-0 opacity-75">
                        Welcome back, <strong>{{ auth()->user()->username }}</strong>
                       <span class="ms-2 text-muted">
                       Customer ID: <strong>{{ auth()->user()->user_id }}</strong>
                       </span>
                    </p>


                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex align-items-center justify-content-lg-end gap-2">
                        <i class="fas fa-calendar text-warning"></i>
                        <span>{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-2">
                <div class="stats-card">
                    <div class="stats-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="mb-2">{{ number_format($stats['total_users']) }}</h3>
                    <p class="mb-0 text-muted">Total Users</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <div class="stats-card">
                    <div class="stats-icon challenges">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3 class="mb-2">{{ number_format($stats['total_challenges']) }}</h3>
                    <p class="mb-0 text-muted">Total Challenges</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <div class="stats-card">
                    <div class="stats-icon payments">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h3 class="mb-2">TZS {{ number_format($stats['total_payments']) }}</h3>
                    <p class="mb-0 text-muted">Total Payments</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <div class="stats-card">
                    <div class="stats-icon materials">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="mb-2">{{ number_format($stats['total_materials']) }}</h3>
                    <p class="mb-0 text-muted">Materials</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <div class="stats-card">
                    <div class="stats-icon purchases">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="mb-2">{{ number_format($stats['total_direct_purchases']) }}</h3>
                    <p class="mb-0 text-muted">Direct Purchases</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <div class="stats-card">
                    <div class="stats-icon lipa">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="mb-2">{{ number_format($stats['total_lipa_kidogo']) }}</h3>
                    <p class="mb-0 text-muted">Lipa Kidogo Plans</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="section-title">
                    <i class="fas fa-bolt"></i>Quick Actions
                </h4>
            </div>
        </div>

        <div class="row mb-4">
            <!-- User Management -->
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('admin.users') }}" class="quick-action-card text-decoration-none">
                    <div class="action-icon" style="background: linear-gradient(135deg, #ff7675 0%, #d63031 100%);">
                        <i class="fas fa-users"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">User Management</h6>
                    <p class="text-muted small mb-0">Manage system users</p>
                </a>
            </div>

            <!-- Challenge Management -->
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('admin.challenges') }}" class="quick-action-card text-decoration-none">
                    <div class="action-icon" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Challenge Management</h6>
                    <p class="text-muted small mb-0">Create and manage challenges</p>
                </a>
            </div>

            <!-- Material Management -->
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('admin.materials') }}" class="quick-action-card text-decoration-none">
                    <div class="action-icon" style="background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Material Management</h6>
                    <p class="text-muted small mb-0">Add and manage materials</p>
                </a>
            </div>

            <!-- Payment Management -->
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('admin.payments') }}" class="quick-action-card text-decoration-none">
                    <div class="action-icon" style="background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Payment Management</h6>
                    <p class="text-muted small mb-0">Monitor all payments</p>
                </a>
            </div>

            <!-- Group Management -->
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('admin.groups') }}" class="quick-action-card text-decoration-none">
                    <div class="action-icon" style="background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Group Management</h6>
                    <p class="text-muted small mb-0">Manage user groups</p>
                </a>
            </div>

            <!-- Penalty Management -->
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('admin.penalties') }}" class="quick-action-card text-decoration-none">
                    <div class="action-icon" style="background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Penalty Management</h6>
                    <p class="text-muted small mb-0">Handle user penalties</p>
                </a>
            </div>

            <!-- Direct Purchases -->
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('admin.direct-purchases') }}" class="quick-action-card text-decoration-none">
                    <div class="action-icon" style="background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Direct Purchases</h6>
                    <p class="text-muted small mb-0">View direct purchases</p>
                </a>
            </div>

            <!-- Lipa Kidogo Plans -->
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('admin.lipa-kidogo-plans') }}" class="quick-action-card text-decoration-none">
                    <div class="action-icon" style="background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Lipa Kidogo Plans</h6>
                    <p class="text-muted small mb-0">Installment plans</p>
                </a>
            </div>

            <!-- Notification Management -->
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('admin.notifications.index') }}" class="quick-action-card text-decoration-none">
                    <div class="action-icon" style="background: linear-gradient(135deg, #ff7675 0%, #d63031 100%);">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Manage Notifications</h6>
                    <p class="text-muted small mb-0">Send user notifications</p>
                </a>
            </div>

            <!-- Reports -->
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('admin.reports') }}" class="quick-action-card text-decoration-none">
                    <div class="action-icon" style="background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Reports & Analytics</h6>
                    <p class="text-muted small mb-0">View system reports</p>
                </a>
            </div>

            <!-- Testimonials -->
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('admin.testimonials') }}" class="quick-action-card text-decoration-none">
                    <div class="action-icon" style="background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);">
                        <i class="fas fa-star"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Testimonials</h6>
                    <p class="text-muted small mb-0">Manage testimonials</p>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-12">
                <div class="activity-card">
                    <h4 class="section-title mb-4">
                        <i class="fas fa-history"></i>Recent Activity
                    </h4>
                    @if($recent_activity->count() > 0)
                        @foreach($recent_activity as $activity)
                            <div class="activity-item">
                                <div class="activity-icon" style="background: var(--oweru-gold);">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $activity->user->username ?? 'Unknown User' }}</div>
                                    <small class="text-muted">{{ $activity->description ?? 'Activity performed' }}</small>
                                </div>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent activity found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
