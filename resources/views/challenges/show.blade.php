@extends('layouts.app')

@section('title', $challenge->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $challenge->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('challenges.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Challenges
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
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Challenge Details</h5>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $challenge->description }}</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <strong><i class="fas fa-money-bill-wave me-2"></i>Daily Amount:</strong>
                        <p class="h5 text-primary">TZS {{ number_format($challenge->daily_amount, 2) }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-users me-2"></i>Participants:</strong>
                        <p class="h5 text-primary">{{ $challenge->active_participants_count }} / {{ $challenge->max_participants }}</p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <strong><i class="fas fa-calendar-start me-2"></i>Start Date:</strong>
                        <p>{{ $challenge->start_date->format('F d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-calendar-end me-2"></i>End Date:</strong>
                        <p>{{ $challenge->end_date->format('F d, Y') }}</p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <strong><i class="fas fa-chart-line me-2"></i>Progress:</strong>
                        @php
                            $totalDays = $challenge->start_date->diffInDays($challenge->end_date);
                            $daysPassed = $challenge->start_date->diffInDays(now());
                            $progress = $totalDays > 0 ? min(100, ($daysPassed / $totalDays) * 100) : 0;
                            $totalCollected = $challenge->getTotalCollected();
                            $estimatedTotal = $challenge->daily_amount * $challenge->active_participants_count * $totalDays;
                        @endphp
                        <div class="progress mt-2" style="height: 20px;">
                            <div class="progress-bar bg-success" style="width: {{ $progress }}%">
                                {{ round($progress) }}%
                            </div>
                        </div>
                        <small class="text-muted">
                            {{ $daysPassed }} of {{ $totalDays }} days completed
                        </small>
                    </div>
                </div>
            </div>
        </div>

        @if($isParticipant)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">My Participation</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Queue Position:</strong>
                        <p class="h5">#{{ $userParticipant->queue_position }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Total Paid:</strong>
                        <p class="h5 text-success">TZS {{ number_format($userParticipant->getTotalPaid(), 2) }}</p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <form action="{{ route('challenges.payment', $challenge->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="card">Credit/Debit Card</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-credit-card me-2"></i>
                            Pay Today's Amount: TZS {{ number_format($challenge->daily_amount, 2) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                @if(!$isParticipant && $availableSlots > 0)
                <form action="{{ route('challenges.join', $challenge->id) }}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-plus me-2"></i>Join Challenge
                    </button>
                </form>
                @elseif(!$isParticipant)
                <button class="btn btn-secondary w-100" disabled>
                    <i class="fas fa-times me-2"></i>Challenge Full
                </button>
                @endif
                
                <a href="#" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-share-alt me-2"></i>Share Challenge
                </a>
                
                <a href="#" class="btn btn-outline-info w-100">
                    <i class="fas fa-question-circle me-2"></i>Get Help
                </a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Challenge Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Total Collected:</strong>
                    <p class="h5 text-success">TZS {{ number_format($totalCollected, 2) }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Available Slots:</strong>
                    <p class="h5 text-info">{{ $availableSlots }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Challenge Creator:</strong>
                    <p>{{ $challenge->creator->username }}</p>
                </div>
                
                <div>
                    <strong>Status:</strong>
                    <span class="badge bg-{{ $challenge->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($challenge->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection