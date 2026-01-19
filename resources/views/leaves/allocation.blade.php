@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Leave Allocation ({{ now()->year }})</h1>
    <p class="text-muted">Manually define maximum allowed days for each employee per leave type.</p>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Set Annual Entitlements</h5>
            <form action="{{ route('leave-allocations.store') }}" method="POST">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Select Employee</label>
                        <select name="employee_id" class="form-select select2" required>
                            <option value="">-- Search Employee --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_id }}) - {{ $emp->department }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Year</label>
                        <input type="number" name="year" class="form-control" value="2026" readonly>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">Enable</th>
                                <th>Leave Type</th>
                                <th>Max Days Allowed (Annual)</th>
                                <th>Policy Preview</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveTypes as $type)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="allocations[{{ $type->id }}][enabled]" value="1" class="form-check-input">
                                </td>
                                <td>
                                    <strong>{{ $type->name }}</strong>
                                </td>
                                <td>
                                    <input type="number" name="allocations[{{ $type->id }}][max_days]" 
                                           class="form-control" value="{{ $type->default_days }}" min="0">
                                </td>
                                <td>
                                    @if($type->sandwich_rule) <span class="badge bg-info">Sandwich Rule Active</span> @endif
                                    @if($type->max_continuous_days) <small class="text-muted">Limit: {{ $type->max_continuous_days }} days/stretch</small> @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save Individual Allocation</button>
                    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection