@extends('layouts.app')
@section('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .error {
            color: red;
        }

        .profile-image-preview {
            max-width: 150px;
            height: auto;
            margin-top: 10px;
        }

        .is-invalid {
            border-color: red;
        }

        .invalid-feedback {
            display: none;
        }

        .is-invalid~.invalid-feedback {
            display: block;
        }
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
                        <form action="{{ route('employees.update', $employee->id) }}" method="POST" id="employee-form"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Step 1: Personal Information -->
                            <div class="form-step active" id="step-1">
                                <h6>{{ __('messages.personal_information') }}</h6>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.employee_id') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="employee_id"
                                            class="form-control @error('employee_id') is-invalid @enderror"
                                            value="{{ old('employee_id', $employee->employee_id) }}" required>
                                        @error('employee_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $employee->name) }}" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.email') }}</label>
                                    <div class="col-sm-9">
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $employee->email) }}">
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.phone') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $employee->phone) }}">
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.gender') }}</label>
                                    <div class="col-sm-9">
                                        <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                            <option value=""
                                                {{ old('gender', $employee->gender) == '' ? 'selected' : '' }}>
                                                {{ __('messages.select_gender') }}</option>
                                            <option value="male"
                                                {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>
                                                {{ __('messages.male') }}</option>
                                            <option value="female"
                                                {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>
                                                {{ __('messages.female') }}</option>
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.profile_image') }}</label>
                                    <div class="col-sm-9">
                                        <input type="file" name="profile_image"
                                            class="form-control @error('profile_image') is-invalid @enderror"
                                            accept="image/*">
                                        @error('profile_image')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        @if ($employee->profile_image)
                                            <img src="{{ $employee->profile_image_url }}" alt="Current Profile"
                                                class="profile-image-preview rounded-circle">
                                        @else
                                            <p>{{ __('messages.no_profile_image') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.dob') }}</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="dob"
                                            class="form-control @error('dob') is-invalid @enderror"
                                            value="{{ old('dob', $employee->dob) }}">
                                        @error('dob')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.nationality') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nationality"
                                            class="form-control @error('nationality') is-invalid @enderror"
                                            value="{{ old('nationality', $employee->nationality) }}">
                                        @error('nationality')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.father_name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="father_name"
                                            class="form-control @error('father_name') is-invalid @enderror"
                                            value="{{ old('father_name', $employee->father_name) }}">
                                        @error('father_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.mother_name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="mother_name"
                                            class="form-control @error('mother_name') is-invalid @enderror"
                                            value="{{ old('mother_name', $employee->mother_name) }}">
                                        @error('mother_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.nrc') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nrc"
                                            class="form-control @error('nrc') is-invalid @enderror"
                                            value="{{ old('nrc', $employee->nrc) }}">
                                        @error('nrc')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.spouse_name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="spouse_name"
                                            class="form-control @error('spouse_name') is-invalid @enderror"
                                            value="{{ old('spouse_name', $employee->spouse_name) }}">
                                        @error('spouse_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.children_names') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="children_names"
                                            class="form-control @error('children_names') is-invalid @enderror"
                                            value="{{ old('children_names', $employee->children_names) }}">
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
                                    <button type="button"
                                        class="btn btn-primary next-step">{{ __('messages.next') }}</button>
                                </div>
                            </div>
                            <!-- Step 2: Professional Details -->
                            <div class="form-step" id="step-2">
                                <h6>{{ __('messages.professional_details') }}</h6>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.education') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="education"
                                            class="form-control @error('education') is-invalid @enderror"
                                            value="{{ old('education', $employee->education) }}">
                                        @error('education')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.current_position') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="current_position"
                                            class="form-control @error('current_position') is-invalid @enderror"
                                            value="{{ old('current_position', $employee->current_position) }}">
                                        @error('current_position')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.salary') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="salary"
                                            class="form-control @error('salary') is-invalid @enderror"
                                            value="{{ old('salary', $employee->salary) }}">
                                        @error('salary')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.department') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="department"
                                            class="form-control @error('department') is-invalid @enderror"
                                            value="{{ old('department', $employee->department) }}">
                                        @error('department')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.blood_type') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="blood_type"
                                            class="form-control @error('blood_type') is-invalid @enderror"
                                            value="{{ old('blood_type', $employee->blood_type) }}">
                                        @error('blood_type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button"
                                        class="btn btn-secondary prev-step">{{ __('messages.back') }}</button>
                                    <button type="button"
                                        class="btn btn-primary next-step">{{ __('messages.next') }}</button>
                                </div>
                            </div>
                            <!-- Step 3: Experience & Certificates -->
                            <div class="form-step" id="step-3">
                                <h6>{{ __('messages.experience') }}</h6>
                                <div id="experiences">
                                    @foreach ($employee->experiences as $index => $experience)
                                        <div class="experience-entry">
                                            <div class="form-group row mb-3">
                                                <label
                                                    class="col-sm-3 col-form-label">{{ __('messages.company_name') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                        name="experiences[{{ $index }}][company_name]"
                                                        class="form-control @error('experiences.{{ $index }}.company_name') is-invalid @enderror"
                                                        value="{{ old('experiences.' . $index . '.company_name', $experience->company_name) }}">
                                                    @error('experiences.{{ $index }}.company_name')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label
                                                    class="col-sm-3 col-form-label">{{ __('messages.position') }}</label>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        name="experiences[{{ $index }}][position]"
                                                        class="form-control @error('experiences.{{ $index }}.position') is-invalid @enderror"
                                                        value="{{ old('experiences.' . $index . '.position', $experience->position) }}">
                                                    @error('experiences.{{ $index }}.position')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label
                                                    class="col-sm-3 col-form-label">{{ __('messages.department') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                        name="experiences[{{ $index }}][department]"
                                                        class="form-control @error('experiences.{{ $index }}.department') is-invalid @enderror"
                                                        value="{{ old('experiences.' . $index . '.department', $experience->department) }}">
                                                    @error('experiences.{{ $index }}.department')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label
                                                    class="col-sm-3 col-form-label">{{ __('messages.from_date') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="date"
                                                        name="experiences[{{ $index }}][from_date]"
                                                        class="form-control @error('experiences.{{ $index }}.from_date') is-invalid @enderror"
                                                        value="{{ old('experiences.' . $index . '.from_date', $experience->from_date) }}">
                                                    @error('experiences.{{ $index }}.from_date')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label
                                                    class="col-sm-3 col-form-label">{{ __('messages.to_date') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="date"
                                                        name="experiences[{{ $index }}][to_date]"
                                                        class="form-control to-date @error('experiences.{{ $index }}.to_date') is-invalid @enderror"
                                                        value="{{ old('experiences.' . $index . '.to_date', $experience->to_date) }}"
                                                        {{ $experience->is_current ? 'disabled' : '' }}>
                                                    <input type="hidden"
                                                        name="experiences[{{ $index }}][is_current]"
                                                        value="0">
                                                    <label><input type="checkbox" class="is-current"
                                                            name="experiences[{{ $index }}][is_current]"
                                                            value="1"
                                                            {{ old('experiences.' . $index . '.is_current', $experience->is_current) ? 'checked' : '' }}>
                                                        {{ __('messages.currently') }}</label>                                                    
                                                    @error('experiences.{{ $index }}.to_date')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label
                                                    class="col-sm-3 col-form-label">{{ __('messages.location') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                        name="experiences[{{ $index }}][location]"
                                                        class="form-control @error('experiences.{{ $index }}.location') is-invalid @enderror"
                                                        value="{{ old('experiences.' . $index . '.location', $experience->location) }}">
                                                    @error('experiences.{{ $index }}.location')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-secondary mb-3"
                                    onclick="addExperience()">{{ __('messages.add_experience') }}</button>
                                <h6>{{ __('messages.certificates') }}</h6>
                                <div id="certificates">
                                    @foreach ($employee->certificates as $index => $certificate)
                                        <div class="certificate-entry">
                                            <div class="form-group row mb-3">
                                                <label
                                                    class="col-sm-3 col-form-label">{{ __('messages.certificate_name') }}</label>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        name="certificates[{{ $index }}][certificate_name]"
                                                        class="form-control @error('certificates.{{ $index }}.certificate_name') is-invalid @enderror"
                                                        value="{{ old('certificates.' . $index . '.certificate_name', $certificate->certificate_name) }}">
                                                    @error('certificates.{{ $index }}.certificate_name')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label
                                                    class="col-sm-3 col-form-label">{{ __('messages.issue_date') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="date"
                                                        name="certificates[{{ $index }}][issue_date]"
                                                        class="form-control @error('certificates.{{ $index }}.issue_date') is-invalid @enderror"
                                                        value="{{ old('certificates.' . $index . '.issue_date', $certificate->issue_date) }}">
                                                    @error('certificates.{{ $index }}.issue_date')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label
                                                    class="col-sm-3 col-form-label">{{ __('messages.issuer') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                        name="certificates[{{ $index }}][issuer]"
                                                        class="form-control @error('certificates.{{ $index }}.issuer') is-invalid @enderror"
                                                        value="{{ old('certificates.' . $index . '.issuer', $certificate->issuer) }}">
                                                    @error('certificates.{{ $index }}.issuer')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label
                                                    class="col-sm-3 col-form-label">{{ __('messages.description') }}</label>
                                                <div class="col-sm-9">
                                                    <textarea name="certificates[{{ $index }}][description]"
                                                        class="form-control @error('certificates.{{ $index }}.description') is-invalid @enderror">{{ old('certificates.' . $index . '.description', $certificate->description) }}</textarea>
                                                    @error('certificates.' . $index . '.description')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __('messages.file') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="file" name="certificates[{{ $index }}][file]"
                                                        class="form-control @error('certificates.{{ $index }}.file') is-invalid @enderror"
                                                        accept="application/pdf,image/jpeg,image/png,image/jpg">
                                                    @if ($certificate->file_path)
                                                        <input type="hidden"
                                                            name="certificates[{{ $index }}][existing_file]"
                                                            value="{{ $certificate->file_path }}">
                                                        <a href="{{ $certificate->file_url }}"
                                                            target="_blank">{{ __('messages.view_current_file') }}</a>
                                                    @endif
                                                    @error('certificates.{{ $index }}.file')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-secondary mb-3"
                                    onclick="addCertificate()">{{ __('messages.add_certificate') }}</button>
                                <h6>{{ __('messages.criminal_records') }}</h6>
                                <div id="criminal-records">
                                    @foreach ($employee->criminalRecords as $index => $record)
                                        <div class="criminal-record-entry">
                                            <div class="form-group row mb-3">
                                                <label
                                                    class="col-sm-3 col-form-label">{{ __('messages.description') }}</label>
                                                <div class="col-sm-8">
                                                    <textarea name="criminal_records[{{ $index }}][description]"
                                                        class="form-control @error('criminal_records.{{ $index }}.description') is-invalid @enderror">{{ old('criminal_records.' . $index . '.description', $record->description) }}</textarea>
                                                    @error('criminal_records.{{ $index }}.description')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __('messages.file') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="file"
                                                        name="criminal_records[{{ $index }}][file]"
                                                        class="form-control @error('criminal_records.{{ $index }}.file') is-invalid @enderror"
                                                        accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                                    @if ($record->file_path)
                                                        <input type="hidden"
                                                            name="criminal_records[{{ $index }}][existing_file]"
                                                            value="{{ $record->file_path }}">
                                                        <a href="{{ $record->file_url }}"
                                                            target="_blank">{{ __('messages.view_current_file') }}</a>
                                                    @endif
                                                    @error('criminal_records.{{ $index }}.file')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-secondary mb-3"
                                    onclick="addCriminalRecord()">{{ __('messages.add_criminal_record') }}</button>
                                <div class="text-end">
                                    <button type="button"
                                        class="btn btn-secondary prev-step">{{ __('messages.back') }}</button>
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
                        <label class="col-sm-3 col-form-label">{{ __('messages.company_name') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="experiences[${experienceIndex}][company_name]" class="form-control">
                        </div>
                    </div>
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
                            <input type="hidden" name="experiences[${experienceIndex}][is_current]" value="0">
                            <label><input type="checkbox" class="is-current" name="experiences[${experienceIndex}][is_current]" value="1"> {{ __('messages.currently') }}</label>

                            <span class="invalid-feedback">{{ __('messages.experience_to_date_required') }}</span>
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
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.issuer') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="certificates[${certificateIndex}][issuer]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.description') }}</label>
                <div class="col-sm-9">
                    <textarea name="certificates[${certificateIndex}][description]" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.file') }}</label>
                <div class="col-sm-9">
                    <input type="file" name="certificates[${certificateIndex}][file]" class="form-control" accept="application/pdf,image/jpeg,image/png,image/jpg">
                </div>
            </div>
        </div>`;
            document.getElementById('certificates').insertAdjacentHTML('beforeend', entry);
            certificateIndex++;
            bindDeleteButtons();
        }

        let criminalRecordIndex = {{ $employee->criminalRecords->count() }};

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
                        <label class="col-sm-3 col-form-label">{{ __('messages.file') }}</label>
                        <div class="col-sm-9">
                            <input type="file" name="criminal_records[${criminalRecordIndex}][file]" class="form-control" accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                        </div>
                    </div>
                </div>`;
            document.getElementById('criminal-records').insertAdjacentHTML('beforeend', entry);
            criminalRecordIndex++;
            bindDeleteButtons();
        }

        function bindDeleteButtons() {
            document.querySelectorAll('.delete-entry').forEach(button => {
                button.removeEventListener('click', handleDelete);
                button.addEventListener('click', handleDelete);
            });
        }

        function handleDelete() {
            this.closest('.experience-entry, .certificate-entry, .criminal-record-entry').remove();
        }

        function bindCurrentCheckboxes() {
            document.querySelectorAll('.is-current').forEach(checkbox => {
                checkbox.removeEventListener('change', handleCheckboxChange);
                checkbox.addEventListener('change', handleCheckboxChange);
            });
        }

        function handleCheckboxChange() {
            const toDateInput = this.closest('.form-group').querySelector('.to-date');
            toDateInput.disabled = this.checked;
            if (this.checked) toDateInput.value = '';
        }

        document.addEventListener('DOMContentLoaded', function() {
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
                button.addEventListener('click', function() {
                    const currentInputs = steps[currentStep].querySelectorAll(
                        'input[required], select[required]');
                    let valid = true;
                    currentInputs.forEach(input => {
                        if (!input.value.trim()) {
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
                button.addEventListener('click', function() {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

            document.getElementById('employee-form').addEventListener('submit', function(event) {
                const experienceEntries = document.querySelectorAll('.experience-entry');
                const certificateEntries = document.querySelectorAll('.certificate-entry');
                const criminalRecordEntries = document.querySelectorAll('.criminal-record-entry');
                let hasErrors = false;

                experienceEntries.forEach((entry, index) => {
                    const companyName = entry.querySelector(
                        `input[name="experiences[${index}][company_name]"]`);
                    const position = entry.querySelector(
                        `input[name="experiences[${index}][position]"]`);
                    const department = entry.querySelector(
                        `input[name="experiences[${index}][department]"]`);
                    const fromDate = entry.querySelector(
                        `input[name="experiences[${index}][from_date]"]`);
                    const toDate = entry.querySelector(
                        `input[name="experiences[${index}][to_date]"]`);
                    const isCurrent = entry.querySelector(
                        `input[name="experiences[${index}][is_current]"]:checked`);
                    const location = entry.querySelector(
                        `input[name="experiences[${index}][location]"]`);

                    const anyFilled = position.value.trim() || department.value.trim() || fromDate
                        .value.trim() || toDate.value.trim() || isCurrent || location.value.trim();
                    if (!anyFilled) {
                        entry.remove();
                    } else {
                        if (!position.value.trim()) {
                            position.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            position.classList.remove('is-invalid');
                        }
                        if (!department.value.trim()) {
                            department.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            department.classList.remove('is-invalid');
                        }
                        if (!fromDate.value.trim()) {
                            fromDate.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            fromDate.classList.remove('is-invalid');
                        }
                        if (!isCurrent && !toDate.value.trim()) {
                            toDate.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            toDate.classList.remove('is-invalid');
                        }
                        if (!location.value.trim()) {
                            location.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            location.classList.remove('is-invalid');
                        }
                    }
                });

                certificateEntries.forEach((entry, index) => {
                    const certificateName = entry.querySelector(
                        `input[name="certificates[${index}][certificate_name]"]`);
                    const issueDate = entry.querySelector(
                        `input[name="certificates[${index}][issue_date]"]`);
                    const issuer = entry.querySelector(
                        `input[name="certificates[${index}][issuer]"]`);
                    const description = entry.querySelector(
                        `textarea[name="certificates[${index}][description]"]`);
                    const file = entry.querySelector(`input[name="certificates[${index}][file]"]`);
                    const existingFile = entry.querySelector(
                        `input[name="certificates[${index}][existing_file]"]`);

                    const anyFilled = certificateName.value.trim() || issueDate.value.trim() ||
                        issuer.value.trim() || description.value.trim() || file.files.length || (
                            existingFile && existingFile.value);
                    if (!anyFilled) {
                        entry.remove();
                    } else {
                        if (!certificateName.value.trim()) {
                            certificateName.classList.add('is-invalid');
                            hasErrors = true;
                        } else {
                            certificateName.classList.remove('is-invalid');
                        }
                    }
                });

                criminalRecordEntries.forEach((entry, index) => {
                    const description = entry.querySelector(
                        `textarea[name="criminal_records[${index}][description]"]`);
                    const file = entry.querySelector(
                        `input[name="criminal_records[${index}][file]"]`);
                    const existingFile = entry.querySelector(
                        `input[name="criminal_records[${index}][existing_file]"]`);

                    const anyFilled = description.value.trim() || file.files.length || (
                        existingFile && existingFile.value);
                    if (!anyFilled) {
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
