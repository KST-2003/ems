@extends('layouts.app')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center no-print">
    <h1>BOD Monthly Summary: {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h1>
    <button onclick="window.print()" class="btn btn-secondary shadow-sm">
        <i class="bi bi-printer"></i> Print Report
    </button>
</div>

<div class="card mb-4 no-print">
    <div class="card-body pt-3">
        <form method="GET" action="{{ route('leaves.reports') }}" class="row g-3">
            <div class="col-md-4">
                <input type="month" name="month" class="form-control" value="{{ $month }}" onchange="this.form.submit()">
            </div>
            <div class="col-md-4">
                <select name="department" class="form-select" onchange="this.form.submit()">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Attendance Rollup</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th class="text-center">Present</th>
                        <th class="text-center">On-Duty</th>
                        <th class="text-center">Absent</th>
                        <th class="text-center">Rate (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        @php
                            $active = $report->total_present + $report->total_on_duty;
                            $rate = ($active / 22) * 100; // Simplified calculation
                        @endphp
                        <tr>
                            <td>{{ $report->employee->employee_id ?? 'N/A' }}</td>
                            <td>{{ $report->employee->name ?? 'Unknown' }}</td>
                            <td class="text-center text-success">{{ $report->total_present }}</td>
                            <td class="text-center text-info">{{ $report->total_on_duty }}</td>
                            <td class="text-center text-danger">{{ $report->total_absent_days }}</td>
                            <td class="text-center">{{ round($rate) }}%</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection