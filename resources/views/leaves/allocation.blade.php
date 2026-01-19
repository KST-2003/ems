@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Leave Allocation ({{ now()->year }})</h1>
    <p class="text-muted">Define maximum allowed days for each employee per leave type.</p>
</div>

<section class="section">
    {{-- Step 1: Searchable Employee List --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body pt-3">
            <h5 class="card-title">1. Search & Select Employee</h5>
            <div class="table-responsive">
                <table id="employeeSearchTable" class="table table-hover align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                        <tr>
                            <td>{{ $emp->employee_id }}</td>
                            <td><strong>{{ $emp->name }}</strong></td>
                            <td>{{ $emp->department }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary" 
                                    onclick="setEmployee({{ $emp->id }}, '{{ $emp->name }} ({{ $emp->employee_id }})')">
                                    Select
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Step 2: Allocation Form --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">2. Set Entitlements for: <span id="selected-employee-name" class="text-primary underline">None Selected</span></h5>
            
            <form action="{{ route('leave-allocations.store') }}" method="POST">
                @csrf
                <input type="hidden" name="employee_id" id="employee_id_input" required>
                <input type="hidden" name="year" value="{{ now()->year }}">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 80px;">Enable</th>
                                <th>Leave Type</th>
                                <th style="width: 200px;">Max Days (Annual)</th>
                                <th>Policy Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveTypes as $type)
                            <tr>
                                <td class="text-center">
                                    {{-- The 'Enable' checkbox ensures we only update specific types --}}
                                    <input type="checkbox" name="allocations[{{ $type->id }}][enabled]" value="1" class="form-check-input role-check">
                                </td>
                                <td>
                                    <strong>{{ $type->name }}</strong>
                                </td>
                                <td>
                                    <input type="number" name="allocations[{{ $type->id }}][max_days]" 
                                           class="form-control" value="{{ $type->default_days }}" min="0">
                                </td>
                                <td class="bg-light small">
                                    @if($type->sandwich_rule) 
                                        <span class="badge bg-info"><i class="bi bi-calendar-check"></i> Sandwich Rule</span> 
                                    @else
                                        <span class="badge bg-secondary">Workdays Only</span>
                                    @endif
                                    @if($type->max_continuous_days) 
                                        <div class="text-muted mt-1">Limit: {{ $type->max_continuous_days }} continuous days.</div> 
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success btn-lg shadow">
                        <i class="bi bi-save"></i> Save Individual Allocation
                    </button>
                    <a href="{{ route('leaves.index') }}" class="btn btn-secondary btn-lg">Back</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
{{-- DataTables CSS/JS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize search table
    $('#employeeSearchTable').DataTable({
        "pageLength": 5,
        "lengthMenu": [5, 10, 25],
        "language": {
            "search": "Quick Search Staff:"
        }
    });
});

// Logic to move selected employee into the form
function setEmployee(id, name) {
    document.getElementById('employee_id_input').value = id;
    document.getElementById('selected-employee-name').innerText = name;
    
    // Smooth scroll to the form
    window.scrollTo({
        top: document.querySelector('form').offsetTop - 100,
        behavior: 'smooth'
    });
}
</script>

<style>
    .form-check-input { width: 1.5em; height: 1.5em; cursor: pointer; }
    .underline { text-decoration: underline; }
</style>
@endsection