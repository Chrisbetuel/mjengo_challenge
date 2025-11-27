@extends('layouts.app')

@section('no_sidebar', true)

@section('title', 'Manage Notifications - Mjengo Challenge')

@section('content')
<style>
    /* Mjengo Brand Colors */
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

    /* Force full-width layout - hide sidebar completely */
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
        max-width: 100% !important;
    }

    .container-fluid {
        padding-left: 20px !important;
        padding-right: 20px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    .manage-notifications-page {
        background: var(--mjengo-light);
        min-height: 100vh;
        padding: 2rem 0;
        width: 100% !important;
        margin: 0 !important;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 8px 25px rgba(44, 62, 80, 0.1);
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
        box-shadow: 0 15px 35px rgba(44, 62, 80, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        background: var(--mjengo-gradient);
    }

    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(44, 62, 80, 0.1);
        border: none;
        overflow: hidden;
        width: 100%;
    }

    .card-header-mjengo {
        background: var(--mjengo-gradient);
        border: none;
        padding: 1.5rem 2rem;
    }

    .btn-mjengo-primary {
        background: var(--mjengo-gradient);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-mjengo-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
        color: white;
    }

    .btn-mjengo-outline {
        border: 2px solid var(--mjengo-secondary);
        border-radius: 10px;
        color: var(--mjengo-secondary);
        font-weight: 600;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-mjengo-outline:hover {
        background: var(--mjengo-secondary);
        color: white;
        transform: translateY(-2px);
    }

    .btn-mjengo-danger {
        border: 2px solid var(--mjengo-accent);
        border-radius: 10px;
        color: var(--mjengo-accent);
        font-weight: 600;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-mjengo-danger:hover {
        background: var(--mjengo-accent);
        color: white;
        transform: translateY(-2px);
    }

    .table-mjengo {
        border-radius: 12px;
        overflow: hidden;
        width: 100%;
    }

    .table-mjengo thead {
        background: var(--mjengo-gradient);
        color: white;
    }

    .table-mjengo th {
        border: none;
        padding: 1rem;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .table-mjengo td {
        border-color: #f1f3f4;
        padding: 1rem;
        vertical-align: middle;
    }

    .table-mjengo tbody tr {
        transition: all 0.3s ease;
    }

    .table-mjengo tbody tr:hover {
        background-color: rgba(52, 152, 219, 0.05);
        transform: translateX(5px);
    }

    .badge-mjengo {
        background: var(--mjengo-gradient);
        color: white;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-payment { background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); }
    .badge-challenge { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }
    .badge-penalty { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }
    .badge-system { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); }
    .badge-group { background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%); }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--mjengo-gray);
        margin-bottom: 1.5rem;
    }

    .notification-content {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .page-header {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 25px rgba(44, 62, 80, 0.1);
        border-left: 5px solid var(--mjengo-secondary);
        width: 100%;
    }

    .search-box {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .search-box:focus {
        border-color: var(--mjengo-secondary);
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }

    .filter-dropdown {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem;
    }

    /* Ensure full width for all containers */
    .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
    }

    .col-12 {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        .page-header {
            padding: 1.5rem;
        }

        .stat-card {
            margin-bottom: 1rem;
        }

        .table-mjengo {
            font-size: 0.9rem;
        }

        .table-mjengo th,
        .table-mjengo td {
            padding: 0.75rem 0.5rem;
        }

        .btn-group .btn {
            margin-bottom: 0.25rem;
        }
    }
</style>

<div class="manage-notifications-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h2 mb-2 text-mjengo-dark">
                        <i class="fas fa-bell me-2 text-mjengo-secondary"></i>
                        Manage Notifications
                    </h1>
                    <p class="mb-0 text-mjengo-gray">Send and manage user notifications</p>
                </div>
                <div class="col-md-6">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <input type="text" class="form-control search-box" placeholder="Search notifications...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-control filter-dropdown">
                                <option value="">All Types</option>
                                <option value="payment">Payment</option>
                                <option value="challenge">Challenge</option>
                                <option value="penalty">Penalty</option>
                                <option value="system">System</option>
                                <option value="group">Group</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.notifications.create') }}" class="btn btn-mjengo-primary">
                        <i class="fas fa-plus me-2"></i>Create Notification
                    </a>
                    <form action="{{ route('admin.notifications.send-to-all') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="title" value="System Announcement">
                        <input type="hidden" name="message" value="This is a system-wide announcement.">
                        <input type="hidden" name="type" value="system">
                        <button type="submit" class="btn btn-mjengo-outline">
                            <i class="fas fa-bullhorn me-2"></i>Send to All Users
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-mjengo-dark mb-1">{{ $notifications->total() }}</h3>
                            <p class="text-mjengo-gray mb-0">Total Notifications</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-mjengo-dark mb-1">{{ $notifications->where('type', 'payment')->count() }}</h3>
                            <p class="text-mjengo-gray mb-0">Payment Notifications</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
                                <i class="fas fa-trophy"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-mjengo-dark mb-1">{{ $notifications->where('type', 'challenge')->count() }}</h3>
                            <p class="text-mjengo-gray mb-0">Challenge Notifications</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-mjengo-dark mb-1">{{ $notifications->where('type', 'penalty')->count() }}</h3>
                            <p class="text-mjengo-gray mb-0">Penalty Notifications</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Table -->
        <div class="main-card">
            <div class="card-header-mjengo text-white">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-list me-2"></i>
                            All Notifications ({{ $notifications->total() }})
                        </h6>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button class="btn btn-light btn-sm me-2">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                        <button class="btn btn-light btn-sm">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if($notifications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-mjengo table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Type</th>
                                    <th>Sent At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                    <tr>
                                        <td>
                                            <span class="badge badge-mjengo">#{{ $notification->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="bg-mjengo-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        {{ strtoupper(substr($notification->user->username ?? 'U', 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 text-mjengo-dark">
                                                        {{ $notification->user->username ?? 'Unknown User' }}
                                                    </h6>
                                                    <small class="text-mjengo-gray">
                                                        {{ $notification->user->email ?? 'No email' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="text-mjengo-dark">{{ $notification->title }}</strong>
                                        </td>
                                        <td>
                                            <div class="notification-content" title="{{ $notification->message }}">
                                                {{ $notification->message }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($notification->type === 'payment')
                                                <span class="badge badge-payment">
                                                    <i class="fas fa-money-bill-wave me-1"></i>Payment
                                                </span>
                                            @elseif($notification->type === 'challenge')
                                                <span class="badge badge-challenge">
                                                    <i class="fas fa-trophy me-1"></i>Challenge
                                                </span>
                                            @elseif($notification->type === 'penalty')
                                                <span class="badge badge-penalty">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Penalty
                                                </span>
                                            @elseif($notification->type === 'system')
                                                <span class="badge badge-system">
                                                    <i class="fas fa-cog me-1"></i>System
                                                </span>
                                            @elseif($notification->type === 'group')
                                                <span class="badge badge-group">
                                                    <i class="fas fa-users me-1"></i>Group
                                                </span>
                                            @else
                                                <span class="badge badge-mjengo">
                                                    <i class="fas fa-tag me-1"></i>{{ ucfirst($notification->type) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <small class="text-mjengo-dark d-block">
                                                    {{ $notification->created_at->format('M d, Y') }}
                                                </small>
                                                <small class="text-mjengo-gray">
                                                    {{ $notification->created_at->format('H:i') }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.notifications.show', $notification) }}"
                                                   class="btn btn-mjengo-outline btn-sm"
                                                   data-bs-toggle="tooltip"
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('admin.notifications.destroy', $notification) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this notification?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-mjengo-danger btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            title="Delete Notification">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer bg-mjengo-light border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-mjengo-gray">
                                Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of {{ $notifications->total() }} notifications
                            </div>
                            <nav>
                                {{ $notifications->links() }}
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-bell-slash"></i>
                        </div>
                        <h4 class="text-mjengo-dark mb-3">No Notifications Found</h4>
                        <p class="text-mjengo-gray mb-4">No notifications have been sent yet. Create your first notification to get started.</p>
                        <a href="{{ route('admin.notifications.create') }}" class="btn btn-mjengo-primary">
                            <i class="fas fa-plus me-2"></i>Create First Notification
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Force full width layout
    document.body.style.overflowX = 'hidden';
    const mainContainer = document.querySelector('.container-fluid');
    if (mainContainer) {
        mainContainer.style.maxWidth = '100%';
        mainContainer.style.marginLeft = '0';
        mainContainer.style.marginRight = '0';
        mainContainer.style.paddingLeft = '20px';
        mainContainer.style.paddingRight = '20px';
    }

    // Remove any sidebar elements that might still be in DOM
    const sidebarElements = document.querySelectorAll('.sidebar, .col-md-3, .col-lg-2, [class*="sidebar"]');
    sidebarElements.forEach(element => {
        element.style.display = 'none';
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

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

    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('.table-mjengo tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });

        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // Search functionality
    const searchBox = document.querySelector('.search-box');
    searchBox.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.table-mjengo tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Filter functionality
    const filterDropdown = document.querySelector('.filter-dropdown');
    filterDropdown.addEventListener('change', function() {
        const filterValue = this.value;
        const rows = document.querySelectorAll('.table-mjengo tbody tr');

        rows.forEach(row => {
            if (!filterValue) {
                row.style.display = '';
            } else {
                const typeBadge = row.querySelector('.badge');
                if (typeBadge && typeBadge.textContent.toLowerCase().includes(filterValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
});
</script>
@endpush
@endsection
