@extends('layouts.app')
@section('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .profile-image {
            max-width: 150px;
            height: auto;
        }
        .attachment-link {
            margin-top: 10px;
            display: inline-block;
            margin-right: 10px;
        }
        .view-icon {
            cursor: pointer;
            color: #007bff;
            margin-right: 10px;
        }
        .modal-image {
            max-width: 100%;
            height: auto;
        }
    </style>
@endsection
@section('content')
    <div class="content-with-sidebar">
        <div class="pagetitle">
            <h1>{{ __('messages.employee_details') }}</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">{{ __('messages.employees') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.employee_details') }}</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $employee->name }}</h5>
                            <div class="row">
                                <div class="col-md-8">
                                    <dl class="row">
                                        <dt class="col-sm-4">{{ __('messages.employee_id') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->employee_id }}</dd>
                                        <dt class="col-sm-4">{{ __('messages.dob') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->dob ? \Carbon\Carbon::parse($employee->dob)->format('Y-m-d') : '-' }}</dd>
                                        <dt class="col-sm-4">{{ __('messages.nationality') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->nationality ?? '-' }}</dd>
                                        <dt class="col-sm-4">{{ __('messages.father_name') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->father_name ?? '-' }}</dd>
                                        <dt class="col-sm-4">{{ __('messages.mother_name') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->mother_name ?? '-' }}</dd>
                                        <dt class="col-sm-4">{{ __('messages.nrc') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->nrc ?? '-' }}</dd>
                                        <dt class="col-sm-4">{{ __('messages.spouse_name') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->spouse_name ?? '-' }}</dd>
                                        <dt class="col-sm-4">{{ __('messages.children_names') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->children_names ?? '-' }}</dd>
                                        <dt class="col-sm-4">{{ __('messages.address') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->address ?? '-' }}</dd>
                                        <dt class="col-sm-4">{{ __('messages.education') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->education ?? '-' }}</dd>
                                        <dt class="col-sm-4">{{ __('messages.current_position') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->current_position ?? '-' }}</dd>
                                        <dt class="col-sm-4">{{ __('messages.salary') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->salary ?? '-' }}</dd>
                                        <dt class="col-sm-4">{{ __('messages.department') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->department ?? '-' }}</dd>
                                        <dt class="col-sm-4">{{ __('messages.blood_type') }}</dt>
                                        <dd class="col-sm-8">{{ $employee->blood_type ?? '-' }}</dd>
                                    </dl>
                                    <h6><strong>{{ __('messages.experience') }}</strong></h6>
                                    @if ($employee->experiences->isEmpty())
                                        <p>{{ __('messages.no_experience') }}</p>
                                    @else
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('messages.position') }}</th>
                                                    <th>{{ __('messages.department') }}</th>
                                                    <th>{{ __('messages.from_date') }}</th>
                                                    <th>{{ __('messages.to_date') }}</th>
                                                    <th>{{ __('messages.location') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employee->experiences as $experience)
                                                    <tr>
                                                        <td>{{ $experience->position }}</td>
                                                        <td>{{ $experience->department }}</td>
                                                        <td>{{ $experience->from_date ? \Carbon\Carbon::parse($experience->from_date)->format('Y-m-d') : '-' }}</td>
                                                        <td>{{ $experience->to_date ? \Carbon\Carbon::parse($experience->to_date)->format('Y-m-d') : ($experience->is_current ? 'Current' : '-') }}</td>
                                                        <td>{{ $experience->location }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                    <h6><strong>{{ __('messages.certificates') }}</strong></h6>
                                    {{-- <p>Debug: Employee Name: {{ $employee->name }} | Processed Name: {{ preg_replace('/[\s\/\\\?*:|"<>]+/', '_', $employee->name) }}</p> --}}
                                    @if ($employee->certificates->isEmpty())
                                        <p>{{ __('messages.no_certificates') }}</p>
                                    @else
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('messages.certificate_name') }}</th>
                                                    <th>{{ __('messages.issue_date') }}</th>
                                                    <th>{{ __('messages.issuer') }}</th>
                                                    <th>{{ __('messages.description') }}</th>
                                                    <th>{{ __('messages.attachment') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employee->certificates as $certificate)
                                                    <tr>
                                                        <td>{{ $certificate->certificate_name }}</td>
                                                        <td>{{ $certificate->issue_date ? \Carbon\Carbon::parse($certificate->issue_date)->format('Y-m-d') : '-' }}</td>
                                                        <td>{{ $certificate->issuer ?? '-' }}</td>
                                                        <td>{{ $certificate->description ?? '-' }}</td>
                                                        <td>
                                                            @if ($certificate->file_url)
                                                                
                                                                @php
                                                                    $safeEmployeeName = preg_replace('/[\s\/\\\?*:|"<>]+/', '_', $employee->name);
                                                                    $safeCertificateName = preg_replace('/[\s\/\\\?*:|"<>]+/', '_', $certificate->certificate_name);
                                                                    $downloadFilename = $safeEmployeeName . '_' . $safeCertificateName . '.' . pathinfo($certificate->file_path, PATHINFO_EXTENSION);
                                                                @endphp
                                                                <a href="{{ $certificate->file_url }}" class="attachment-link" download="{{ $downloadFilename }}">{{ __('messages.download') }}</a>
                                                                @if (in_array(pathinfo($certificate->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                                    <i class="bi bi-eye view-icon" data-bs-toggle="modal" data-bs-target="#imageModal" data-image="{{ $certificate->file_url }}" title="{{ __('messages.view_image') }}"></i>
                                                                @endif
                                                            @else
                                                                {{ __('messages.no_attachment') }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                    <h6><strong>{{ __('messages.criminal_records') }}</strong></h6>
                                    @if ($employee->criminalRecords->isEmpty())
                                        <p>{{ __('messages.no_criminal_records') }}</p>
                                    @else
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('messages.description') }}</th>
                                                    <th>{{ __('messages.attachment') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employee->criminalRecords as $record)
                                                    <tr>
                                                        <td>{{ $record->description ?? '-' }}</td>
                                                        <td>
                                                            @if ($record->file_url)
                                                                @if (in_array(pathinfo($record->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                                    <i class="bi bi-eye view-icon" data-bs-toggle="modal" data-bs-target="#imageModal" data-image="{{ $record->file_url }}" title="{{ __('messages.view_image') }}"></i>
                                                                @endif
                                                                @php
                                                                    $safeEmployeeName = preg_replace('/[\s\/\\\?*:|"<>]+/', '_', $employee->name);
                                                                    $downloadFilename = $safeEmployeeName . '_criminal_record_' . $record->id . '.' . pathinfo($record->file_path, PATHINFO_EXTENSION);
                                                                @endphp
                                                                <a href="{{ $record->file_url }}" class="attachment-link" download="{{ $downloadFilename }}">{{ __('messages.download') }}</a>
                                                            @else
                                                                {{ __('messages.no_attachment') }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                                <div class="col-md-4 text-center">
                                    <img src="{{ $employee->profile_image_url }}" alt="Profile" class="profile-image">
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning">{{ __('messages.edit') }}</a>
                                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('messages.confirm_delete') }}')">{{ __('messages.delete') }}</button>
                                </form>
                                <a href="{{ route('employees.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Image Preview Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">{{ __('messages.image_preview') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="" alt="Image Preview" class="modal-image" id="modalImage">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewIcons = document.querySelectorAll('.view-icon');
            const modalImage = document.getElementById('modalImage');

            viewIcons.forEach(icon => {
                icon.addEventListener('click', function() {
                    const imageUrl = this.getAttribute('data-image');
                    modalImage.src = imageUrl;
                });
            });
        });
    </script>
@endsection