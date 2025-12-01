@extends('layouts.app')

@section('title', 'Direct Purchases - Admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Direct Purchases Management</h1>
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
        <h6 class="m-0 font-weight-bold text-primary">All Direct Purchases ({{ $directPurchases->total() }})</h6>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                Filter by Status
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">All</a></li>
                <li><a class="dropdown-item" href="#">Paid</a></li>
                <li><a class="dropdown-item" href="#">Pending</a></li>
                <li><a class="dropdown-item" href="#">Shipped</a></li>
                <li><a class="dropdown-item" href="#">Delivered</a></li>
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
                        <th>Quantity</th>
                        <th>Total Amount (TZS)</th>
                        <th>Status</th>
                        <th>Delivery Address</th>
                        <th>Phone</th>
                        <th>Purchase Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($directPurchases as $purchase)
                    <tr>
                        <td>{{ $purchase->id }}</td>
                        <td>
                            <strong>{{ $purchase->user->username }}</strong>
                            <br>
                            <small class="text-muted">{{ $purchase->user->email }}</small>
                        </td>
                        <td>
                            <strong>{{ $purchase->material->name }}</strong>
                            <br>
                            <small class="text-muted">{{ number_format($purchase->unit_price, 2) }} TZS each</small>
                        </td>
                        <td>{{ $purchase->quantity }}</td>
                        <td>{{ number_format($purchase->total_amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $purchase->status === 'paid' ? 'success' : ($purchase->status === 'pending' ? 'warning' : ($purchase->status === 'shipped' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($purchase->status) }}
                            </span>
                        </td>
                        <td>{{ Str::limit($purchase->delivery_address, 30) }}</td>
                        <td>{{ $purchase->phone_number }}</td>
                        <td>{{ $purchase->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($purchase->status === 'paid')
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Mark as Shipped">
                                        <i class="fas fa-truck"></i>
                                    </button>
                                @elseif($purchase->status === 'shipped')
                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Mark as Delivered">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No direct purchases found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $directPurchases->links() }}
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
