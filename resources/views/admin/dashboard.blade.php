@extends('layouts.app')

@section('title', 'Admin Dashboard - Mjengo Challenge')

@section('content')
<style>
    /* Mjengo Admin Dashboard Styles */
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
        --mjengo-gradient-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --mjengo-gradient-warning: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .admin-dashboard {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--mjengo-gradient);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .stat-card.primary::before { background: var(--mjengo-gradient); }
    .stat-card.success::before { background: var(--mjengo-gradient-success); }
    .stat-card.warning::before { background: var(--mjengo-gradient-warning); }
    .stat-card.danger::before { background: var(--mjengo-gradient-secondary); }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-icon.primary { background: var(--mjengo-gradient); }
    .stat-icon.success { background: var(--mjengo-gradient-success); }
    .stat-icon.warning { background: var(--mjengo-gradient-warning); }
    .stat-icon.danger { background: var(--mjengo-gradient-secondary); }

    .quick-action-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .quick-action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--mjengo-gradient);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .quick-action-card:hover::before {
        opacity: 0.05;
    }

    .quick-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .action-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        margin: 0 auto 1rem;
    }

    .action-icon.blue { background: var(--mjengo-gradient); }
    .action-icon.green { background: var(--mjengo-gradient-success); }
    .action-icon.purple { background: var(--mjengo-gradient-secondary); }
    .action-icon.orange { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }

    .recent-activity {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .activity-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f3f4;
        transition: background-color 0.3s ease;
    }

    .activity-item:hover {
        background-color: #f8f9fa;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-badge {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 10px;
    }

    .badge-success { background-color: var(--mjengo-success); }
    .badge-primary { background-color: var(--mjengo-primary); }
    .badge-warning { background-color: var(--mjengo-warning); }

    .dashboard-header {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-left: 5px solid var(--mjengo-secondary);
    }

    .welcome-text {
        color: var(--mjengo-dark);
        font-weight: 600;
    }

    .date-display {
        color: var(--mjengo-gray);
        font-size: 0.9rem;
    }

    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    /* Custom scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: var(--mjengo-secondary);
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #2980b9;
    }

    /* Animation for numbers */
    .counter {
        font-variant-numeric: tabular-nums;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stat-card, .quick-action-card {
            margin-bottom: 1rem;
        }
        
        .dashboard-header {
            padding: 1.5rem;
        }
    }
</style>

<div class="admin-dashboard">
    <div class="container-fluid px-4 py-4">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h2 mb-2 welcome-text">
                        <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                        Welcome back, Administrator!
                    </h1>
                    <p class="date-display mb-0">
                        <i class="fas fa-calendar me-1"></i>
                        {{ now()->format('l, F j, Y') }}
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="btn-group">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card primary">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon primary">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-primary mb-1">{{ $stats['total_users'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Users</p>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                12% growth
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card success">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon success">
                                <i class="fas fa-trophy"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-success mb-1">{{ $stats['total_challenges'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Active Challenges</p>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                8% growth
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card warning">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon warning">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-warning mb-1">{{ $stats['total_direct_purchases'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Direct Purchases</p>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                15% growth
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card danger">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon danger">
                                <i class="fas fa-credit-card"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-danger mb-1">{{ $stats['total_lipa_kidogo'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Lipa Kidogo Plans</p>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                22% growth
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Stats Row -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card" style="border-left: 4px solid #9b59b6;">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter mb-1" style="color: #9b59b6;">TZS {{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                            <p class="text-muted mb-0">Total Revenue</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card" style="border-left: 4px solid #1abc9c;">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter mb-1" style="color: #1abc9c;">{{ $stats['active_participants'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Active Participants</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card" style="border-left: 4px solid #e67e22;">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter mb-1" style="color: #e67e22;">{{ $stats['pending_approvals'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Pending Approvals</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card" style="border-left: 4px solid #7f8c8d;">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #7f8c8d 0%, #95a5a6 100%);">
                                <i class="fas fa-bricks"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter mb-1" style="color: #7f8c8d;">{{ $stats['total_materials'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Materials</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions and Recent Activity -->
        <div class="row">
            <!-- Quick Actions -->
            <div class="col-xl-8 mb-4">
                <div class="chart-container">
                    <h4 class="mb-4">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Quick Actions
                    </h4>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.challenges.create') }}" class="quick-action-card text-decoration-none">
                                <div class="action-icon blue">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Create Challenge</h6>
                                <p class="text-muted small mb-0">Start new savings challenge</p>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.challenges') }}" class="quick-action-card text-decoration-none">
                                <div class="action-icon green">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Manage Challenges</h6>
                                <p class="text-muted small mb-0">View all challenges</p>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.users') }}" class="quick-action-card text-decoration-none">
                                <div class="action-icon purple">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Manage Users</h6>
                                <p class="text-muted small mb-0">User administration</p>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.materials') }}" class="quick-action-card text-decoration-none">
                                <div class="action-icon orange">
                                    <i class="fas fa-bricks"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Manage Materials</h6>
                                <p class="text-muted small mb-0">Add/view materials</p>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.groups') }}" class="quick-action-card text-decoration-none">
                                <div class="action-icon" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Manage Groups</h6>
                                <p class="text-muted small mb-0">Group administration</p>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.payments') }}" class="quick-action-card text-decoration-none">
                                <div class="action-icon" style="background: linear-gradient(135deg, #a8e6cf 0%, #3bb78f 100%);">
                                    <i class="fas fa-money-check"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">View Payments</h6>
                                <p class="text-muted small mb-0">Payment transactions</p>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.penalties') }}" class="quick-action-card text-decoration-none">
                                <div class="action-icon" style="background: linear-gradient(135deg, #ffd93d 0%, #ff9c33 100%);">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Manage Penalties</h6>
                                <p class="text-muted small mb-0">Penalty administration</p>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.reports') }}" class="quick-action-card text-decoration-none">
                                <div class="action-icon" style="background: linear-gradient(135deg, #6a89cc 0%, #4a69bd 100%);">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">View Reports</h6>
                                <p class="text-muted small mb-0">Analytics & insights</p>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.notifications.index') }}" class="quick-action-card text-decoration-none">
                                <div class="action-icon" style="background: linear-gradient(135deg, #ff7675 0%, #d63031 100%);">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Manage Notifications</h6>
                                <p class="text-muted small mb-0">Send user notifications</p>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Overview Chart Placeholder -->
                <div class="chart-container">
                    <h4 class="mb-4">
                        <i class="fas fa-chart-pie me-2 text-info"></i>
                        System Overview
                    </h4>
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="p-3 border rounded">
                                <div class="text-primary mb-2">
                                    <i class="fas fa-user-check fa-2x"></i>
                                </div>
                                <h5 class="mb-1">{{ $stats['active_users'] ?? 0 }}</h5>
                                <small class="text-muted">Active Users</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 border rounded">
                                <div class="text-success mb-2">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <h5 class="mb-1">{{ $stats['completed_challenges'] ?? 0 }}</h5>
                                <small class="text-muted">Completed Challenges</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 border rounded">
                                <div class="text-warning mb-2">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                                <h5 class="mb-1">{{ $stats['pending_payments'] ?? 0 }}</h5>
                                <small class="text-muted">Pending Payments</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 border rounded">
                                <div class="text-danger mb-2">
                                    <i class="fas fa-exclamation-circle fa-2x"></i>
                                </div>
                                <h5 class="mb-1">{{ $stats['overdue_installments'] ?? 0 }}</h5>
                                <small class="text-muted">Overdue Installments</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-xl-4 mb-4">
                <div class="recent-activity">
                    <div class="p-4 border-bottom">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2 text-primary"></i>
                            Recent Activity
                        </h5>
                    </div>
                    <div class="custom-scrollbar" style="max-height: 500px; overflow-y: auto;">
                        @if(isset($stats['recent_activity']) && $stats['recent_activity']->count() > 0)
                            @foreach($stats['recent_activity'] as $activity)
                            <div class="activity-item">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="activity-badge 
                                            {{ $activity->action == 'user_registered' ? 'badge-success' : 
                                               ($activity->action == 'payment_made' ? 'badge-primary' : 'badge-warning') }}">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6 class="mb-1 text-dark">{{ $activity->user->username ?? 'System' }}</h6>
                                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1 text-muted small">
                                            @if($activity->action == 'user_registered')
                                                <i class="fas fa-user-plus me-1 text-success"></i>
                                                New user registration
                                            @elseif($activity->action == 'payment_made')
                                                <i class="fas fa-money-bill-wave me-1 text-primary"></i>
                                                Payment processed
                                            @elseif($activity->action == 'challenge_created')
                                                <i class="fas fa-trophy me-1 text-warning"></i>
                                                Challenge created
                                            @else
                                                <i class="fas fa-cog me-1 text-info"></i>
                                                {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                                            @endif
                                        </p>
                                        @if($activity->description)
                                        <small class="text-muted">{{ Str::limit($activity->description, 60) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No recent activity found</p>
                            </div>
                        @endif
                    </div>
                    <div class="p-3 border-top text-center">
                        <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-chart-bar me-1"></i>View Full Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate counters
    const counters = document.querySelectorAll('.counter');
    const speed = 200;

    counters.forEach(counter => {
        const animate = () => {
            const value = +counter.innerText;
            const data = +counter.getAttribute('data-target');
            const time = data / speed;
            
            if(value < data) {
                counter.innerText = Math.ceil(value + time);
                setTimeout(animate, 1);
            } else {
                counter.innerText = data;
            }
        }
        
        counter.setAttribute('data-target', counter.innerText);
        counter.innerText = '0';
        setTimeout(animate, 500);
    });

    // Add hover effects
    const cards = document.querySelectorAll('.stat-card, .quick-action-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection