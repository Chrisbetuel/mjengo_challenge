@extends('layouts.app')

@section('title', 'Manage Payments - Admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Payment Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">All Payments ({{ $payments->total() }})</h6>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                Filter by Status
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">All</a></li>
                <li><a class="dropdown-item" href="#">Paid</a></li>
                <li><a class="dropdown-item" href="#">Pending</a></li>
                <li><a class="dropdown-item" href="#">Failed</a></li>
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
                        <th>Amount (TZS)</th>
                        <th>Payment Date</th>
                        <th>Status</th>
                        <th>Transaction ID</th>
                        <th>Payment Method</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>
                            <strong>{{ $payment->participant->user->username }}</strong>
                            <br>
                            <small class="text-muted">{{ $payment->participant->user->email ?? 'No email' }}</small>
                        </td>
                        <td>
                            {{ $payment->participant->challenge->name }}
                            <br>
                            <small class="text-muted">Daily: TZS {{ number_format($payment->participant->challenge->daily_amount, 2) }}</small>
                        </td>
                        <td class="text-nowrap">
                            <strong class="text-success">TZS {{ number_format($payment->amount, 2) }}</strong>
                        </td>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td>
                            @if($payment->status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($payment->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $payment->transaction_id ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $payment->payment_method ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($payment->status === 'pending')
                                    <button class="btn btn-outline-success" data-bs-toggle="tooltip" title="Mark as Paid">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Mark as Failed">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                <button class="btn btn-outline-info" data-bs-toggle="tooltip" title="Resend Notification">
                                    <i class="fas fa-bell"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <i class="fas fa-money-bill-wave fa-2x mb-3"></i><br>
                            No payments found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} payments
            </div>
            <nav>
                {{ $payments->links() }}
            </nav>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Paid</h6>
                        <h3 class="card-text">TZS {{ number_format($payments->where('status', 'paid')->sum('amount'), 2) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Pending Payments</h6>
                        <h3 class="card-text">{{ $payments->where('status', 'pending')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
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
                        <h6 class="card-title">Failed Payments</h6>
                        <h3 class="card-text">{{ $payments->where('status', 'failed')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x"></i>
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
                        <h6 class="card-title">Total Transactions</h6>
                        <h3 class="card-text">{{ $payments->total() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exchange-alt fa-2x"></i>
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