@extends('layouts.app')

@section('title', 'My Penalties')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">My Penalties</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Your Penalties ({{ $penalties->total() }})</h6>
        </div>
        <div class="card-body">
            @if($penalties->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
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
                            @foreach($penalties as $penalty)
                            <tr>
                                <td>{{ $penalty->id }}</td>
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
                                    @elseif($penalty->status === 'overdue')
                                        <span class="badge bg-danger">Overdue</span>
                                    @else
                                        <span class="badge bg-success">Resolved</span>
                                    @endif
                                </td>
                                <td>{{ $penalty->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('penalties.show', $penalty->id) }}" class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($penalty->isActive())
                                            <button class="btn btn-outline-warning" title="Appeal Penalty" data-bs-toggle="modal" data-bs-target="#appealModal{{ $penalty->id }}">
                                                <i class="fas fa-gavel"></i> Appeal
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
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
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h4 class="text-success">No Penalties Found</h4>
                    <p class="text-muted">Great job! You don't have any penalties at the moment.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Appeal Modals -->
    @foreach($penalties as $penalty)
        @if($penalty->isActive())
        <div class="modal fade" id="appealModal{{ $penalty->id }}" tabindex="-1" aria-labelledby="appealModalLabel{{ $penalty->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="appealModalLabel{{ $penalty->id }}">Appeal Penalty #{{ $penalty->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('penalties.appeal', $penalty->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="appeal_reason{{ $penalty->id }}" class="form-label">Appeal Reason</label>
                                <textarea class="form-control" id="appeal_reason{{ $penalty->id }}" name="appeal_reason" rows="4" placeholder="Please explain why you believe this penalty should be removed or reduced..." required></textarea>
                                <div class="form-text">Your appeal will be reviewed by an administrator.</div>
                            </div>
                            <div class="alert alert-info">
                                <strong>Penalty Details:</strong><br>
                                Type: {{ $penalty->getPenaltyTypeText() }}<br>
                                Amount: TZS {{ number_format($penalty->amount, 2) }}<br>
                                Reason: {{ $penalty->reason }}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">Submit Appeal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
