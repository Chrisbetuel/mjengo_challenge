@extends('layouts.app')

@section('title', 'Manage Materials')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Materials</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.materials.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Material
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Swahili Name</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                    <tr>
                        <td>{{ $material->id }}</td>
                        <td>
                            @if($material->image)
                                <img src="{{ asset('storage/' . $material->image) }}" alt="{{ $material->name }}" width="50" height="50" class="rounded">
                            @else
                                <i class="fas fa-bricks fa-2x text-secondary"></i>
                            @endif
                        </td>
                        <td>{{ $material->name }}</td>
                        <td>{{ $material->sw_name ?? '-' }}</td>
                        <td>TZS {{ number_format($material->price, 2) }}</td>
                        <td>
                            <span class="badge {{ $material->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($material->status) }}
                            </span>
                        </td>
                        <td>{{ $material->creator->name ?? 'N/A' }}</td>
                        <td>{{ $material->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.materials.edit', $material->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.materials.toggle-status', $material->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $material->status == 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $material->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas {{ $material->status == 'active' ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this material?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-info-circle me-2"></i>
                            No materials found. <a href="{{ route('admin.materials.create') }}">Create one now</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($materials->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $materials->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
