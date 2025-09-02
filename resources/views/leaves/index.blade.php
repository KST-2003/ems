@extends('layouts.app')

@section('css')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="pagetitle">
    <div class="row mb-3">
        <div class="col-8">
            <h1>{{ __('messages.employee_leaves') }}</h1>
        </div>
        <div class="col-4 text-end">
            <a href="{{ route('leaves.create') }}" class="btn btn-primary">{{ __('messages.add_leave') }}</a>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-3">
            <select id="filter-department" class="form-select">
                <option value="">{{ __('messages.all_departments') }}</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}">{{ $dept }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select id="filter-leave-type" class="form-select">
                <option value="">{{ __('messages.all_leave_types') }}</option>
                @foreach($leaveTypes as $type)
                    <option value="{{ $type->name }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered" id="leaves-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.number') }}</th>
                                <th>{{ __('messages.name') }}</th>
                                <th>{{ __('messages.department') }}</th>
                                <th>{{ __('messages.phone') }}</th>
                                <th>{{ __('messages.number_of_leaves') }}</th>
                                <th>{{ __('messages.leave_types') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('js/app.js') }}?v={{ time() }}"></script>
<script>
$(document).ready(function() {
    var table = $('#leaves-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("leaves.list") }}',
            type: 'GET',
            data: function(d) {
                d.department = $('#filter-department').val();
                d.leave_type = $('#filter-leave-type').val();
            },
            error: function(xhr) {
                console.error('DataTables AJAX error:', xhr.responseText);
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'department', name: 'department' },
            { data: 'phone', name: 'phone' },
            { data: 'number_of_leaves', name: 'number_of_leaves', searchable: false },
            { data: 'leave_types', name: 'leave_types', searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // Filter dropdowns
    $('#filter-department, #filter-leave-type').change(function() {
        table.ajax.reload();
    });
});
</script>
@endsection
