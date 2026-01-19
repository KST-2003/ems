@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Edit Leave Record</h1>
    </div>

    @if (!$leave)
        <div class="alert alert-danger">Leave record not found.</div>
        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Back</a>
    @else
        {{-- FIX: Use $leave to match the resource parameter 'leave' --}}
        <form action="{{ route('leaves.update', $leave->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Employee</label>
                <input type="text" class="form-control" value="{{ $leave->employee->name ?? 'Unknown' }}" disabled>
                <input type="hidden" name="employee_id" value="{{ $leave->employee_id }}">
            </div>

            <div class="mb-3">
                <label for="leave_type_id" class="form-label">Leave Type</label>
                <select name="leave_type_id" id="leave_type_id" class="form-control" required>
                    @foreach ($leaveTypes as $type)
                        <option value="{{ $type->id }}" {{ $leave->leave_type_id == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" 
                           value="{{ \Carbon\Carbon::parse($leave->start_date)->format('Y-m-d') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" 
                           value="{{ $leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->format('Y-m-d') : '' }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="reason" class="form-label">Remark</label>
                <textarea name="reason" id="reason" class="form-control">{{ $leave->reason }}</textarea>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="ongoing" {{ $leave->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="done" {{ $leave->status == 'done' ? 'selected' : '' }}>Done</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Record</button>
            <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Back</a>
        </form>
    @endif
@endsection