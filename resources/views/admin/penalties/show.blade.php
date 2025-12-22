@extends('layouts.app')

@section('title', 'Penalty Details - Admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Penalty Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.penalties') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Penalties
        </a>
        <a href="{{ route('admin.penalties.create') }}" class="btn btn-primary ms-2">
            <i class="fas fa-plus"></i> Create New Penalty
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <!-- Penalty Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Penalty Information</h6>
                <div>
                    @if($penalty->status === 'active')
                        <form action="{{ route('admin.penalties.resolve', $penalty->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Mark as Resolved">
                                <i class="fas fa-check"></i> Resolve
                            </button>
                        </form>
                    @elseif($penalty->status === 'appealed')
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Review Appeal">
                            <i class="fas fa-gavel"></i> Review Appeal
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-muted">Penalty ID:</strong>
                            <span class="ms-2">#{{ $penalty->id }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-muted">Status:</strong>
                            <span class="ms-2">
                                @if($penalty->status === 'active')
                                    <span class="badge bg-warning">Active</span>
                                @elseif($penalty->status === 'appealed')
                                    <span class="badge bg-info">Appealed</span>
                                @elseif($penalty->status === 'overdue')
                                    <span class="badge bg-danger">Overdue</span>
                                @else
                                    <span class="badge bg-success">Resolved</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-muted">Penalty Type:</strong>
                            <span class="ms-2 badge bg-secondary">{{ $penalty->getPenaltyTypeText() }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-muted">Amount:</strong>
                            <span class="ms-2 text-danger fw-bold">TZS {{ number_format($penalty->amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <strong class="text-muted">Reason:</strong>
                    <div class="mt-2 p-3 bg-light rounded">
                        {{ $penalty->reason }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-muted">Created:</strong>
                            <span class="ms-2">{{ $penalty->created_at->format('M d, Y \a\t H:i') }}</span>
                        </div>
                    </div>
                    @if($penalty->resolved_at)
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-muted">Resolved:</strong>
                            <span class="ms-2">{{ $penalty->resolved_at->format('M d, Y \a\t H:i') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-muted">Username:</strong>
                            <span class="ms-2">{{ $penalty->user->username }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-muted">Email:</strong>
                            <span class="ms-2">{{ $penalty->user->email ?? 'Not provided' }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <strong class="text-muted">Challenge Debt:</strong>
                            <span class="ms-2 text-warning fw-bold">TZS {{ number_format($penalty->user->challenge_debt ?? 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <strong class="text-muted">Lipa Kidogo Debt:</strong>
                            <span class="ms-2 text-info fw-bold">TZS {{ number_format($penalty->user->lipa_kidogo_debt ?? 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <strong class="text-muted">Total Debt:</strong>
                            <span class="ms-2 text-danger fw-bold">TZS {{ number_format($penalty->user->total_debt ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <a href="{{ route('admin.users.show', $penalty->user->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-user"></i> View Full User Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Challenge Information (if applicable) -->
        @if($penalty->challenge)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Related Challenge</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong class="text-muted">Challenge Name:</strong>
                    <span class="ms-2">{{ $penalty->challenge->name }}</span>
                </div>
                <div class="mb-3">
                    <strong class="text-muted">Status:</strong>
                    <span class="ms-2">
                        @if($penalty->challenge->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($penalty->challenge->status === 'completed')
                            <span class="badge bg-primary">Completed</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($penalty->challenge->status) }}</span>
                        @endif
                    </span>
                </div>
                <div class="mb-3">
                    <strong class="text-muted">Daily Amount:</strong>
                    <span class="ms-2">TZS {{ number_format($penalty->challenge->daily_amount, 2) }}</span>
                </div>
                <div class="mb-3">
                    <a href="{{ route('admin.challenges') }}?id={{ $penalty->challenge->id }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-trophy"></i> View Challenge
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.penalties.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Penalty
                    </a>
                    <a href="{{ route('admin.users.show', $penalty->user->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-user"></i> View User Details
                    </a>
                    @if($penalty->challenge)
                    <a href="{{ route('admin.challenges') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-trophy"></i> View All Challenges
                    </a>
                    @endif
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
