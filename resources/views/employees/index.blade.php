@extends('layouts.app')

@section('css')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="pagetitle">
    <div class="row">
        <div class="col-8">
            <h1>{{ __('messages.employees') }}</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">{{ __('messages.employees') }}</li>
                </ol>
            </nav>
        </div>
        <div class="col-4">
            <a href="{{ route('employees.create') }}" style="float: right" class="btn btn-primary">{{ __('messages.add_employee') }}</a>
        </div>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('messages.employees') }}</h5>
                    <table class="table" id="employees-table">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('messages.number') }}</th>
                                <th scope="col">{{ __('messages.employee_id') }}</th>
                                <th scope="col">{{ __('messages.name') }}</th>
                                <th scope="col">{{ __('messages.phone') }}</th>
                                <th scope="col">{{ __('messages.department') }}</th>
                                <th scope="col">{{ __('messages.actions') }}</th>
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
$(document).ready(function () {
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('DataTables is not loaded');
    } else {
        console.log("Initializing DataTable...");
        $('#employees-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('employees.list') }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                
                error: function (xhr, error, thrown) {
                    console.log('DataTables AJAX error:', xhr, error, thrown);
                }
            },
            columns: [
                { data: '#', name: '#' },
                { data: 'employee_id', name: 'employee_id' },
                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'department', name: 'department' },
                {
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        return `
                            <a href="${'{{ route("employees.show", ":id") }}'.replace(':id', data)}" class="btn btn-sm btn-info">{{ __('messages.view') }}</a>
                            <a href="${'{{ route("employees.edit", ":id") }}'.replace(':id', data)}" class="btn btn-sm btn-warning">{{ __('messages.edit') }}</a>
                            <form action="${'{{ route("employees.destroy", ":id") }}'.replace(':id', data)}" method="POST" style="display:inline;" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.delete') }}</button>
                            </form>
                        `;
                    }
                }
            ]
        });
    }
});
</script>
@endsection