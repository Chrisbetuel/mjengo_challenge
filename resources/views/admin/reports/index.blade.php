@extends('layouts.app')

@section('title', 'Reports & Analytics - Admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Reports & Analytics</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Export PDF</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export Excel</button>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Revenue</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            TZS {{ number_format($monthlyRevenue->sum('total'), 2) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                            Active Challenges</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $challengePerformance->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-trophy fa-2x text-gray-300"></i>
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
                            Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userActivity->count() }}</div>
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
                            Avg. Payment</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            TZS {{ number_format($monthlyRevenue->avg('total'), 2) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Monthly Revenue -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Monthly Revenue (Last 12 Months)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Revenue (TZS)</th>
                                <th>Growth</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyRevenue as $revenue)
                            <tr>
                                <td>{{ date('F Y', mktime(0, 0, 0, $revenue->month, 1, $revenue->year)) }}</td>
                                <td class="text-nowrap"><strong>TZS {{ number_format($revenue->total, 2) }}</strong></td>
                                <td>
                                    @php
                                        $prevMonth = $monthlyRevenue->where('year', $revenue->year - ($revenue->month == 1 ? 1 : 0))
                                            ->where('month', $revenue->month == 1 ? 12 : $revenue->month - 1)
                                            ->first();
                                        $growth = $prevMonth ? (($revenue->total - $prevMonth->total) / $prevMonth->total) * 100 : 0;
                                    @endphp
                                    <span class="badge bg-{{ $growth >= 0 ? 'success' : 'danger' }}">
                                        {{ $growth >= 0 ? '+' : '' }}{{ number_format($growth, 1) }}%
                                    </span>
                                </td>
                                <td>
                                    @if($growth > 20)
                                        <i class="fas fa-arrow-up text-success"></i> Strong Growth
                                    @elseif($growth > 0)
                                        <i class="fas fa-arrow-up text-success"></i> Growing
                                    @elseif($growth < -10)
                                        <i class="fas fa-arrow-down text-danger"></i> Declining
                                    @else
                                        <i class="fas fa-minus text-warning"></i> Stable
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Users -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Top Active Users</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Payments</th>
                                <th>Total Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userActivity as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->username }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $user->email ?? 'No email' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $user->payments_count }}</span>
                                </td>
                                <td class="text-nowrap">
                                    <strong>TZS {{ number_format($user->payments->sum('amount'), 2) }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Challenge Performance -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Challenge Performance</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Challenge Name</th>
                                <th>Participants</th>
                                <th>Daily Amount</th>
                                <th>Total Collected</th>
                                <th>Completion Rate</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($challengePerformance as $challenge)
                            <tr>
                                <td>
                                    <strong>{{ $challenge->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $challenge->description ? Str::limit($challenge->description, 50) : 'No description' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $challenge->active_participants_count >= $challenge->max_participants ? 'success' : 'info' }}">
                                        {{ $challenge->active_participants_count }} / {{ $challenge->max_participants }}
                                    </span>
                                </td>
                                <td class="text-nowrap">TZS {{ number_format($challenge->daily_amount, 2) }}</td>
                                <td class="text-nowrap">
                                    <strong>TZS {{ number_format($challenge->payments_sum_amount ?? 0, 2) }}</strong>
                                </td>
                                <td>
                                    @php
                                        $totalDays = $challenge->start_date->diffInDays($challenge->end_date);
                                        $daysPassed = $challenge->start_date->diffInDays(now());
                                        $completionRate = $totalDays > 0 ? min(100, ($daysPassed / $totalDays) * 100) : 0;
                                    @endphp
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $completionRate >= 75 ? 'success' : ($completionRate >= 50 ? 'warning' : 'danger') }}" 
                                             style="width: {{ $completionRate }}%">
                                            {{ round($completionRate) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $challenge->status === 'active' ? 'success' : ($challenge->status === 'completed' ? 'primary' : 'secondary') }}">
                                        {{ ucfirst($challenge->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">System Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-trophy fa-2x text-primary mb-2"></i>
                            <h5>{{ $challengePerformance->count() }}</h5>
                            <small class="text-muted">Total Challenges</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-users fa-2x text-success mb-2"></i>
                            <h5>{{ $userActivity->count() }}</h5>
                            <small class="text-muted">Active Users</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-bricks fa-2x text-warning mb-2"></i>
                            <h5>{{ $challengePerformance->sum('active_participants_count') }}</h5>
                            <small class="text-muted">Total Participants</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Performance Metrics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-percentage fa-2x text-info mb-2"></i>
                            <h5>
                                @php
                                    $totalCapacity = $challengePerformance->sum('max_participants');
                                    $filledCapacity = $challengePerformance->sum('active_participants_count');
                                    $utilization = $totalCapacity > 0 ? ($filledCapacity / $totalCapacity) * 100 : 0;
                                @endphp
                                {{ round($utilization) }}%
                            </h5>
                            <small class="text-muted">Capacity Utilization</small>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-chart-line fa-2x text-danger mb-2"></i>
                            <h5>
                                @php
                                    $avgCompletion = $challengePerformance->avg(function($challenge) {
                                        $totalDays = $challenge->start_date->diffInDays($challenge->end_date);
                                        $daysPassed = $challenge->start_date->diffInDays(now());
                                        return $totalDays > 0 ? min(100, ($daysPassed / $totalDays) * 100) : 0;
                                    });
                                @endphp
                                {{ round($avgCompletion) }}%
                            </h5>
                            <small class="text-muted">Avg. Completion</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection