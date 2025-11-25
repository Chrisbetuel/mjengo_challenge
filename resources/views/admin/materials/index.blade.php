@extends('layouts.app')

@section('no_sidebar', true)

@section('title', 'Manage Materials - Oweru')

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

    .manage-materials-page {
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
    }

    .card-header-oweru {
        background: var(--oweru-gradient);
        border: none;
        padding: 1.5rem 2rem;
    }

    .btn-oweru-primary {
        background: var(--oweru-gradient-gold);
        border: none;
        border-radius: 10px;
        color: var(--oweru-dark);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-oweru-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(200, 145, 40, 0.3);
        color: var(--oweru-dark);
    }

    .btn-oweru-outline {
        border: 2px solid var(--oweru-gold);
        border-radius: 10px;
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

    .btn-oweru-danger {
        border: 2px solid var(--oweru-danger);
        border-radius: 10px;
        color: var(--oweru-danger);
        font-weight: 600;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-oweru-danger:hover {
        background: var(--oweru-danger);
        color: white;
        transform: translateY(-2px);
    }

    .btn-oweru-success {
        border: 2px solid var(--oweru-success);
        border-radius: 10px;
        color: var(--oweru-success);
        font-weight: 600;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-oweru-success:hover {
        background: var(--oweru-success);
        color: white;
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

    .badge-active {
        background: var(--oweru-success);
        color: white;
    }

    .badge-inactive {
        background: var(--oweru-gray);
        color: white;
    }

    .material-image {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid var(--oweru-gold);
    }

    .material-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: var(--oweru-gradient-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--oweru-dark);
        font-size: 1.5rem;
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

    .page-header {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 25px rgba(9, 23, 42, 0.1);
        border-left: 5px solid var(--oweru-gold);
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
        border-color: var(--oweru-gold);
        box-shadow: 0 0 0 0.2rem rgba(200, 145, 40, 0.25);
    }

    .filter-dropdown {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem;
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
        
        .material-image,
        .material-icon {
            width: 50px;
            height: 50px;
        }
    }
</style>

<div class="manage-materials-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h2 mb-2 futura-font text-oweru-dark">
                        <i class="fas fa-bricks me-2 text-oweru-gold"></i>
                        Manage Materials
                    </h1>
                    <p class="mb-0 poppins-font text-oweru-gray">Manage construction materials available for purchase</p>
                </div>
                <div class="col-md-6">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <input type="text" class="form-control search-box" placeholder="Search materials by name...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-control filter-dropdown">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
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
                                <i class="fas fa-bricks"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-oweru-dark mb-1">{{ $materials->total() }}</h3>
                            <p class="text-oweru-gray mb-0 poppins-font">Total Materials</p>
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
                            <h3 class="counter text-oweru-dark mb-1">{{ $materials->where('status', 'active')->count() }}</h3>
                            <p class="text-oweru-gray mb-0 poppins-font">Active Materials</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-oweru-dark mb-1">{{ $materials->sum(function($material) { return $material->directPurchases->count(); }) }}</h3>
                            <p class="text-oweru-gray mb-0 poppins-font">Direct Purchases</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3 class="counter text-oweru-dark mb-1">{{ $materials->sum(function($material) { return $material->lipaKidogoPlans->count(); }) }}</h3>
                            <p class="text-oweru-gray mb-0 poppins-font">Lipa Kidogo Plans</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materials Table -->
        <div class="main-card">
            <div class="card-header-oweru text-white">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="m-0 font-weight-bold futura-font">
                            <i class="fas fa-list me-2"></i>
                            All Materials ({{ $materials->total() }})
                        </h6>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('admin.materials.create') }}" class="btn-oweru-primary">
                            <i class="fas fa-plus me-2"></i>Add New Material
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if($materials->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-oweru table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="futura-font">Material</th>
                                    <th class="futura-font">Swahili Name</th>
                                    <th class="futura-font">Price</th>
                                    <th class="futura-font">Status</th>
                                    <th class="futura-font">Created By</th>
                                    <th class="futura-font">Created</th>
                                    <th class="futura-font">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materials as $material)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    @if($material->image)
                                                        <img src="{{ asset('storage/' . $material->image) }}" 
                                                             alt="{{ $material->name }}" 
                                                             class="material-image">
                                                    @else
                                                        <div class="material-icon">
                                                            <i class="fas fa-bricks"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 futura-font text-oweru-dark">{{ $material->name }}</h6>
                                                    <p class="mb-0 text-oweru-gray small poppins-font">
                                                        {{ Str::limit($material->description, 50) }}
                                                    </p>
                                                    <div class="mt-1">
                                                        <small class="badge-oweru me-1">{{ $material->directPurchases->count() }} direct</small>
                                                        <small class="badge-oweru">{{ $material->lipaKidogoPlans->count() }} lipa kidogo</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="poppins-font text-oweru-dark">
                                                {{ $material->sw_name ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-oweru">
                                                TZS {{ number_format($material->price, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($material->status === 'active')
                                                <span class="badge-active px-3 py-2">
                                                    <i class="fas fa-check-circle me-1"></i>Active
                                                </span>
                                            @else
                                                <span class="badge-inactive px-3 py-2">
                                                    <i class="fas fa-pause-circle me-1"></i>Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="poppins-font text-oweru-dark">
                                                {{ $material->creator->username ?? 'System' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <small class="text-oweru-dark futura-font d-block">
                                                    {{ $material->created_at->format('M d, Y') }}
                                                </small>
                                                <small class="text-oweru-gray poppins-font">
                                                    {{ $material->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.materials.edit', $material->id) }}" 
                                                   class="btn-oweru-outline btn-sm" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit Material">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.materials.toggle-status', $material->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn {{ $material->status == 'active' ? 'btn-oweru-warning' : 'btn-oweru-success' }} btn-sm"
                                                            data-bs-toggle="tooltip" 
                                                            title="{{ $material->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                                        <i class="fas {{ $material->status == 'active' ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this material?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn-oweru-danger btn-sm"
                                                            data-bs-toggle="tooltip" 
                                                            title="Delete Material">
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
                    <div class="card-footer bg-oweru-light border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="poppins-font text-oweru-gray">
                                Showing {{ $materials->firstItem() }} to {{ $materials->lastItem() }} of {{ $materials->total() }} materials
                            </div>
                            <nav>
                                {{ $materials->links() }}
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-bricks"></i>
                        </div>
                        <h4 class="futura-font text-oweru-dark mb-3">No Materials Found</h4>
                        <p class="poppins-font text-oweru-gray mb-4">Get started by adding your first construction material to the system.</p>
                        <a href="{{ route('admin.materials.create') }}" class="btn-oweru-primary">
                            <i class="fas fa-plus me-2"></i>Add First Material
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
    const tableRows = document.querySelectorAll('.table-oweru tbody tr');
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
        const rows = document.querySelectorAll('.table-oweru tbody tr');
        
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
        const rows = document.querySelectorAll('.table-oweru tbody tr');
        
        rows.forEach(row => {
            if (!filterValue) {
                row.style.display = '';
            } else {
                const statusBadge = row.querySelector('.badge-active, .badge-inactive');
                if (statusBadge) {
                    const isActive = statusBadge.classList.contains('badge-active');
                    const shouldShow = (filterValue === 'active' && isActive) || 
                                     (filterValue === 'inactive' && !isActive);
                    row.style.display = shouldShow ? '' : 'none';
                }
            }
        });
    });
});
</script>
@endpush
@endsection