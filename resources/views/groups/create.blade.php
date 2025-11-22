@extends('layouts.app')

@section('title', 'Groups - Mjengo Challenge')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Groups</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('groups.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Create Group
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<!-- User's Groups -->
@if($userGroups->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">My Groups</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($userGroups as $membership)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100 border-primary">
                            <div class="card-body">
                                <h5 class="card-title">{{ $membership->group->name }}</h5>
                                <p class="card-text text-muted small">{{ Str::limit($membership->group->description, 100) }}</p>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $membership->group->activeMembers->count() }} / {{ $membership->group->max_members }} members
                                    </small>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        Leader: {{ $membership->group->leader->username }}
                                    </small>
                                </div>
                                @if($membership->group->challenge)
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-trophy me-1"></i>
                                        Challenge: {{ $membership->group->challenge->name }}
                                    </small>
                                </div>
                                @endif
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('groups.show', $membership->group->id) }}" class="btn btn-sm btn-primary w-100">
                                    View Group
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- All Active Groups -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Available Groups</h5>
            </div>
            <div class="card-body">
                @if($groups->count() > 0)
                    <div class="row">
                        @foreach($groups as $group)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $group->name }}</h5>
                                    <p class="card-text text-muted small">{{ Str::limit($group->description, 100) }}</p>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-users me-1"></i>
                                            {{ $group->activeMembers->count() }} / {{ $group->max_members }} members
                                        </small>
                                        <div class="progress mt-1" style="height: 5px;">
                                            <div class="progress-bar" style="width: {{ ($group->activeMembers->count() / $group->max_members) * 100 }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            Leader: {{ $group->leader->username }}
                                        </small>
                                    </div>
                                    
                                    @if($group->challenge)
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-trophy me-1"></i>
                                            Challenge: {{ $group->challenge->name }}
                                        </small>
                                    </div>
                                    @endif
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            Created: {{ $group->created_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    @if(auth()->user()->id === $group->leader_id)
                                        <span class="badge bg-primary">You are the leader</span>
                                    @elseif($group->members->where('user_id', auth()->id())->where('status', 'active')->count() > 0)
                                        <span class="badge bg-success">Already a member</span>
                                    @elseif($group->isFull())
                                        <span class="badge bg-secondary">Group Full</span>
                                    @else
                                        <form action="{{ route('groups.join', $group->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm w-100">
                                                <i class="fas fa-user-plus me-1"></i>Join Group
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('groups.show', $group->id) }}" class="btn btn-outline-primary btn-sm w-100 mt-1">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No groups available</h5>
                        <p class="text-muted">Be the first to create a group and start saving together!</p>
                        <a href="{{ route('groups.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create First Group
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection