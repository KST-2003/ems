@extends('layouts.app')
@section('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="pagetitle">
        <h1>{{ __('messages.add_leave') }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">{{ __('messages.employee_leaves') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.add_leave') }}</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('messages.add_leave') }}</h5>
                        <form action="{{ route('leaves.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="employee_id" class="col-sm-2 col-form-label">{{ __('messages.employee') }}</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="employee_id" name="employee_id" required>
                                        <option value="">{{ __('messages.select_employee') }}</option>
                                        @foreach (\App\Models\Employee::all() as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }} - ID :  {{ $employee->employee_id}} </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="leave_type_id" class="col-sm-2 col-form-label">{{ __('messages.leave_type') }}</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="leave_type_id" name="leave_type_id" required>
                                        <option value="">{{ __('messages.select_leave_type') }}</option>
                                        @foreach (\App\Models\LeaveType::all() as $leaveType)
                                            <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('leave_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="start_date" class="col-sm-2 col-form-label">{{ __('messages.start_date') }}</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                    @error('start_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="end_date" class="col-sm-2 col-form-label">{{ __('messages.end_date') }}</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                    @error('end_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="reason" class="col-sm-2 col-form-label">{{ __('messages.reason') }}</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="reason" name="reason" rows="4"></textarea>
                                    @error('reason')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">{{ __('messages.status') }}</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="Pending">{{ __('messages.pending') }}</option>
                                        <option value="Approved">{{ __('messages.approved') }}</option>
                                        <option value="Rejected">{{ __('messages.rejected') }}</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">{{ __('messages.submit') }}</button>
                                    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection