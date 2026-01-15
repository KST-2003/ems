@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Add New Application</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('recruitments.index') }}">Recruitments</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Applicant Information</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('recruitments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="position_applied" class="form-label">Position Applied</label>
                                <input type="text" name="position_applied" class="form-control" value="{{ old('position_applied') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="nrc" class="form-label">NRC Number</label>
                                <input type="text" name="nrc" class="form-control" value="{{ old('nrc') }}" placeholder="e.g. 12/YAKANA(N)123456">
                            </div>
                            <div class="col-md-4">
                                <label for="blood_type" class="form-label">Blood Type</label>
                                <select name="blood_type" class="form-control">
                                    <option value="">-- Select --</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                    <option value="O">O</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nationality" class="form-label">Nationality</label>
                                <input type="text" name="nationality" class="form-control" value="{{ old('nationality', 'Burmese') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="religion" class="form-label">Religion</label>
                                <input type="text" name="religion" class="form-control" value="{{ old('religion') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="father_name" class="form-label">Father's Name</label>
                                <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="mother_name" class="form-label">Mother's Name</label>
                                <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="resume" class="form-label">Resume / CV (Photo Only) <span class="text-danger">*</span></label>
                            <input type="file" name="resume" class="form-control" accept="image/*" required onchange="previewImage(event)">
                            <small class="text-muted">Allowed formats: JPG, PNG, JPEG. No PDF allowed.</small>
                            
                            <div class="mt-3">
                                <img id="imagePreview" src="#" alt="Preview" style="display: none; max-width: 200px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('recruitments.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Application</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection