@extends('layouts.app')

@section('title', 'Manage Penalties - Admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Penalty Management</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">All Penalties ({{ $penalties->total() }})</h6>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                Filter by Status
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">All</a></li>
                <li><a class="dropdown-item" href="#">Active</a></li>
                <li><a class="dropdown-item" href="#">Appealed</a></li>
                <li><a class="dropdown-item" href="#">Resolved</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Challenge</th>
                        <th>Type</th>
                        <th>Amount (TZS)</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penalties as $penalty)
                    <tr>
                        <td>{{ $penalty->id }}</td>
                        <td>
                            <strong>{{ $penalty->user->username }}</strong>
                            <br>
                            <small class="text-muted">{{ $penalty->user->email ?? 'No email' }}</small>
                        </td>
                        <td>
                            @if($penalty->challenge)
                                {{ $penalty->challenge->name }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $penalty->penalty_type === 'late_payment' ? 'warning' : ($penalty->penalty_type === 'missed_payment' ? 'danger' : 'secondary') }}">
                                {{ $penalty->getPenaltyTypeText() }}
                            </span>
                        </td>
                        <td class="text-nowrap">
                            <strong class="text-danger">TZS {{ number_format($penalty->amount, 2) }}</strong>
                        </td>
                        <td>
                            <small>{{ Str::limit($penalty->reason, 50) }}</small>
                        </td>
                        <td>
                            @if($penalty->status === 'active')
                                <span class="badge bg-warning">Active</span>
                            @elseif($penalty->status === 'appealed')
                                <span class="badge bg-info">Appealed</span>
                            @else
                                <span class="badge bg-success">Resolved</span>
                            @endif
                        </td>
                        <td>{{ $penalty->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @if($penalty->status === 'active')
                                    <form action="{{ route('admin.penalties.resolve', $penalty->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success" data-bs-toggle="tooltip" title="Mark as Resolved">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <button class="btn btn-outline-info" data-bs-toggle="tooltip" title="View Appeal">
                                        <i class="fas fa-gavel"></i>
                                    </button>
                                @elseif($penalty->status === 'appealed')
                                    <button class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Review Appeal">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <form action="{{ route('admin.penalties.resolve', $penalty->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success" data-bs-toggle="tooltip" title="Approve Appeal">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Resolved</span>
                                @endif
                                <button class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i><br>
                            No penalties found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Showing {{ $penalties->firstItem() }} to {{ $penalties->lastItem() }} of {{ $penalties->total() }} penalties
            </div>
            <nav>
                {{ $penalties->links() }}
            </nav>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Active Penalties</h6>
                        <h3 class="card-text">{{ $penalties->where('status', 'active')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Appealed Penalties</h6>
                        <h3 class="card-text">{{ $penalties->where('status', 'appealed')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-gavel fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Resolved Penalties</h6>
                        <h3 class="card-text">{{ $penalties->where('status', 'resolved')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Penalty Amount</h6>
                        <h3 class="card-text">TZS {{ number_format($penalties->sum('amount'), 2) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush