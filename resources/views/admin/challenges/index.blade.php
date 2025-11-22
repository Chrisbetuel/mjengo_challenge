@extends('layouts.app')

@section('title', 'Manage Challenges')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Manage Challenges</h1>
                <a href="{{ route('admin.challenges.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New Challenge
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Challenges
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $challenges->total() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-trophy fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Active Challenges
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $challenges->where('status', 'active')->count() }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-play fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Total Participants
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $challenges->sum('active_participants_count') }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Total Payments
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($challenges->sum('payments_sum_amount') ?? 0) }} KES
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Challenges Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">All Challenges</h6>
                </div>
                <div class="card-body">
                    @if($challenges->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Creator</th>
                                        <th>Daily Amount</th>
                                        <th>Participants</th>
                                        <th>Status</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Total Collected</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($challenges as $challenge)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <div class="font-weight-bold">{{ $challenge->name }}</div>
                                                        <small class="text-muted">{{ Str::limit($challenge->description, 50) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $challenge->creator->username ?? 'N/A' }}</td>
                                            <td>{{ number_format($challenge->daily_amount) }} KES</td>
                                            <td>
                                                <span class="badge badge-primary">{{ $challenge->active_participants_count }}/{{ $challenge->max_participants }}</span>
                                            </td>
                                            <td>
                                                @if($challenge->status === 'active')
                                                    <span class="badge badge-success">Active</span>
                                                @elseif($challenge->status === 'completed')
                                                    <span class="badge badge-info">Completed</span>
                                                @elseif($challenge->status === 'cancelled')
                                                    <span class="badge badge-danger">Cancelled</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst($challenge->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $challenge->start_date->format('M d, Y') }}</td>
                                            <td>{{ $challenge->end_date->format('M d, Y') }}</td>
                                            <td>{{ number_format($challenge->payments_sum_amount ?? 0) }} KES</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('challenges.show', $challenge->id) }}"
                                                       class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-warning" title="Edit Challenge" disabled>
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" title="Delete Challenge" disabled>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $challenges->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-trophy fa-4x text-gray-300 mb-3"></i>
                            <h4 class="text-gray-500">No Challenges Found</h4>
                            <p class="text-gray-400 mb-4">Get started by creating your first challenge.</p>
                            <a href="{{ route('admin.challenges.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create First Challenge
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any interactive functionality here
    console.log('Challenges management page loaded');
});
</script>
@endpush
@endsection
