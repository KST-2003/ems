@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>{{ __('messages.leave_types') }}</h1>
        <nav>
            <ol class="breadcrumb">
                {{-- <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li> --}}
                <li class="breadcrumb-item active">{{ __('messages.leave_types') }}</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('messages.manage_leave_types') }}</h5>

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
                            {{ __('messages.add_new_leave_type') }}
                        </button>

                        <!-- Create Modal -->
                        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('leave-types.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createModalLabel">{{ __('messages.add_new_leave_type') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="default_days" class="form-label">{{ __('messages.default_days') }}</label>
                                                <input type="number" class="form-control" id="default_days" name="default_days" min="0">
                                            </div>
                                            <div class="mb-3">
                                                <label for="color" class="form-label">{{ __('messages.color') }}</label>
                                                <input type="color" class="form-control form-control-color" id="color" name="color" value="#3788d8">
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">{{ __('messages.description') }}</label>
                                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                                            <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <table class="table table-hover datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.name') }}</th>
                                    <th>{{ __('messages.default_days') }}</th>
                                    <th>{{ __('messages.color') }}</th>
                                    <th>{{ __('messages.description') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaveTypes as $type)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $type->name }}</td>
                                        <td>{{ $type->default_days ?? '-' }}</td>
                                        <td>
                                            <div style="width: 30px; height: 30px; background-color: {{ $type->color ?? '#ccc' }}; border: 1px solid #ddd; border-radius: 4px;"></div>
                                        </td>
                                        <td>{{ $type->description ?? '-' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#editModal"
                                                data-id="{{ $type->id }}"
                                                data-name="{{ $type->name }}"
                                                data-days="{{ $type->default_days }}"
                                                data-color="{{ $type->color ?? '#3788d8' }}"
                                                data-desc="{{ $type->description }}"
                                                data-url="{{ route('leave-types.update', $type->id) }}">
                                                {{ __('messages.edit') }}
                                            </button>

                                            <form action="{{ route('leave-types.destroy', $type->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('messages.delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">{{ __('messages.edit_leave_type') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">{{ __('messages.name') }}</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_days" class="form-label">{{ __('messages.default_days') }}</label>
                            <input type="number" class="form-control" id="edit_days" name="default_days" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="edit_color" class="form-label">{{ __('messages.color') }}</label>
                            <input type="color" class="form-control form-control-color" id="edit_color" name="color">
                        </div>
                        <div class="mb-3">
                            <label for="edit_desc" class="form-label">{{ __('messages.description') }}</label>
                            <textarea class="form-control" id="edit_desc" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editButtons = document.querySelectorAll('.edit-btn');
            
            editButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    document.getElementById('edit_name').value = this.getAttribute('data-name');
                    document.getElementById('edit_days').value = this.getAttribute('data-days');
                    document.getElementById('edit_color').value = this.getAttribute('data-color');
                    document.getElementById('edit_desc').value = this.getAttribute('data-desc');
                    
                    document.getElementById('editForm').setAttribute('action', this.getAttribute('data-url'));
                });
            });
        });
    </script>
@endsection