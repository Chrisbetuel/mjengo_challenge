@extends('layouts.app')

@section('title', 'Lipa Kidogo Plans - Admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Lipa Kidogo Plans Management</h1>
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
        <h6 class="m-0 font-weight-bold text-primary">All Lipa Kidogo Plans ({{ $lipaKidogoPlans->total() }})</h6>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                Filter by Status
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">All</a></li>
                <li><a class="dropdown-item" href="#">Active</a></li>
                <li><a class="dropdown-item" href="#">Completed</a></li>
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
                        <th>Material</th>
                        <th>Total Amount (TZS)</th>
                        <th>Installment Amount (TZS)</th>
                        <th>Installments</th>
                        <th>Paid / Total</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>Payment Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lipaKidogoPlans as $plan)
                    <tr>
                        <td>{{ $plan->id }}</td>
                        <td>
                            <strong>{{ $plan->user->username }}</strong>
                            <br>
                            <small class="text-muted">{{ $plan->user->email }}</small>
                        </td>
                        <td>
                            <strong>{{ $plan->material->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $plan->user_type }}</small>
                        </td>
                        <td>{{ number_format($plan->total_amount, 2) }}</td>
                        <td>{{ number_format($plan->installment_amount, 2) }}</td>
                        <td>{{ $plan->num_installments }}</td>
                        <td>
                            <span class="badge bg-info">{{ $plan->paid_installments_count }} / {{ $plan->num_installments }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $plan->status === 'active' ? 'success' : ($plan->status === 'completed' ? 'primary' : ($plan->status === 'pending' ? 'warning' : 'danger')) }}">
                                {{ ucfirst($plan->status) }}
                            </span>
                        </td>
                        <td>{{ $plan->start_date->format('M d, Y') }}</td>
                        <td>{{ $plan->payment_duration }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Installments">
                                    <i class="fas fa-list"></i>
                                </button>
                                @if($plan->status === 'active')
                                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Pause Plan">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                @elseif($plan->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Activate Plan">
                                        <i class="fas fa-play"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-4">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No Lipa Kidogo plans found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $lipaKidogoPlans->links() }}
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
