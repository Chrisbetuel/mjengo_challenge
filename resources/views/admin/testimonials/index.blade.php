@extends('layouts.app')

@section('title', 'Manage Testimonials')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-oweru-dark text-white">
                    <h4 class="mb-0 futura-font">
                        <i class="fas fa-comments me-2"></i>Manage Testimonials
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Content</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($testimonials as $testimonial)
                                <tr>
                                    <td>{{ $testimonial->user->username }}</td>
                                    <td>{{ Str::limit($testimonial->content, 100) }}</td>
                                    <td>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </td>
                                    <td>
                                        @if($testimonial->is_approved)
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($testimonial->is_featured)
                                            <span class="badge bg-primary">Featured</span>
                                        @else
                                            <span class="badge bg-secondary">Normal</span>
                                        @endif
                                    </td>
                                    <td>{{ $testimonial->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            @if(!$testimonial->is_approved)
                                                <form action="{{ route('admin.testimonials.approve', $testimonial) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($testimonial->is_approved && !$testimonial->is_featured)
                                                <form action="{{ route('admin.testimonials.feature', $testimonial) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm" title="Feature">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($testimonial->is_featured)
                                                <form action="{{ route('admin.testimonials.unfeature', $testimonial) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-secondary btn-sm" title="Unfeature">
                                                        <i class="fas fa-star-half-alt"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Are you sure you want to delete this testimonial?')"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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
</div>
@endsection