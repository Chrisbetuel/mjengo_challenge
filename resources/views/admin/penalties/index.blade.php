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
                <li><a class="dropdown-item" href="?status=all">All</a></li>
                <li><a class="dropdown-item" href="?status=active">Active</a></li>
                <li><a class="dropdown-item" href="?status=appealed">Appealed</a></li>
                <li><a class="dropdown-item" href="?status=resolved">Resolved</a></li>
                <li><a class="dropdown-item" href="?status=overdue">Overdue</a></li>
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
                        <th>Challenge Debt</th>
                        <th>Lipa Kidogo Debt</th>
                        <th>Total Debt</th>
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
                        <td class="text-nowrap">
                            <strong class="text-warning">TZS {{ number_format($penalty->user->challenge_debt ?? 0, 2) }}</strong>
                        </td>
                        <td class="text-nowrap">
                            <strong class="text-info">TZS {{ number_format($penalty->user->lipa_kidogo_debt ?? 0, 2) }}</strong>
                        </td>
                        <td class="text-nowrap">
                            <strong class="text-danger">TZS {{ number_format($penalty->user->total_debt ?? 0, 2) }}</strong>
                        </td>
                        <td>
                            @if($penalty->status === 'active')
                                <span class="badge bg-warning">Active</span>
                            @elseif($penalty->status === 'appealed')
                                <span class="badge bg-info">Appealed</span>
                            @elseif($penalty->status === 'overdue')
                                <span class="badge bg-danger">Overdue</span>
                            @else
                                <span class="badge bg-success">Resolved</span>
                            @endif
                        </td>
                        <td>{{ $penalty->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.penalties.show', $penalty->id) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
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
                        <td colspan="12" class="text-center text-muted py-4">
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

<!-- Users with Debts Section -->
<div class="card shadow mt-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Users with Outstanding Debts ({{ $usersWithDebts->total() }})</h6>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                Filter by Debt Type
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?debt_filter=all">All Debts</a></li>
                <li><a class="dropdown-item" href="?debt_filter=challenge">Challenge Debts</a></li>
                <li><a class="dropdown-item" href="?debt_filter=lipa_kidogo">Lipa Kidogo Debts</a></li>
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
                        <th>Challenge Debt</th>
                        <th>Lipa Kidogo Debt</th>
                        <th>Total Debt</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usersWithDebts as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <strong>{{ $user->username }}</strong>
                            <br>
                            <small class="text-muted">{{ $user->email ?? 'No email' }}</small>
                        </td>
                        <td class="text-nowrap">
                            <strong class="text-warning">TZS {{ number_format($user->challenge_debt ?? 0, 2) }}</strong>
                        </td>
                        <td class="text-nowrap">
                            <strong class="text-info">TZS {{ number_format($user->lipa_kidogo_debt ?? 0, 2) }}</strong>
                        </td>
                        <td class="text-nowrap">
                            <strong class="text-danger">TZS {{ number_format($user->total_debt ?? 0, 2) }}</strong>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.penalties.create', ['user_id' => $user->id]) }}" class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Create Penalty">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <button class="btn btn-outline-info toggle-details" data-bs-toggle="tooltip" title="View Details" data-user-id="{{ $user->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Detailed debt breakdown row -->
                    <tr class="debt-details-row" id="details-{{ $user->id }}" style="display: none;">
                        <td colspan="6" class="p-0">
                            <div class="card m-2 border-warning">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-primary">Detailed Debt Breakdown for {{ $user->username }}</h6>
                                </div>
                                <div class="card-body p-0">
                                    @if($user->detailed_challenge_debts->count() > 0)
                                    <div class="p-3 border-bottom">
                                        <h6 class="text-warning mb-3"><i class="fas fa-trophy"></i> Challenge Debts</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Challenge</th>
                                                        <th>Installment</th>
                                                        <th>Amount</th>
                                                        <th>Due Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($user->detailed_challenge_debts as $debt)
                                                    <tr>
                                                        <td><strong>{{ $debt->challenge_name }}</strong></td>
                                                        <td>{{ $debt->installment_number }}/{{ $debt->total_installments }}</td>
                                                        <td class="text-nowrap"><strong class="text-danger">TZS {{ number_format($debt->amount, 2) }}</strong></td>
                                                        <td>{{ \Carbon\Carbon::parse($debt->due_date)->format('M d, Y') }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $debt->payment_status === 'pending' ? 'warning' : 'danger' }}">
                                                                {{ ucfirst($debt->payment_status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif

                                    @if($user->detailed_lipa_kidogo_debts->count() > 0)
                                    <div class="p-3">
                                        <h6 class="text-info mb-3"><i class="fas fa-shopping-cart"></i> Lipa Kidogo Debts</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Material</th>
                                                        <th>Installment</th>
                                                        <th>Amount</th>
                                                        <th>Due Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($user->detailed_lipa_kidogo_debts as $debt)
                                                    <tr>
                                                        <td><strong>{{ $debt->material_name }}</strong></td>
                                                        <td>{{ $debt->installment_number }}/{{ $debt->total_installments }}</td>
                                                        <td class="text-nowrap"><strong class="text-danger">TZS {{ number_format($debt->amount, 2) }}</strong></td>
                                                        <td>{{ \Carbon\Carbon::parse($debt->due_date)->format('M d, Y') }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $debt->installment_status === 'pending' ? 'warning' : 'danger' }}">
                                                                {{ ucfirst($debt->installment_status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif

                                    @if($user->detailed_challenge_debts->count() == 0 && $user->detailed_lipa_kidogo_debts->count() == 0)
                                    <div class="p-3 text-center text-muted">
                                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                                        <p>No detailed debt information available.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-2x mb-3"></i><br>
                            No users with outstanding debts found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination for users with debts -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Showing {{ $usersWithDebts->firstItem() }} to {{ $usersWithDebts->lastItem() }} of {{ $usersWithDebts->total() }} users with debts
            </div>
            <nav>
                {{ $usersWithDebts->links() }}
            </nav>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-2">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Active</h6>
                        <h3 class="card-text">{{ $penalties->where('status', 'active')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Appealed</h6>
                        <h3 class="card-text">{{ $penalties->where('status', 'appealed')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-gavel fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Overdue</h6>
                        <h3 class="card-text">{{ $penalties->where('status', 'overdue')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Resolved</h6>
                        <h3 class="card-text">{{ $penalties->where('status', 'resolved')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white">
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