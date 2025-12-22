@extends('layouts.app')

@section('title', 'Create Penalty - Admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New Penalty</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.penalties') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Penalties
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Penalty Details</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.penalties.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="user_id" class="form-label">Select User <span class="text-danger">*</span></label>
                        <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                            <option value="">Choose a user...</option>
                            @foreach($usersWithDebts as $user)
                                <option value="{{ $user->id }}" {{ (old('user_id') == $user->id || request('user_id') == $user->id) ? 'selected' : '' }}>
                                    {{ $user->username }} (Total Debt: TZS {{ number_format($user->total_debt, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="penalty_type" class="form-label">Penalty Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('penalty_type') is-invalid @enderror" id="penalty_type" name="penalty_type" required>
                            <option value="">Select penalty type...</option>
                            <option value="late_payment" {{ old('penalty_type') == 'late_payment' ? 'selected' : '' }}>Late Payment</option>
                            <option value="missed_payment" {{ old('penalty_type') == 'missed_payment' ? 'selected' : '' }}>Missed Payment</option>
                            <option value="group_violation" {{ old('penalty_type') == 'group_violation' ? 'selected' : '' }}>Group Violation</option>
                        </select>
                        @error('penalty_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Penalty Amount (TZS) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount"
                               value="{{ old('amount') }}" min="1000" step="100" required
                               placeholder="Minimum: TZS 1,000">
                        <div class="form-text">Minimum penalty amount is TZS 1,000</div>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="4"
                                  required placeholder="Provide detailed reason for the penalty...">{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="challenge_id" class="form-label">Related Challenge (Optional)</label>
                        <select class="form-select @error('challenge_id') is-invalid @enderror" id="challenge_id" name="challenge_id">
                            <option value="">Select a challenge (optional)...</option>
                            @foreach(\App\Models\Challenge::where('status', 'active')->get() as $challenge)
                                <option value="{{ $challenge->id }}" {{ old('challenge_id') == $challenge->id ? 'selected' : '' }}>
                                    {{ $challenge->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Only select if penalty is related to a specific challenge</div>
                        @error('challenge_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.penalties') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Penalty
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Penalty Guidelines</h6>
            </div>
            <div class="card-body">
                <h6 class="text-warning mb-3"><i class="fas fa-exclamation-triangle"></i> Important Notes</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Only users with outstanding debts can be penalized</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Minimum penalty amount is TZS 1,000</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Provide clear and detailed reasons</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Penalties are automatically set to 'active' status</li>
                </ul>

                <hr>

                <h6 class="text-info mb-3"><i class="fas fa-info-circle"></i> Penalty Types</h6>
                <div class="mb-2">
                    <strong class="text-warning">Late Payment:</strong> For payments made after due date
                </div>
                <div class="mb-2">
                    <strong class="text-danger">Missed Payment:</strong> For completely missed payment installments
                </div>
                <div class="mb-2">
                    <strong class="text-secondary">Group Violation:</strong> For violations of group rules or agreements
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

    // Auto-format amount input
    document.getElementById('amount').addEventListener('input', function(e) {
        let value = e.target.value;
        if (value < 1000) {
            e.target.setCustomValidity('Minimum penalty amount is TZS 1,000');
        } else {
            e.target.setCustomValidity('');
        }
    });
</script>
@endpush
