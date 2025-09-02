@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>{{ __('messages.employee_leaves') }} - {{ $employee->name }}</h1>
        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-primary float-end">
            {{ __('messages.view_employee') }}
        </a>
    </div>

    <div class="mb-3">
        <label for="leave_type">{{ __('messages.leave_type') }}</label>
        <select id="leave_type" class="form-control">
            <option value="">{{ __('messages.select_leaves-type') }}</option>
            @foreach ($leaveTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>

    <table class="table table-bordered" id="employee-leaves-table">
        <thead>
            <tr>
                <th>{{ __('messages.number') }}</th>
                <th>{{ __('messages.description') }}</th>
                <th>{{ __('messages.start_date') }} (D-M-Y)</th>
                <th>{{ __('messages.end_date') }} (D-M-Y)</th>
                <th>{{ __('messages.duration') }}</th>
                <th>{{ __('messages.remark') }}</th>
                <th>{{ __('messages.status') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div class="mt-3">
        <a href="{{ route('export.excel', $employee->id) }}" class="btn btn-success">
            {{ __('messages.export_excel') }}
        </a>
        <a href="{{ route('export.pdf', $employee->id) }}" class="btn btn-danger">
            {{ __('messages.export_pdf') }}
        </a>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#employee-leaves-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('leaves.filter', $employee->id) }}',
                    data: function(d) {
                        d.leave_type_id = $('#leave_type').val();
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'leaveType.name',
                        name: 'leaveType.name',
                        render: data => data ?? '-'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        searchable: true
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                        searchable: true
                    },
                    {
                        data: 'duration',
                        name: 'duration',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'reason',
                        name: 'reason',
                        render: data => data ?? '-',
                        searchable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#leave_type').change(function() {
                table.ajax.reload();
            });

            $('#employee-leaves-table').on('click', '.delete-leave', function() {
                const id = $(this).data('id');
                if (confirm('Delete this leave?')) {
                    $.ajax({
                        url: '{{ url("leaves") }}/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload();
                                alert('Leave deleted successfully');
                            } else {
                                alert('Delete failed: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('Delete failed: ' + xhr.responseJSON?.message || 'Unknown error');
                        }
                    });
                }
            });
        });
    </script>
@endsection
