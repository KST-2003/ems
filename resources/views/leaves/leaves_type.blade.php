@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Leave Policies</h1>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Create Policy Type</h5>
                <form action="{{ route('leave-types.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Medical Leave">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Yearly Default (Days)</label>
                        <input type="number" name="default_days" class="form-control" value="10">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Continuous Days</label>
                        <input type="number" name="max_continuous_days" class="form-control" placeholder="Optional">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Calendar Highlight Color</label>
                        <input type="color" name="color" class="form-control" value="#3788d8">
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="sandwich_rule" value="1" class="form-check-input" id="sandwich">
                        <label class="form-check-label" for="sandwich">Apply Sandwich Rule (Include Holidays)</label>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Save Policy</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Existing Policies</h5>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Color</th>
                            <th>Name</th>
                            <th>Default</th>
                            <th>Rules</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaveTypes as $type)
                        <tr>
                            <td><div style="width:25px; height:25px; background:{{ $type->color }}; border-radius:4px;"></div></td>
                            <td>{{ $type->name }}</td>
                            <td>{{ $type->default_days }}</td>
                            <td>
                                @if($type->sandwich_rule) <span class="badge bg-info">Sandwich</span> @endif
                                @if($type->max_continuous_days) <span class="badge bg-secondary">Max {{ $type->max_continuous_days }}</span> @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary edit-type-btn" 
                                    data-bs-toggle="modal" data-bs-target="#editTypeModal"
                                    data-id="{{ $type->id }}"
                                    data-name="{{ $type->name }}"
                                    data-days="{{ $type->default_days }}"
                                    data-continuous="{{ $type->max_continuous_days }}"
                                    data-color="{{ $type->color }}"
                                    data-sandwich="{{ $type->sandwich_rule ? '1' : '0' }}">
                                    Edit
                                </button>
                                <form action="{{ route('leave-types.destroy', $type->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
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

<div class="modal fade" id="editTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editTypeForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Leave Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Yearly Default (Days)</label>
                        <input type="number" name="default_days" id="edit_days" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Continuous Days</label>
                        <input type="number" name="max_continuous_days" id="edit_continuous" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Calendar Highlight Color</label>
                        <input type="color" name="color" id="edit_color" class="form-control">
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="sandwich_rule" id="edit_sandwich" value="1" class="form-check-input">
                        <label class="form-check-label">Apply Sandwich Rule</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Policy</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.edit-type-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('edit_name').value = this.dataset.name;
        document.getElementById('edit_days').value = this.dataset.days;
        document.getElementById('edit_continuous').value = this.dataset.continuous;
        document.getElementById('edit_color').value = this.dataset.color;
        document.getElementById('edit_sandwich').checked = this.dataset.sandwich === '1';
        document.getElementById('editTypeForm').action = `/leave-types/${this.dataset.id}`;
    });
});
</script>
@endsection