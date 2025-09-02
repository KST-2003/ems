@extends('layouts.app')
@section('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .form-step { display: none; }
        .form-step.active { display: block; }
        .error { color: red; }
    </style>
@endsection
@section('content')
    <div class="pagetitle">
        <h1>{{ __('messages.create_employee') }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">{{ __('messages.employees') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.create') }}</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('messages.create_employee') }}</h5>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('employees.store') }}" method="POST" id="employee-form" enctype="multipart/form-data">
                            @csrf
                            <!-- Step 1: Personal Information -->
                            <div class="form-step active" id="step-1">
                                <h6>{{ __('messages.personal_information') }}</h6>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.employee_id') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ old('employee_id') }}" required>
                                        @error('employee_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.email') }}</label>
                                    <div class="col-sm-9">
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.phone') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.gender') }}</label>
                                    <div class="col-sm-9">
                                        <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                            <option value="" {{ old('gender') == '' ? 'selected' : '' }}>{{ __('messages.select_gender') }}</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.profile_image') }}</label>
                                    <div class="col-sm-9">
                                        <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" accept="image/*">
                                        @error('profile_image')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.dob') }}</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob') }}">
                                        @error('dob')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.nationality') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nationality" class="form-control @error('nationality') is-invalid @enderror" value="{{ old('nationality') }}">
                                        @error('nationality')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.father_name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="father_name" class="form-control @error('father_name') is-invalid @enderror" value="{{ old('father_name') }}">
                                        @error('father_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.mother_name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="mother_name" class="form-control @error('mother_name') is-invalid @enderror" value="{{ old('mother_name') }}">
                                        @error('mother_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.nrc') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nrc" class="form-control @error('nrc') is-invalid @enderror" value="{{ old('nrc') }}">
                                        @error('nrc')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.spouse_name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="spouse_name" class="form-control @error('spouse_name') is-invalid @enderror" value="{{ old('spouse_name') }}">
                                        @error('spouse_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.children_names') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="children_names" class="form-control @error('children_names') is-invalid @enderror" value="{{ old('children_names') }}">
                                        @error('children_names')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.address') }}</label>
                                    <div class="col-sm-9">
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-primary next-step">{{ __('messages.next') }}</button>
                                </div>
                            </div>
                            <!-- Step 2: Professional Details -->
                            <div class="form-step" id="step-2">
                                <h6>{{ __('messages.professional_details') }}</h6>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.education') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="education" class="form-control @error('education') is-invalid @enderror" value="{{ old('education') }}">
                                        @error('education')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.current_position') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="current_position" class="form-control @error('current_position') is-invalid @enderror" value="{{ old('current_position') }}">
                                        @error('current_position')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.salary') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="salary" class="form-control @error('salary') is-invalid @enderror" value="{{ old('salary') }}">
                                        @error('salary')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.department') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department') }}">
                                        @error('department')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.blood_type') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="blood_type" class="form-control @error('blood_type') is-invalid @enderror" value="{{ old('blood_type') }}">
                                        @error('blood_type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-step">{{ __('messages.back') }}</button>
                                    <button type="button" class="btn btn-primary next-step">{{ __('messages.next') }}</button>
                                </div>
                            </div>
                            <!-- Step 3: Experience, Certificates, Criminal Records -->
                            <div class="form-step" id="step-3">
                                <h6>{{ __('messages.experience') }}</h6>
                                <div id="experiences"></div>
                                <button type="button" class="btn btn-secondary mb-3" onclick="addExperience()">{{ __('messages.add_experience') }}</button>
                                <h6>{{ __('messages.certificates') }}</h6>
                                <div id="certificates"></div>
                                <button type="button" class="btn btn-secondary mb-3" onclick="addCertificate()">{{ __('messages.add_certificate') }}</button>
                                <h6>{{ __('messages.criminal_records') }}</h6>
                                <div id="criminal_records"></div>
                                <button type="button" class="btn btn-secondary mb-3" onclick="addCriminalRecord()">{{ __('messages.add_criminal_record') }}</button>
                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-step">{{ __('messages.back') }}</button>
                                    <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        let experienceIndex = 0;
        function addExperience() {
            const entry = `
                <div class="experience-entry">
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">{{ __('messages.position') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="experiences[${experienceIndex}][position]" class="form-control">
                            <span class="invalid-feedback">{{ __('messages.experience_position_required') }}</span>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">{{ __('messages.department') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="experiences[${experienceIndex}][department]" class="form-control">
                            <span class="invalid-feedback">{{ __('messages.experience_department_required') }}</span>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">{{ __('messages.from_date') }}</label>
                        <div class="col-sm-9">
                            <input type="date" name="experiences[${experienceIndex}][from_date]" class="form-control">
                            <span class="invalid-feedback">{{ __('messages.experience_from_date_required') }}</span>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">{{ __('messages.to_date') }}</label>
                        <div class="col-sm-9">
                            <input type="date" name="experiences[${experienceIndex}][to_date]" class="form-control to-date">
                            <label><input type="checkbox" class="is-current" name="experiences[${experienceIndex}][is_current]" value="1"> {{ __('messages.currently') }}</label>
                            <small class="form-text text-muted">{{ __('messages.uncheck_currently_to_quit') }}</small>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">{{ __('messages.location') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="experiences[${experienceIndex}][location]" class="form-control">
                            <span class="invalid-feedback">{{ __('messages.experience_location_required') }}</span>
                        </div>
                    </div>
                </div>`;
            document.getElementById('experiences').insertAdjacentHTML('beforeend', entry);
            experienceIndex++;
            bindDeleteButtons();
            bindCurrentCheckboxes();
        }

        let certificateIndex = 0;
        function addCertificate() {
            const entry = `
                <div class="certificate-entry">
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">{{ __('messages.certificate_name') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="certificates[${certificateIndex}][certificate_name]" class="form-control">
                            <span class="invalid-feedback">{{ __('messages.certificate_name_required') }}</span>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">{{ __('messages.issue_date') }}</label>
                        <div class="col-sm-9">
                            <input type="date" name="certificates[${certificateIndex}][issue_date]" class="form-control">
                            <span class="invalid-feedback">{{ __('messages.certificate_issue_date_required') }}</span>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">{{ __('messages.issuer') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="certificates[${certificateIndex}][issuer]" class="form-control">
                            <span class="invalid-feedback">{{ __('messages.certificate_issuer_required') }}</span>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">{{ __('messages.description') }}</label>
                        <div class="col-sm-9">
                            <textarea name="certificates[${certificateIndex}][description]" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">{{ __('messages.attachment') }}</label>
                        <div class="col-sm-9">
                            <input type="file" name="certificates[${certificateIndex}][file]" class="form-control" accept="image/*,application/pdf">
                            @error('certificates.*.file')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>`;
            document.getElementById('certificates').insertAdjacentHTML('beforeend', entry);
            certificateIndex++;
            bindDeleteButtons();
        }

        let criminalRecordIndex = 0;
        function addCriminalRecord() {
            const entry = `
                <div class="criminal-record-entry">
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">{{ __('messages.description') }}</label>
                        <div class="col-sm-8">
                            <textarea name="criminal_records[${criminalRecordIndex}][description]" class="form-control"></textarea>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">{{ __('messages.attachment') }}</label>
                        <div class="col-sm-9">
                            <input type="file" name="criminal_records[${criminalRecordIndex}][file]" class="form-control" accept="image/*,application/pdf">
                            @error('criminal_records.*.file')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>`;
            document.getElementById('criminal_records').insertAdjacentHTML('beforeend', entry);
            criminalRecordIndex++;
            bindDeleteButtons();
        }

        function bindDeleteButtons() {
            document.querySelectorAll('.delete-entry').forEach(button => {
                button.addEventListener('click', function () {
                    this.closest('.experience-entry, .certificate-entry, .criminal-record-entry').remove();
                });
            });
        }

        function bindCurrentCheckboxes() {
            document.querySelectorAll('.is-current').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const toDateInput = this.closest('.form-group').querySelector('.to-date');
                    toDateInput.disabled = this.checked;
                    if (this.checked) toDateInput.value = '';
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const steps = document.querySelectorAll('.form-step');
            const nextButtons = document.querySelectorAll('.next-step');
            const prevButtons = document.querySelectorAll('.prev-step');
            let currentStep = 0;

            function showStep(stepIndex) {
                steps.forEach((step, index) => {
                    step.classList.toggle('active', index === stepIndex);
                });
            }

            nextButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const currentInputs = steps[currentStep].querySelectorAll('input[required], select[required]');
                    let valid = true;
                    currentInputs.forEach(input => {
                        if (!input.value) {
                            input.classList.add('is-invalid');
                            valid = false;
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    });

                    if (valid) {
                        currentStep++;
                        if (currentStep < steps.length) {
                            showStep(currentStep);
                        }
                    } else {
                        alert('{{ __('messages.fill_required_fields') }}');
                    }
                });
            });

            prevButtons.forEach(button => {
                button.addEventListener('click', function () {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

            document.getElementById('employee-form').addEventListener('submit', function (event) {
                const experienceEntries = document.querySelectorAll('.experience-entry');
                const certificateEntries = document.querySelectorAll('.certificate-entry');
                const criminalRecordEntries = document.querySelectorAll('.criminal-record-entry');
                let hasErrors = false;

                experienceEntries.forEach((entry, index) => {
                    const position = entry.querySelector(`input[name="experiences[${index}][position]"]`);
                    const department = entry.querySelector(`input[name="experiences[${index}][department]"]`);
                    const fromDate = entry.querySelector(`input[name="experiences[${index}][from_date]"]`);
                    const toDate = entry.querySelector(`input[name="experiences[${index}][to_date]"]`);
                    const isCurrent = entry.querySelector(`input[name="experiences[${index}][is_current]"]`);
                    const location = entry.querySelector(`input[name="experiences[${index}][location]"]`);

                    if (!position.value && !department.value && !fromDate.value && !toDate.value && !isCurrent.checked && !location.value) {
                        entry.remove();
                    } else {
                        if (!position.value) {
                            position.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            position.classList.remove('is-invalid');
                        }
                        if (!department.value) {
                            department.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            department.classList.remove('is-invalid');
                        }
                        if (!fromDate.value) {
                            fromDate.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            fromDate.classList.remove('is-invalid');
                        }
                        if (!isCurrent.checked && !toDate.value) {
                            toDate.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            toDate.classList.remove('is-invalid');
                        }
                        if (!location.value) {
                            location.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            location.classList.remove('is-invalid');
                        }
                    }
                });

                certificateEntries.forEach((entry, index) => {
                    const certificateName = entry.querySelector(`input[name="certificates[${index}][certificate_name]"]`);
                    const issueDate = entry.querySelector(`input[name="certificates[${index}][issue_date]"]`);
                    const issuer = entry.querySelector(`input[name="certificates[${index}][issuer]"]`);
                    const description = entry.querySelector(`textarea[name="certificates[${index}][description]"]`);
                    const file = entry.querySelector(`input[name="certificates[${index}][file]"]`);

                    if (!certificateName.value && !issueDate.value && !issuer.value && !description.value && !file.files.length) {
                        entry.remove();
                    } else {
                        if (!certificateName.value && (issueDate.value || issuer.value || description.value || file.files.length)) {
                            certificateName.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            certificateName.classList.remove('is-invalid');
                        }
                        if (!issueDate.value && (certificateName.value || issuer.value || description.value || file.files.length)) {
                            issueDate.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            issueDate.classList.remove('is-invalid');
                        }
                        if (!issuer.value && (certificateName.value || issueDate.value || description.value || file.files.length)) {
                            issuer.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            issuer.classList.remove('is-invalid');
                        }
                    }
                });

                criminalRecordEntries.forEach((entry, index) => {
                    const description = entry.querySelector(`textarea[name="criminal_records[${index}][description]"]`);
                    const file = entry.querySelector(`input[name="criminal_records[${index}][file]"]`);

                    if (!description.value && !file.files.length) {
                        entry.remove();
                    }
                });

                if (hasErrors) {
                    event.preventDefault();
                    alert('{{ __('messages.fill_required_fields') }}');
                }
            });

            bindDeleteButtons();
            bindCurrentCheckboxes();
            showStep(currentStep);
        });
    </script>
@endsection