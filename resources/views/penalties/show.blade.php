@extends('layouts.app')

@section('title', 'Penalty Details - Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Penalty Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('penalties.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Penalties
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Penalty #{{ $penalty->id }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary">Penalty Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Penalty ID:</strong></td>
                                <td>{{ $penalty->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Type:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $penalty->penalty_type === 'late_payment' ? 'warning' : ($penalty->penalty_type === 'missed_payment' ? 'danger' : 'secondary') }}">
                                        {{ $penalty->getPenaltyTypeText() }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Amount:</strong></td>
                                <td><strong class="text-danger h5">TZS {{ number_format($penalty->amount, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
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
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $penalty->created_at->format('M d, Y \a\t H:i') }}</td>
                            </tr>
                            @if($penalty->updated_at != $penalty->created_at)
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $penalty->updated_at->format('M d, Y \a\t H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-primary">Reason & Details</h5>
                        <div class="mb-3">
                            <strong>Reason:</strong>
                            <p class="mt-2">{{ $penalty->reason }}</p>
                        </div>

                        @if($penalty->challenge)
                        <div class="mb-3">
                            <strong>Related Challenge:</strong>
                            <p class="mt-2">
                                <a href="{{ route('challenges.show', $penalty->challenge->id) }}" class="text-decoration-none">
                                    {{ $penalty->challenge->name }}
                                </a>
                            </p>
                        </div>
                        @endif

                        @if($penalty->status === 'resolved')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>This penalty has been resolved.</strong>
                            @if($penalty->resolved_at)
                            <br><small>Resolved on: {{ $penalty->resolved_at->format('M d, Y \a\t H:i') }}</small>
                            @endif
                        </div>
                        @elseif($penalty->status === 'appealed')
                        <div class="alert alert-info">
                            <i class="fas fa-gavel"></i>
                            <strong>This penalty has been appealed.</strong>
                            <br><small>Your appeal is under review by the administrators.</small>
                        </div>
                        @elseif($penalty->status === 'overdue')
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>This penalty is overdue.</strong>
                            <br><small>Please contact support to resolve this issue.</small>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>This penalty is currently active.</strong>
                            <br><small>You can appeal this penalty if you believe it's incorrect.</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                @if($penalty->status === 'active')
                <button class="btn btn-warning btn-block mb-2" data-bs-toggle="modal" data-bs-target="#appealModal">
                    <i class="fas fa-gavel"></i> Appeal Penalty
                </button>
                @endif

                <a href="mailto:support@oweru.com?subject=Inquiry about Penalty #{{ $penalty->id }}" class="btn btn-info btn-block mb-2">
                    <i class="fas fa-envelope"></i> Contact Support
                </a>

                <a href="{{ route('penalties.index') }}" class="btn btn-outline-secondary btn-block">
                    <i class="fas fa-list"></i> View All Penalties
                </a>
            </div>
        </div>

        <!-- Related Information -->
        @if($penalty->challenge)
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Related Challenge</h6>
            </div>
            <div class="card-body">
                <h6>{{ $penalty->challenge->name }}</h6>
                <p class="small text-muted mb-2">{{ Str::limit($penalty->challenge->description, 100) }}</p>
                <div class="d-flex justify-content-between text-sm">
                    <span>Daily Amount: <strong>TZS {{ number_format($penalty->challenge->daily_amount, 0) }}</strong></span>
                </div>
                <div class="d-flex justify-content-between text-sm mt-1">
                    <span>Status: <span class="badge bg-{{ $penalty->challenge->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($penalty->challenge->status) }}</span></span>
                </div>
                <a href="{{ route('challenges.show', $penalty->challenge->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                    <i class="fas fa-eye"></i> View Challenge
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Appeal Modal -->
@if($penalty->status === 'active')
<div class="modal fade" id="appealModal" tabindex="-1" aria-labelledby="appealModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appealModalLabel">Appeal Penalty #{{ $penalty->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('penalties.appeal', $penalty->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="appeal_reason" class="form-label">Appeal Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="appeal_reason" name="appeal_reason" rows="4" placeholder="Please explain why you believe this penalty is incorrect..." required></textarea>
                        <div class="form-text">Provide detailed reasons for your appeal. This will be reviewed by administrators.</div>
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
@endsection
