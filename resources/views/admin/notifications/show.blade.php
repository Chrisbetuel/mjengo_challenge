@extends('layouts.app')

@section('no_sidebar', true)

@section('title', 'Notification Details - Mjengo Challenge')

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

    .notification-details-page {
        background: var(--mjengo-light);
        min-height: 100vh;
        padding: 2rem 0;
        width: 100% !important;
        margin: 0 !important;
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

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--mjengo-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 2rem;
    }

    .notification-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(44, 62, 80, 0.1);
        border: none;
        margin-bottom: 2rem;
    }

    .notification-title {
        color: var(--mjengo-dark);
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .notification-message {
        background: var(--mjengo-light);
        border-radius: 10px;
        padding: 1.5rem;
        border-left: 4px solid var(--mjengo-secondary);
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f3f4;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: var(--mjengo-dark);
    }

    .info-value {
        color: var(--mjengo-gray);
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

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        .page-header {
            padding: 1.5rem;
        }

        .notification-card {
            padding: 1.5rem;
        }

        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>

<div class="notification-details-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h2 mb-2 text-mjengo-dark">
                        <i class="fas fa-bell me-2 text-mjengo-secondary"></i>
                        Notification Details
                    </h1>
                    <p class="mb-0 text-mjengo-gray">View detailed information about this notification</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="btn-group">
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-mjengo-outline">
                            <i class="fas fa-arrow-left me-2"></i>Back to Notifications
                        </a>
                        <form action="{{ route('admin.notifications.destroy', $notification) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this notification?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-mjengo-danger">
                                <i class="fas fa-trash me-2"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Details -->
        <div class="row">
            <!-- User Information -->
            <div class="col-lg-4 mb-4">
                <div class="notification-card">
                    <h5 class="mb-4">
                        <i class="fas fa-user me-2 text-mjengo-secondary"></i>
                        Recipient Information
                    </h5>

                    <div class="text-center mb-4">
                        <div class="user-avatar mx-auto mb-3">
                            {{ strtoupper(substr($notification->user->username ?? 'U', 0, 1)) }}
                        </div>
                        <h6 class="text-mjengo-dark mb-1">{{ $notification->user->username ?? 'Unknown User' }}</h6>
                        <small class="text-mjengo-gray">{{ $notification->user->email ?? 'No email' }}</small>
                    </div>

                    <div class="info-row">
                        <span class="info-label">User ID:</span>
                        <span class="info-value">{{ $notification->user->id ?? 'N/A' }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $notification->user->phone_number ?? 'Not provided' }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Role:</span>
                        <span class="info-value">{{ $notification->user->role ?? 'user' }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Joined:</span>
                        <span class="info-value">{{ $notification->user->created_at ? $notification->user->created_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Notification Content -->
            <div class="col-lg-8">
                <div class="notification-card">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h5 class="mb-2">
                                <i class="fas fa-envelope me-2 text-mjengo-secondary"></i>
                                Notification Content
                            </h5>
                            <div class="mb-3">
                                @if($notification->type === 'payment')
                                    <span class="badge badge-payment">
                                        <i class="fas fa-money-bill-wave me-1"></i>Payment Notification
                                    </span>
                                @elseif($notification->type === 'challenge')
                                    <span class="badge badge-challenge">
                                        <i class="fas fa-trophy me-1"></i>Challenge Notification
                                    </span>
                                @elseif($notification->type === 'penalty')
                                    <span class="badge badge-penalty">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Penalty Notification
                                    </span>
                                @elseif($notification->type === 'system')
                                    <span class="badge badge-system">
                                        <i class="fas fa-cog me-1"></i>System Notification
                                    </span>
                                @elseif($notification->type === 'group')
                                    <span class="badge badge-group">
                                        <i class="fas fa-users me-1"></i>Group Notification
                                    </span>
                                @else
                                    <span class="badge badge-mjengo">
                                        <i class="fas fa-tag me-1"></i>{{ ucfirst($notification->type) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-end">
                            <small class="text-mjengo-gray">ID: {{ $notification->id }}</small>
                        </div>
                    </div>

                    <div class="notification-title">
                        {{ $notification->title }}
                    </div>

                    <div class="notification-message">
                        {{ $notification->message }}
                    </div>
                </div>

                <!-- Notification Metadata -->
                <div class="notification-card">
                    <h5 class="mb-4">
                        <i class="fas fa-info-circle me-2 text-mjengo-secondary"></i>
                        Notification Details
                    </h5>

                    <div class="info-row">
                        <span class="info-label">Notification ID:</span>
                        <span class="info-value">{{ $notification->id }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Type:</span>
                        <span class="info-value">{{ ucfirst($notification->type) }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Created At:</span>
                        <span class="info-value">{{ $notification->created_at->format('M d, Y \a\t H:i:s') }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Last Updated:</span>
                        <span class="info-value">{{ $notification->updated_at->format('M d, Y \a\t H:i:s') }}</span>
                    </div>

                    @if($notification->data)
                    <div class="info-row">
                        <span class="info-label">Additional Data:</span>
                        <span class="info-value">
                            <pre class="bg-light p-2 rounded small">{{ json_encode($notification->data, JSON_PRETTY_PRINT) }}</pre>
                        </span>
                    </div>
                    @endif

                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle me-1"></i>Sent
                            </span>
                        </span>
                    </div>
                </div>
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
});
</script>
@endpush
@endsection
