@extends('layouts.app')

@section('css')
    <style>
        .resume-thumb {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .gap-1 {
            gap: 0.25rem;
        }

        /* Custom padding and sizing for the tick/cross buttons */
        .btn-tick {
            padding: 0.25rem 0.6rem;
            line-height: 1.5;
        }

        .btn-cross {
            padding: 0.25rem 0.6rem;
            line-height: 1.5;
        }
    </style>
@endsection

@section('content')
    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Recruitments</h1>
            </div>
            <div class="col-4 text-end">
                <a href="{{ route('recruitments.create') }}" class="btn btn-primary">Add Application</a>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body pt-3">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select id="filter-status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="declined">Declined</option>
                            <option value="accepted">Accepted</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Position</label>
                        <input type="text" id="filter-position" class="form-control" placeholder="Search by position...">
                    </div>
                </div>

                <table class="table" id="recruitments-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Resume</th>
                            <th>Linked Employee</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    <div class="modal fade" id="acceptModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="acceptForm" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="accepted">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Accept & Link Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Link to Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employeeSelect" class="form-select" required>
                                <option value="">-- Select Employee --</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success w-100">Confirm Acceptance</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form id="rejectForm" method="POST" style="display:none;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="declined">
        <input type="hidden" name="employee_id" value="">
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#recruitments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('recruitments.list') }}',
                    data: function(d) {
                        d.status = $('#filter-status').val();
                        d.position = $('#filter-position').val();
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'position_applied',
                        name: 'position_applied'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'resume_file_path',
                        render: function(data) {
                            return data ? `<img src="/storage/${data}" class="resume-thumb">` :
                                'No Image';
                        }
                    },
                    {
                        data: 'employee.name',
                        name: 'employee.name',
                        defaultContent: '<i>Not Linked</i>'
                    },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let empId = row.employee_id ? row.employee_id : '';

                            // Ensure case-insensitivity for the check
                            let currentStatus = row.status ? row.status.toLowerCase() : '';
                            let actionButtons = '';

                            if (currentStatus === 'pending') {
                                actionButtons = `
                <button type="button" onclick="openAcceptModal(${data}, '${empId}')" 
                    class="btn btn-sm btn-success btn-tick" title="Accept">
                    ✓
                </button>
                <button type="button" onclick="confirmReject(${data})" 
                    class="btn btn-sm btn-danger btn-cross" title="Reject">
                    ✕
                </button>
            `;
                            }

                            return `
            <div class="d-flex gap-1 align-items-center">
                <a href="/recruitments/${data}" class="btn btn-sm btn-info text-white">View</a>
                ${actionButtons}
                <form action="/recruitments/${data}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this record?')">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Del</button>
                </form>
            </div>
        `;
                        }
                    }
                ]
            });

            $('#filter-status').on('change', function() {
                table.draw();
            });
            $('#filter-position').on('keyup', function() {
                table.draw();
            });
        });

        function openAcceptModal(id, empId) {
            let url = '{{ route('recruitments.updateStatus', ':id') }}'.replace(':id', id);
            $('#acceptForm').attr('action', url);
            $('#employeeSelect').val(empId);
            $('#acceptModal').modal('show');
        }

        function confirmReject(id) {
            if (confirm('Move this application to Declined?')) {
                let url = '{{ route('recruitments.updateStatus', ':id') }}'.replace(':id', id);
                let form = $('#rejectForm');
                form.attr('action', url);
                form.submit();
            }
        }
    </script>
@endsection
