@extends('layouts.app')
@section('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .form-step { display: none; }
        .form-step.active { display: block; }
        .error { color: red; }
        .profile-image-preview { max-width: 150px; height: auto; margin-top: 10px; }
    </style>
@endsection
@section('content')
    <div class="pagetitle">
        <h1>{{ __('messages.edit_employee') }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">{{ __('messages.employees') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.edit') }}</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('messages.edit_employee') }}</h5>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('employees.update', $employee->id) }}" method="POST" id="employee-form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Step 1: Personal Information -->
                            <div class="form-step active" id="step-1">
                                <h6>{{ __('messages.personal_information') }}</h6>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.employee_id') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ old('employee_id', $employee->employee_id) }}" required>
                                        @error('employee_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $employee->name) }}" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.email') }}</label>
                                    <div class="col-sm-9">
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $employee->email) }}">
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.phone') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $employee->phone) }}">
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.gender') }}</label>
                                    <div class="col-sm-9">
                                        <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                            <option value="" {{ old('gender', $employee->gender) == '' ? 'selected' : '' }}>{{ __('messages.select_gender') }}</option>
                                            <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                            <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
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
                                        @if($employee->profile_image)
                                            <img src="{{ $employee->profile_image_url }}" alt="Current Profile" class="profile-image-preview rounded-circle">
                                        @else
                                            <p>{{ __('messages.no_profile_image') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.dob') }}</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob', $employee->dob) }}">
                                        @error('dob')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.nationality') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nationality" class="form-control @error('nationality') is-invalid @enderror" value="{{ old('nationality', $employee->nationality) }}">
                                        @error('nationality')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.father_name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="father_name" class="form-control @error('father_name') is-invalid @enderror" value="{{ old('father_name', $employee->father_name) }}">
                                        @error('father_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.mother_name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="mother_name" class="form-control @error('mother_name') is-invalid @enderror" value="{{ old('mother_name', $employee->mother_name) }}">
                                        @error('mother_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.nrc') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nrc" class="form-control @error('nrc') is-invalid @enderror" value="{{ old('nrc', $employee->nrc) }}">
                                        @error('nrc')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.spouse_name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="spouse_name" class="form-control @error('spouse_name') is-invalid @enderror" value="{{ old('spouse_name', $employee->spouse_name) }}">
                                        @error('spouse_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.children_names') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="children_names" class="form-control @error('children_names') is-invalid @enderror" value="{{ old('children_names', $employee->children_names) }}">
                                        @error('children_names')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.address') }}</label>
                                    <div class="col-sm-9">
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address', $employee->address) }}</textarea>
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
                                        <input type="text" name="education" class="form-control @error('education') is-invalid @enderror" value="{{ old('education', $employee->education) }}">
                                        @error('education')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.current_position') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="current_position" class="form-control @error('current_position') is-invalid @enderror" value="{{ old('current_position', $employee->current_position) }}">
                                        @error('current_position')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.salary') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="salary" class="form-control @error('salary') is-invalid @enderror" value="{{ old('salary', $employee->salary) }}">
                                        @error('salary')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.department') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department', $employee->department) }}">
                                        @error('department')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.blood_type') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="blood_type" class="form-control @error('blood_type') is-invalid @enderror" value="{{ old('blood_type', $employee->blood_type) }}">
                                        @error('blood_type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.criminal_record') }}</label>
                                    <div class="col-sm-9">
                                        <select name="criminal_record" class="form-control @error('criminal_record') is-invalid @enderror">
                                            <option value="0" {{ old('criminal_record', $employee->criminal_record) == 0 ? 'selected' : '' }}>{{ __('messages.no') }}</option>
                                            <option value="1" {{ old('criminal_record', $employee->criminal_record) == 1 ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
                                        </select>
                                        @error('criminal_record')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.criminal_record_description') }}</label>
                                    <div class="col-sm-9">
                                        <textarea name="criminal_record_description" class="form-control @error('criminal_record_description') is-invalid @enderror">{{ old('criminal_record_description', $employee->criminal_record_description) }}</textarea>
                                        @error('criminal_record_description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-step">{{ __('messages.back') }}</button>
                                    <button type="button" class="btn btn-primary next-step">{{ __('messages.next') }}</button>
                                </div>
                            </div>
                            <!-- Step 3: Experience & Certificates -->
                            <div class="form-step" id="step-3">
                                <h6>{{ __('messages.experience') }}</h6>
                                <div id="experiences">
                                    @foreach($employee->experiences as $index => $experience)
                                        <div class="experience-entry">
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __('messages.position') }}</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="experiences[{{$index}}][position]" class="form-control @error('experiences.{{$index}}.position') is-invalid @enderror" value="{{ old('experiences.'.$index.'.position', $experience->position) }}">
                                                    @error('experiences.{{$index}}.position')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __('messages.department') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="experiences[{{$index}}][department]" class="form-control @error('experiences.{{$index}}.department') is-invalid @enderror" value="{{ old('experiences.'.$index.'.department', $experience->department) }}">
                                                    @error('experiences.{{$index}}.department')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __('messages.from_date') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="date" name="experiences[{{$index}}][from_date]" class="form-control @error('experiences.{{$index}}.from_date') is-invalid @enderror" value="{{ old('experiences.'.$index.'.from_date', $experience->from_date) }}">
                                                    @error('experiences.{{$index}}.from_date')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __('messages.to_date') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="date" name="experiences[{{$index}}][to_date]" class="form-control @error('experiences.{{$index}}.to_date') is-invalid @enderror" value="{{ old('experiences.'.$index.'.to_date', $experience->to_date) }}">
                                                    @error('experiences.{{$index}}.to_date')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __('messages.location') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="experiences[{{$index}}][location]" class="form-control @error('experiences.{{$index}}.location') is-invalid @enderror" value="{{ old('experiences.'.$index.'.location', $experience->location) }}">
                                                    @error('experiences.{{$index}}.location')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-secondary mb-3" onclick="addExperience()">{{ __('messages.add_experience') }}</button>
                                <h6>{{ __('messages.certificates') }}</h6>
                                <div id="certificates">
                                    @foreach($employee->certificates as $index => $certificate)
                                        <div class="certificate-entry">
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __('messages.certificate_name') }}</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="certificates[{{$index}}][certificate_name]" class="form-control @error('certificates.{{$index}}.certificate_name') is-invalid @enderror" value="{{ old('certificates.'.$index.'.certificate_name', $certificate->certificate_name) }}">
                                                    @error('certificates.{{$index}}.certificate_name')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __('messages.issue_date') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="date" name="certificates[{{$index}}][issue_date]" class="form-control @error('certificates.{{$index}}.issue_date') is-invalid @enderror" value="{{ old('certificates.'.$index.'.issue_date', $certificate->issue_date) }}">
                                                    @error('certificates.'.$index.'.issue_date')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __('messages.issuer') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="certificates[{{$index}}][issuer]" class="form-control @error('certificates.{{$index}}.issuer') is-invalid @enderror" value="{{ old('certificates.'.$index.'.issuer', $certificate->issuer) }}">
                                                    @error('certificates.'.$index.'.issuer')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __('messages.description') }}</label>
                                                <div class="col-sm-9">
                                                    <textarea name="certificates[{{$index}}][description]" class="form-control @error('certificates.'.$index.'.description') is-invalid @enderror">{{ old('certificates.'.$index.'.description', $certificate->description) }}</textarea>
                                                    @error('certificates.'.$index.'.description')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-secondary mb-3" onclick="addCertificate()">{{ __('messages.add_certificate') }}</button>
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
        let experienceIndex = {{ $employee->experiences->count() }};
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
                            <input type="date" name="experiences[${experienceIndex}][to_date]" class="form-control">
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
        }

        let certificateIndex = {{ $employee->certificates->count() }};
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
                </div>`;
            document.getElementById('certificates').insertAdjacentHTML('beforeend', entry);
            certificateIndex++;
            bindDeleteButtons();
        }

        function bindDeleteButtons() {
            document.querySelectorAll('.delete-entry').forEach(button => {
                button.addEventListener('click', function () {
                    this.closest('.experience-entry, .certificate-entry').remove();
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
                let hasErrors = false;

                experienceEntries.forEach((entry, index) => {
                    const position = entry.querySelector(`input[name="experiences[${index}][position]"]`);
                    const department = entry.querySelector(`input[name="experiences[${index}][department]"]`);
                    const fromDate = entry.querySelector(`input[name="experiences[${index}][from_date]"]`);
                    const location = entry.querySelector(`input[name="experiences[${index}][location]"]`);

                    // Check if any field is filled
                    if (position.value || department.value || fromDate.value || location.value) {
                        // If any field is filled, all required fields must be filled
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
                        if (!location.value) {
                            location.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            location.classList.remove('is-invalid');
                        }
                    } else {
                        // Remove empty entries
                        entry.remove();
                    }
                });

                certificateEntries.forEach((entry, index) => {
                    const certificateName = entry.querySelector(`input[name="certificates[${index}][certificate_name]"]`);
                    const issueDate = entry.querySelector(`input[name="certificates[${index}][issue_date]"]`);
                    const issuer = entry.querySelector(`input[name="certificates[${index}][issuer]"]`);

                    // Check if any field is filled
                    if (certificateName.value || issueDate.value || issuer.value) {
                        // If any field is filled, all required fields must be filled
                        if (!certificateName.value) {
                            certificateName.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            certificateName.classList.remove('is-invalid');
                        }
                        if (!issueDate.value) {
                            issueDate.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            issueDate.classList.remove('is-invalid');
                        }
                    } else {
                        // Remove empty entries
                        entry.remove();
                    }
                });

                if (hasErrors) {
                    event.preventDefault();
                    alert('{{ __('messages.fill_required_fields') }}');
                }
            });

            bindDeleteButtons();
            showStep(currentStep);
        });
    </script>
@endsection