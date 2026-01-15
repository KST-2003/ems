@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>{{ __('messages.edit_leave') }}</h1>
    </div>

    @if (!$leave)
        <div class="alert alert-danger">
            Leave record not found.
        </div>
        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
    @else
        <form action="{{ route('leaves.update', $leave->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Employee - Show warning if missing -->
            <div class="mb-3">
                <label for="employee_id" class="form-label">{{ __('messages.employee') }}</label>
                @if ($leave->employee)
                    <input type="text" class="form-control" value="{{ $leave->employee->name }}" disabled>
                @else
                    <input type="text" class="form-control is-invalid" value="Employee not found (ID: {{ $leave->employee_id }})" disabled>
                    <div class="invalid-feedback">
                        The associated employee (ID {{ $leave->employee_id }}) does not exist or is deleted.
                    </div>
                @endif
                <input type="hidden" name="employee_id" value="{{ $leave->employee_id }}">
                @error('employee_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="leave_type_id" class="form-label">{{ __('messages.leave_type') }}</label>
                <select name="leave_type_id" id="leave_type_id" class="form-control" required>
                    @foreach ($leaveTypes as $type)
                        <option value="{{ $type->id }}" {{ $leave->leave_type_id == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('leave_type_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

           <input type="date" name="start_date" id="start_date" class="form-control" 
       value="{{ $leave->start_date ? (\Carbon\Carbon::parse($leave->start_date)->format('Y-m-d')) : '' }}" required>

<input type="date" name="end_date" id="end_date" class="form-control" 
       value="{{ $leave->end_date ? (\Carbon\Carbon::parse($leave->end_date)->format('Y-m-d')) : '' }}">

            <div class="mb-3">
                <label for="reason" class="form-label">{{ __('messages.remark') }}</label>
                <textarea name="reason" id="reason" class="form-control">{{ $leave->reason ?? '' }}</textarea>
                @error('reason')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">{{ __('messages.status') }}</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="ongoing" {{ $leave->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="done" {{ $leave->status == 'done' ? 'selected' : '' }}>Done</option>
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
            <a href="{{ route('leaves.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </form>
    @endif
@endsection