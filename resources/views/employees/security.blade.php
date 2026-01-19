@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Security & Role Management</h1>
</div>

<section class="section">
    <div class="row">
        {{-- Left Side: Role Definitions --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">System Roles</h5>
                    <div class="list-group">
                        @foreach($roles as $role => $desc)
                            <div class="list-group-item border-0 ps-0">
                                <h6 class="mb-1 fw-bold text-primary">{{ $role }}</h6>
                                <p class="mb-1 small text-muted">{{ $desc }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side: Staff Assignments --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Assign Access to Staff</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Access Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $emp)
                                <tr>
                                    <td>
                                        <strong>{{ $emp->name }}</strong><br>
                                        <small class="text-muted">{{ $emp->employee_id }}</small>
                                    </td>
                                    <td>{{ $emp->department }}</td>
                                    <td><span class="badge bg-light text-dark border">Staff</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#roleModal{{ $emp->id }}">
                                            Edit Role
                                        </button>
                                    </td>
                                </tr>

                                {{-- Role Update Modal --}}
                                <div class="modal fade" id="roleModal{{ $emp->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Role: {{ $emp->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('security.index') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="employee_id" value="{{ $emp->id }}">
                                                    <div class="mb-3">
                                                        <label class="form-label">Select Role</label>
                                                        <select name="role" class="form-select">
                                                            @foreach($roles as $roleName => $desc)
                                                                <option value="{{ $roleName }}">{{ $roleName }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-success w-100">Update Permissions</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection