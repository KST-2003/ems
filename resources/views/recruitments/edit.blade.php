@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Edit Applicant: {{ $recruitment->name }}</h5>

        <form action="{{ route('recruitments.update', $recruitment->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $recruitment->name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>NRC Number</label>
                    <input type="text" name="nrc" class="form-control" value="{{ $recruitment->nrc }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Religion</label>
                    <input type="text" name="religion" class="form-control" value="{{ $recruitment->religion }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Nationality</label>
                    <input type="text" name="nationality" class="form-control" value="{{ $recruitment->nationality }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Blood Type</label>
                    <input type="text" name="blood_type" class="form-control" value="{{ $recruitment->blood_type }}">
                </div>
            </div>

            <div class="mb-3">
                <label>Replace Resume (Photo only - jpeg, png, jpg)</label>
                <input type="file" name="resume" class="form-control" accept="image/*">
                <small class="text-muted">Leave empty to keep current photo.</small>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Update Application</button>
                <a href="{{ route('recruitments.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection