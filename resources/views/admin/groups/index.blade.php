@extends('layouts.app')

@section('no_sidebar', true)

@section('title', 'Manage Groups - Oweru')

@section('content')
<style>
    /* Oweru Brand Colors */
    :root {
        --oweru-dark: #09172A;
        --oweru-gold: #C89128;
        --oweru-light: #F8F8F9;
        --oweru-blue: #2D3A58;
        --oweru-secondary: #E5B972;
        --oweru-gray: #889898;
        --oweru-success: #27ae60;
        --oweru-danger: #e74c3c;
        --oweru-warning: #f39c12;
        --oweru-gradient: linear-gradient(135deg, #09172A 0%, #2D3A58 100%);
        --oweru-gradient-gold: linear-gradient(135deg, #C89128 0%, #E5B972 100%);
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

    .manage-groups-page {
        background: var(--oweru-light);
        min-height: 100vh;
        padding: 2rem 0;
        width: 100% !important;
        margin: 0 !important;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 8px 25px rgba(9, 23, 42, 0.1);
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
        background: var(--oweru-gradient-gold);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(9, 23, 42, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--oweru-dark);
        background: var(--oweru-gradient-gold);
    }

    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(9, 23, 42, 0.1);
        border: none;
        overflow: hidden;
        width: 100%;
        margin-bottom: 2rem;
    }

    .card-header-oweru {
        background: var(--oweru-gradient);
        border: none;
        padding: 1.5rem 2rem;
    }

    .btn-oweru-success {
        background: var(--oweru-success);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-oweru-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        color: white;
    }

    .btn-oweru-danger {
        background: var(--oweru-danger);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-oweru-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        color: white;
    }

    .btn-oweru-outline {
        border: 2px solid var(--oweru-gold);
        border-radius: 8px;
        color: var(--oweru-gold);
        font-weight: 600;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-oweru-outline:hover {
        background: var(--oweru-gold);
        color: var(--oweru-dark);
        transform: translateY(-2px);
    }

    .table-oweru {
        border-radius: 12px;
        overflow: hidden;
        width: 100%;
    }

    .table-oweru thead {
        background: var(--oweru-gradient);
        color: white;
    }

    .table-oweru th {
        border: none;
        padding: 1rem;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .table-oweru td {
        border-color: #f1f3f4;
        padding: 1rem;
        vertical-align: middle;
    }

    .table-oweru tbody tr {
        transition: all 0.3s ease;
    }

    .table-oweru tbody tr:hover {
        background-color: rgba(200, 145, 40, 0.05);
        transform: translateX(5px);
    }

    .badge-oweru {
        background: var(--oweru-gradient-gold);
        color: var(--oweru-dark);
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-pending {
        background: var(--oweru-warning);
        color: white;
    }

    .badge-active {
        background: var(--oweru-success);
        color: white;
    }

    .badge-inactive {
        background: var(--oweru-gray);
        color: white;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--oweru-gray);
        margin-bottom: 1.5rem;
    }

    .group-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--oweru-gradient-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--oweru-dark);
        font-weight: 600;
        font-size: 1rem;
    }

    .page-header {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 25px rgba(9, 23, 42, 0.1);
        border-left: 5px solid var(--oweru-gold);
        width: 100%;
    }

    .member-progress {
        height: 6px;
        border-radius: 3px;
        background: #e9ecef;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .member-progress-bar {
        height: 100%;
        background: var(--oweru-gradient-gold);
        border-radius: 3px;
    }

    .futura-font {
        font-family: 'Futura PT', Arial, sans-serif;
    }

    .poppins-font {
        font-family: 'Poppins', Arial, sans-serif;
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

    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

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
        
        .table-oweru {
            font-size: 0.9rem;
        }
        
        .table-oweru th,
        .table-oweru td {
            padding: 0.75rem 0.5rem;
        }
        
        .btn-group .btn {
            margin-bottom: 0.25rem;
        }
    }
</style>

<div class="manage-groups-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h2 mb-2 futura-font text-oweru-dark">
                        <i class="fas fa-users-cog me-2 text-oweru-gold"></i>
                        Manage Groups
                    </h1>
                    <p class="mb-0 poppins-font text-oweru-gray">Approve and manage user groups in the Oweru system</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="text-oweru-gray poppins-font small">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ $pendingGroups->count() }} pending, {{ $activeGroups->count() }} active
                    </div>
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
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-oweru-dark mb-1">{{ $pendingGroups->count() + $activeGroups->count() }}</h3>
                            <p class="text-oweru-gray mb-0 poppins-font">Total Groups</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-oweru-dark mb-1">{{ $pendingGroups->count() }}</h3>
                            <p class="text-oweru-gray mb-0 poppins-font">Pending Approval</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-oweru-dark mb-1">{{ $activeGroups->count() }}</h3>
                            <p class="text-oweru-gray mb-0 poppins-font">Active Groups</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon">
                                <i class="fas fa-user-friends"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-oweru-dark mb-1">{{ $pendingGroups->sum('activeMembers->count()') + $activeGroups->sum('activeMembers->count()') }}</h3>
                            <p class="text-oweru-gray mb-0 poppins-font">Total Members</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Groups Card -->
        <div class="main-card">
            <div class="card-header-oweru text-white">
                <h6 class="m-0 font-weight-bold futura-font">
                    <i class="fas fa-clock me-2"></i>
                    Pending Group Approvals ({{ $pendingGroups->count() }})
                </h6>
            </div>
            <div class="card-body p-0">
                @if($pendingGroups->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-oweru table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="futura-font">Group Details</th>
                                    <th class="futura-font">Leader</th>
                                    <th class="futura-font">Members</th>
                                    <th class="futura-font">Challenge</th>
                                    <th class="futura-font">Created</th>
                                    <th class="futura-font">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingGroups as $group)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="group-avatar">
                                                        {{ strtoupper(substr($group->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 futura-font text-oweru-dark">{{ $group->name }}</h6>
                                                    <p class="mb-0 text-oweru-gray small poppins-font">
                                                        {{ Str::limit($group->description, 60) }}
                                                    </p>
                                                    <span class="badge-pending px-3 py-1 mt-1">
                                                        <i class="fas fa-clock me-1"></i>Pending Approval
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="poppins-font">
                                                <div class="text-oweru-dark small fw-bold">
                                                    {{ $group->leader->username ?? 'N/A' }}
                                                </div>
                                                <div class="text-oweru-gray small">
                                                    {{ $group->leader->email ?? 'No email' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <span class="futura-font text-oweru-dark d-block">{{ $group->getMemberCount() }}</span>
                                                <small class="text-oweru-gray poppins-font">of {{ $group->max_members }}</small>
                                                <div class="member-progress">
                                                    <div class="member-progress-bar" style="width: {{ ($group->getMemberCount() / $group->max_members) * 100 }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($group->challenge)
                                                <span class="badge-oweru">
                                                    {{ $group->challenge->name }}
                                                </span>
                                            @else
                                                <span class="text-oweru-gray poppins-font">No Challenge</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <small class="text-oweru-dark futura-font d-block">
                                                    {{ $group->created_at->format('M d, Y') }}
                                                </small>
                                                <small class="text-oweru-gray poppins-font">
                                                    {{ $group->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('admin.groups.approve', $group) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn-oweru-success btn-sm" 
                                                            data-bs-toggle="tooltip" 
                                                            title="Approve Group">
                                                        <i class="fas fa-check me-1"></i>Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.groups.reject', $group) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn-oweru-danger btn-sm" 
                                                            data-bs-toggle="tooltip" 
                                                            title="Reject Group"
                                                            onclick="return confirm('Are you sure you want to reject this group? This action cannot be undone.')">
                                                        <i class="fas fa-times me-1"></i>Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h4 class="futura-font text-oweru-dark mb-3">No Pending Groups</h4>
                        <p class="poppins-font text-oweru-gray mb-4">All groups have been processed and approved.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Active Groups Card -->
        <div class="main-card">
            <div class="card-header-oweru text-white">
                <h6 class="m-0 font-weight-bold futura-font">
                    <i class="fas fa-users me-2"></i>
                    Active Groups ({{ $activeGroups->count() }})
                </h6>
            </div>
            <div class="card-body p-0">
                @if($activeGroups->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-oweru table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="futura-font">Group Details</th>
                                    <th class="futura-font">Leader</th>
                                    <th class="futura-font">Members</th>
                                    <th class="futura-font">Challenge</th>
                                    <th class="futura-font">Status</th>
                                    <th class="futura-font">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeGroups as $group)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="group-avatar">
                                                        {{ strtoupper(substr($group->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 futura-font text-oweru-dark">{{ $group->name }}</h6>
                                                    <p class="mb-0 text-oweru-gray small poppins-font">
                                                        {{ Str::limit($group->description, 60) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="poppins-font">
                                                <div class="text-oweru-dark small fw-bold">
                                                    {{ $group->leader->username ?? 'N/A' }}
                                                </div>
                                                <div class="text-oweru-gray small">
                                                    {{ $group->leader->email ?? 'No email' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <span class="futura-font text-oweru-dark d-block">{{ $group->getMemberCount() }}</span>
                                                <small class="text-oweru-gray poppins-font">of {{ $group->max_members }}</small>
                                                <div class="member-progress">
                                                    <div class="member-progress-bar" style="width: {{ ($group->getMemberCount() / $group->max_members) * 100 }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($group->challenge)
                                                <span class="badge-oweru">
                                                    {{ $group->challenge->name }}
                                                </span>
                                            @else
                                                <span class="text-oweru-gray poppins-font">No Challenge</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge-active px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i>Active
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('groups.show', $group) }}" 
                                                   class="btn-oweru-outline btn-sm"
                                                   data-bs-toggle="tooltip" 
                                                   title="View Group Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('admin.groups.deactivate', $group) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn-oweru-danger btn-sm"
                                                            data-bs-toggle="tooltip" 
                                                            title="Deactivate Group"
                                                            onclick="return confirm('Are you sure you want to deactivate this group? Members will no longer be able to participate.')">
                                                        <i class="fas fa-pause"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="futura-font text-oweru-dark mb-3">No Active Groups</h4>
                        <p class="poppins-font text-oweru-gray mb-4">No groups are currently active in the system.</p>
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
    const tableRows = document.querySelectorAll('.table-oweru tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
});
</script>
@endpush
@endsection