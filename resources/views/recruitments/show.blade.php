@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Applicant Details: {{ $recruitment->name }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('recruitments.index') }}">Recruitments</a></li>
            <li class="breadcrumb-item active">View Details</li>
        </ol>
    </nav>
</div>

<section class="section profile">
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <h5 class="card-title">Resume Photo</h5>
                    @if($recruitment->resume_file_path)
                        <img src="{{ asset('storage/' . $recruitment->resume_file_path) }}" alt="Resume" class="img-fluid rounded border">
                        <a href="{{ route('recruitments.download', $recruitment->id) }}" class="btn btn-secondary btn-sm mt-3">
                            <i class="bi bi-download"></i> Download Photo
                        </a>
                    @else
                        <p class="text-muted">No resume uploaded</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-body pt-3">
                    <div class="tab-content pt-2">
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-muted font-weight-bold">Status</div>
                            <div class="col-lg-9 col-md-8">
                                <span class="badge bg-{{ $recruitment->status == 'accepted' ? 'success' : ($recruitment->status == 'declined' ? 'danger' : 'warning') }}">
                                    {{ strtoupper($recruitment->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label">Position Applied</div>
                            <div class="col-lg-9 col-md-8">{{ $recruitment->position_applied ?? 'N/A' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label">NRC Number</div>
                            <div class="col-lg-9 col-md-8">{{ $recruitment->nrc }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label">Linked Employee</div>
                            <div class="col-lg-9 col-md-8">
                                {{ $recruitment->employee ? $recruitment->employee->name : 'Not Linked' }}
                            </div>
                        </div>

                        <hr>
                        <h6>Family & Personal Information</h6>
                        <div class="row mb-2">
                            <div class="col-md-6"><strong>Father's Name:</strong> {{ $recruitment->father_name }}</div>
                            <div class="col-md-6"><strong>Mother's Name:</strong> {{ $recruitment->mother_name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6"><strong>Religion:</strong> {{ $recruitment->religion }}</div>
                            <div class="col-md-6"><strong>Blood Type:</strong> {{ $recruitment->blood_type }}</div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('recruitments.edit', $recruitment->id) }}" class="btn btn-warning">Edit Information</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection