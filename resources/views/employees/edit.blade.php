{{-- resources/views/employees/edit.blade.php --}}
@extends('layouts.app')

@section('css')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<style>
    .form-step { display: none; }
    .form-step.active { display: block; }
    .error { color: red; }
    .is-invalid { border-color: red; }
    .invalid-feedback { display: none; }
    .is-invalid ~ .invalid-feedback { display: block; }
    .delete-entry { margin-top: 28px; }
    .section-header { margin-top: 40px; margin-bottom: 20px; font-weight: bold; font-size: 1.2em; }
    .profile-image-preview { max-width: 150px; height: auto; margin-top: 10px; border-radius: 50%; }
    .current-image { max-width: 50px; margin-bottom: 10px; }
    .img-thumbnail{max-width: 50px; margin-bottom: 10px !important ;}
</style>
@endsection

@section('content')
<div class="pagetitle">
    <h1>{{ __('messages.edit_employee') }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">{{ __('messages.employees') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employees.show', $employee->id) }}">{{ $employee->name }}</a></li>
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

                        <!-- Current Profile Image -->
                        @if($employee->profile_image)
                
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">ပရိုဖိုင်ပုံ</label>
                                <div class="col-sm-9">
                                    <img src="{{ asset('storage/employees/' . $employee->profile_image) }}" alt="Profile Image" class="current-image img-thumbnail">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_profile_image" id="remove_profile_image" value="1">
                                        <label class="form-check-label" for="remove_profile_image">
                                           ပုံဖျက်မည်
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Step 1: Personal Information -->
                        <div class="form-step active" id="step-1">
                            <h6>{{ __('messages.personal_information') }}</h6>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.employee_id') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ old('employee_id', $employee->employee_id) }}" required>
                                    @error('employee_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.name') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $employee->name) }}" required>
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.phone') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $employee->phone) }}">
                                    @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.gender') }}</label>
                                <div class="col-sm-9">
                                    <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                        <option value="">{{ __('messages.select_gender') }}</option>
                                        <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                        <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                                    </select>
                                    @error('gender') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.profile_image') }}</label>
                                <div class="col-sm-9">
                                    <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" accept="image/*">
                                    @error('profile_image') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.mm_dob') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="mm_dob" class="form-control @error('mm_dob') is-invalid @enderror" value="{{ old('mm_dob', $employee->mm_dob) }}">
                                    @error('mm_dob') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.eng_dob') }}</label>
                                <div class="col-sm-9">
                                    <input type="date" name="eng_dob" class="form-control @error('eng_dob') is-invalid @enderror" value="{{ old('eng_dob', $employee->eng_dob ? \Carbon\Carbon::parse($employee->eng_dob)->format('Y-m-d') : '') }}">
                                    @error('eng_dob') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.nationality') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nationality" class="form-control @error('nationality') is-invalid @enderror" value="{{ old('nationality', $employee->nationality) }}">
                                    @error('nationality') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.religion') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="religion" class="form-control @error('religion') is-invalid @enderror" value="{{ old('religion', $employee->religion) }}">
                                    @error('religion') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.father_name') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="father_name" class="form-control @error('father_name') is-invalid @enderror" value="{{ old('father_name', $employee->father_name) }}">
                                    @error('father_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.mother_name') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="mother_name" class="form-control @error('mother_name') is-invalid @enderror" value="{{ old('mother_name', $employee->mother_name) }}">
                                    @error('mother_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.nrc') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nrc" class="form-control @error('nrc') is-invalid @enderror" value="{{ old('nrc', $employee->nrc) }}">
                                    @error('nrc') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.spouse_name') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="spouse_name" class="form-control @error('spouse_name') is-invalid @enderror" value="{{ old('spouse_name', $employee->spouse_name) }}">
                                    @error('spouse_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.spouse_job') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="spouse_job" class="form-control @error('spouse_job') is-invalid @enderror" value="{{ old('spouse_job', $employee->spouse_job) }}">
                                    @error('spouse_job') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.spouse_job_place') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="spouse_job_place" class="form-control @error('spouse_job_place') is-invalid @enderror" value="{{ old('spouse_job_place', $employee->spouse_job_place) }}">
                                    @error('spouse_job_place') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.current_address') }}</label>
                                <div class="col-sm-9">
                                    <textarea name="current_address" class="form-control @error('current_address') is-invalid @enderror">{{ old('current_address', $employee->current_address) }}</textarea>
                                    @error('current_address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.permanent_address') }}</label>
                                <div class="col-sm-9">
                                    <textarea name="permanent_address" class="form-control @error('permanent_address') is-invalid @enderror">{{ old('permanent_address', $employee->permanent_address) }}</textarea>
                                    @error('permanent_address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-primary next-step">{{ __('messages.next') }}</button>
                            </div>
                        </div>

                        <!-- Step 2: Professional Details + Children + Relatives -->
                        <div class="form-step" id="step-2">
                            <h6>{{ __('messages.professional_details') }}</h6>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.current_position') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="current_position" class="form-control @error('current_position') is-invalid @enderror" value="{{ old('current_position', $employee->current_position) }}">
                                    @error('current_position') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.salary') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="salary" class="form-control @error('salary') is-invalid @enderror" value="{{ old('salary', $employee->salary) }}">
                                    @error('salary') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.department') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department', $employee->department) }}">
                                    @error('department') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.blood_type') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="blood_type" class="form-control @error('blood_type') is-invalid @enderror" value="{{ old('blood_type', $employee->blood_type) }}">
                                    @error('blood_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.lang_proficiency') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="lang_proficiency" class="form-control @error('lang_proficiency') is-invalid @enderror" value="{{ old('lang_proficiency', $employee->lang_proficiency) }}" placeholder="Comma-separated values">
                                    @error('lang_proficiency') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">{{ __('messages.hobby') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="hobby" class="form-control @error('hobby') is-invalid @enderror" value="{{ old('hobby', $employee->hobby) }}" placeholder="Comma-separated values">
                                    @error('hobby') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Children -->
                            <div class="section-header">{{ __('messages.children') }}</div>
                            <div id="children">
                                @foreach(old('children', $employee->children ?? []) as $index => $child)
                                    @php $childData = is_object($child) ? $child->toArray() : $child; @endphp
                                    <div class="child-entry">
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.name') }}</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="children[{{ $index }}][name]" class="form-control" value="{{ $childData['name'] ?? '' }}">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.date_of_birth') }}</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="children[{{ $index }}][date_of_birth]" class="form-control" value="{{ isset($childData['date_of_birth']) ? \Carbon\Carbon::parse($childData['date_of_birth'])->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>
                                        @if(isset($childData['id']))
                                            <input type="hidden" name="children[{{ $index }}][id]" value="{{ $childData['id'] }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mb-3" onclick="addChild()">{{ __('messages.add_child') }}</button>

                            <!-- Relatives -->
                            <div class="section-header">{{ __('messages.relatives') }}</div>
                            <div id="relatives">
                                @foreach(old('relatives', $employee->relatives ?? []) as $index => $relative)
                                    @php $relativeData = is_object($relative) ? $relative->toArray() : $relative; @endphp
                                    <div class="relative-entry">
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.name') }}</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="relatives[{{ $index }}][name]" class="form-control" value="{{ $relativeData['name'] ?? '' }}">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.relation') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="relatives[{{ $index }}][relation]" class="form-control" value="{{ $relativeData['relation'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.job') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="relatives[{{ $index }}][job]" class="form-control" value="{{ $relativeData['job'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.location') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="relatives[{{ $index }}][location]" class="form-control" value="{{ $relativeData['location'] ?? '' }}">
                                            </div>
                                        </div>
                                        @if(isset($relativeData['id']))
                                            <input type="hidden" name="relatives[{{ $index }}][id]" value="{{ $relativeData['id'] }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mb-3" onclick="addRelative()">{{ __('messages.add_relative') }}</button>

                            <div class="text-end">
                                <button type="button" class="btn btn-secondary prev-step">{{ __('messages.back') }}</button>
                                <button type="button" class="btn btn-primary next-step">{{ __('messages.next') }}</button>
                            </div>
                        </div>

                        <!-- Step 3: Education + Past Experiences + Training + Current Company Experience -->
                        <div class="form-step" id="step-3">
                            <!-- Education -->
                            <div class="section-header">{{ __('messages.education') }}</div>
                            <div id="educations">
                                @foreach(old('educations', $employee->educations ?? []) as $index => $education)
                                    @php $eduData = is_object($education) ? $education->toArray() : $education; @endphp
                                    <div class="education-entry">
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.type') }}</label>
                                            <div class="col-sm-9">
                                                <select name="educations[{{ $index }}][type]" class="form-control">
                                                    <option value="school" {{ ($eduData['type'] ?? '') == 'school' ? 'selected' : '' }}>{{ __('messages.school') }}</option>
                                                    <option value="uni" {{ ($eduData['type'] ?? '') == 'uni' ? 'selected' : '' }}>{{ __('messages.university') }}</option>
                                                    <option value="other" {{ ($eduData['type'] ?? '') == 'other' ? 'selected' : '' }}>{{ __('messages.other') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.institution_name') }}</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="educations[{{ $index }}][institution_name]" class="form-control" value="{{ $eduData['institution_name'] ?? '' }}">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.highest_certificate') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="educations[{{ $index }}][highest_certificate]" class="form-control" value="{{ $eduData['highest_certificate'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.field_of_study') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="educations[{{ $index }}][field_of_study]" class="form-control" value="{{ $eduData['field_of_study'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.date') }}</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="educations[{{ $index }}][date]" class="form-control" value="{{ isset($eduData['date']) ? \Carbon\Carbon::parse($eduData['date'])->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.remark') }}</label>
                                            <div class="col-sm-9">
                                                <textarea name="educations[{{ $index }}][remark]" class="form-control">{{ $eduData['remark'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        @if(isset($eduData['id']))
                                            <input type="hidden" name="educations[{{ $index }}][id]" value="{{ $eduData['id'] }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mb-3" onclick="addEducation()">{{ __('messages.add_education') }}</button>

                            <!-- Past Experiences -->
                            <div class="section-header">{{ __('messages.past_experiences') }}</div>
                            <div id="past_experiences">
                                @foreach(old('past_experiences', $employee->pastExperiences ?? []) as $index => $experience)
                                    @php $expData = is_object($experience) ? $experience->toArray() : $experience; @endphp
                                    <div class="past-experience-entry">
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.position') }}</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="past_experiences[{{ $index }}][position]" class="form-control" value="{{ $expData['position'] ?? '' }}">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.salary') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="past_experiences[{{ $index }}][salary]" class="form-control" value="{{ $expData['salary'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.location') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="past_experiences[{{ $index }}][location]" class="form-control" value="{{ $expData['location'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.start_date') }}</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="past_experiences[{{ $index }}][start_date]" class="form-control" value="{{ isset($expData['start_date']) ? \Carbon\Carbon::parse($expData['start_date'])->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.end_date') }}</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="past_experiences[{{ $index }}][end_date]" class="form-control" value="{{ isset($expData['end_date']) ? \Carbon\Carbon::parse($expData['end_date'])->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.remark') }}</label>
                                            <div class="col-sm-9">
                                                <textarea name="past_experiences[{{ $index }}][remark]" class="form-control">{{ $expData['remark'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.life_insurance') }}</label>
                                            <div class="col-sm-9">
                                                <select name="past_experiences[{{ $index }}][life_insurance]" class="form-control">
                                                    <option value="no" {{ ($expData['life_insurance'] ?? '') == 'no' ? 'selected' : '' }}>{{ __('messages.no') }}</option>
                                                    <option value="yes" {{ ($expData['life_insurance'] ?? '') == 'yes' ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        @if(isset($expData['id']))
                                            <input type="hidden" name="past_experiences[{{ $index }}][id]" value="{{ $expData['id'] }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mb-3" onclick="addPastExperience()">{{ __('messages.add_past_experience') }}</button>

                            <!-- Training -->
                            <div class="section-header">{{ __('messages.training') }}</div>
                            <div id="trainings">
                                @foreach(old('trainings', $employee->trainings ?? []) as $index => $training)
                                    @php $trainingData = is_object($training) ? $training->toArray() : $training; @endphp
                                    <div class="training-entry">
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.training_type') }}</label>
                                            <div class="col-sm-9">
                                                <select name="trainings[{{ $index }}][training_type]" class="form-control">
                                                    <option value="domestic" {{ ($trainingData['training_type'] ?? '') == 'domestic' ? 'selected' : '' }}>{{ __('messages.domestic') }}</option>
                                                    <option value="foreign" {{ ($trainingData['training_type'] ?? '') == 'foreign' ? 'selected' : '' }}>{{ __('messages.foreign') }}</option>
                                                    <option value="other" {{ ($trainingData['training_type'] ?? '') == 'other' ? 'selected' : '' }}>{{ __('messages.other') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.course_name') }}</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="trainings[{{ $index }}][course_name]" class="form-control" value="{{ $trainingData['course_name'] ?? '' }}">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.location') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="trainings[{{ $index }}][location]" class="form-control" value="{{ $trainingData['location'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.start_date') }}</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="trainings[{{ $index }}][start_date]" class="form-control" value="{{ isset($trainingData['start_date']) ? \Carbon\Carbon::parse($trainingData['start_date'])->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.end_date') }}</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="trainings[{{ $index }}][end_date]" class="form-control" value="{{ isset($trainingData['end_date']) ? \Carbon\Carbon::parse($trainingData['end_date'])->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>
                                        @if(isset($trainingData['id']))
                                            <input type="hidden" name="trainings[{{ $index }}][id]" value="{{ $trainingData['id'] }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mb-3" onclick="addTraining()">{{ __('messages.add_training') }}</button>

                            <!-- Current Company Experience (နိုင်ငံ့ဝန်ထမ်းတာဝန်ထမ်းဆောင်မှုမှတ်တမ်း) -->
                            <div class="section-header">{{ __('messages.experience') }}</div>
                            <div id="experiences">
                                @foreach(old('experiences', $employee->experiences ?? []) as $index => $experience)
                                    @php $expData = is_object($experience) ? $experience->toArray() : $experience; @endphp
                                    <div class="experience-entry">
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.position') }}</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="experiences[{{ $index }}][position]" class="form-control" value="{{ $expData['position'] ?? '' }}">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.department') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="experiences[{{ $index }}][department]" class="form-control" value="{{ $expData['department'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.from_date') }}</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="experiences[{{ $index }}][from_date]" class="form-control" value="{{ isset($expData['from_date']) ? \Carbon\Carbon::parse($expData['from_date'])->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.to_date') }}</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="experiences[{{ $index }}][to_date]" class="form-control to-date" value="{{ isset($expData['to_date']) ? \Carbon\Carbon::parse($expData['to_date'])->format('Y-m-d') : '' }}" {{ $expData['is_current'] ?? false ? 'disabled' : '' }}>
                                                <input type="hidden" name="experiences[{{ $index }}][is_current]" value="0">
                                                <label><input type="checkbox" class="is-current" name="experiences[{{ $index }}][is_current]" value="1" {{ $expData['is_current'] ?? false ? 'checked' : '' }}> {{ __('messages.currently') }}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.location') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="experiences[{{ $index }}][location]" class="form-control" value="{{ $expData['location'] ?? '' }}">
                                            </div>
                                        </div>
                                        @if(isset($expData['id']))
                                            <input type="hidden" name="experiences[{{ $index }}][id]" value="{{ $expData['id'] }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mb-3" onclick="addExperience()">{{ __('messages.add_experience') }}</button>

                            <div class="text-end">
                                <button type="button" class="btn btn-secondary prev-step">{{ __('messages.back') }}</button>
                                <button type="button" class="btn btn-primary next-step">{{ __('messages.next') }}</button>
                            </div>
                        </div>

                        <!-- Step 4: Personnel Actions + Service Record + Certificates + Criminal Records -->
                        <div class="form-step" id="step-4">
                            <!-- Personnel Actions -->
                            <div class="section-header">{{ __('messages.personnel_actions') }}</div>
                            <div id="personnel_actions">
                                @foreach(old('personnel_actions', $employee->personnelActions ?? []) as $index => $action)
                                    @php $actionData = is_object($action) ? $action->toArray() : $action; @endphp
                                    <div class="personnel-action-entry">
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.type') }}</label>
                                            <div class="col-sm-9">
                                                <select name="personnel_actions[{{ $index }}][type]" class="form-control">
                                                    <option value="recruit" {{ ($actionData['type'] ?? '') == 'recruit' ? 'selected' : '' }}>{{ __('messages.recruit') }}</option>
                                                    <option value="promote" {{ ($actionData['type'] ?? '') == 'promote' ? 'selected' : '' }}>{{ __('messages.promote') }}</option>
                                                    <option value="demote" {{ ($actionData['type'] ?? '') == 'demote' ? 'selected' : '' }}>{{ __('messages.demote') }}</option>
                                                    <option value="transfer" {{ ($actionData['type'] ?? '') == 'transfer' ? 'selected' : '' }}>{{ __('messages.transfer') }}</option>
                                                    <option value="punishment" {{ ($actionData['type'] ?? '') == 'punishment' ? 'selected' : '' }}>{{ __('messages.punishment') }}</option>
                                                    <option value="partnership" {{ ($actionData['type'] ?? '') == 'partnership' ? 'selected' : '' }}>{{ __('messages.partnership') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.position') }}</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="personnel_actions[{{ $index }}][position]" class="form-control" value="{{ $actionData['position'] ?? '' }}">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.department') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="personnel_actions[{{ $index }}][department]" class="form-control" value="{{ $actionData['department'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.location') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="personnel_actions[{{ $index }}][location]" class="form-control" value="{{ $actionData['location'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.start_date') }}</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="personnel_actions[{{ $index }}][start_date]" class="form-control" value="{{ isset($actionData['start_date']) ? \Carbon\Carbon::parse($actionData['start_date'])->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.end_date') }}</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="personnel_actions[{{ $index }}][end_date]" class="form-control" value="{{ isset($actionData['end_date']) ? \Carbon\Carbon::parse($actionData['end_date'])->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.reason') }}</label>
                                            <div class="col-sm-9">
                                                <textarea name="personnel_actions[{{ $index }}][reason]" class="form-control">{{ $actionData['reason'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        @if(isset($actionData['id']))
                                            <input type="hidden" name="personnel_actions[{{ $index }}][id]" value="{{ $actionData['id'] }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mb-3" onclick="addPersonnelAction()">{{ __('messages.add_personnel_action') }}</button>

                            <!-- လက်ရှိဝန်ထမ်းအဖွဲ့ဝင်သည့်နေ့ (Permanent Appointment Record) -->
                            <div class="section-header">လက်ရှိဝန်ထမ်းအဖွဲ့ဝင်သည့်နေ့</div>
                            <div class="card p-4 border mb-4 bg-light">
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.grade_at_appointment') }}</label>
                                    <div class="col-sm-9">
                                        <select name="service_record[grade]" class="form-control @error('service_record.grade') is-invalid @enderror">
                                            <option value="">{{ __('messages.select_grade') }}</option>
                                            <option value="junior" {{ old('service_record.grade', $employee->serviceRecord->grade ?? '') == 'junior' ? 'selected' : '' }}>ငယ် (Junior Grade)</option>
                                            <option value="senior" {{ old('service_record.grade', $employee->serviceRecord->grade ?? '') == 'senior' ? 'selected' : '' }}>၎င်း (ကြီး) (Senior Grade)</option>
                                            <option value="selection" {{ old('service_record.grade', $employee->serviceRecord->grade ?? '') == 'selection' ? 'selected' : '' }}>၎င်း (ရွေးချယ်) (Selection Grade)</option>
                                            <option value="higher" {{ old('service_record.grade', $employee->serviceRecord->grade ?? '') == 'higher' ? 'selected' : '' }}>၎င်း (အထက်) (Higher Grade)</option>
                                        </select>
                                        @error('service_record.grade')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.employment_date') }}</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="service_record[recruited_date]" 
                                               class="form-control @error('service_record.recruited_date') is-invalid @enderror"
                                               value="{{ old('service_record.recruited_date', $employee->serviceRecord->recruited_date ? \Carbon\Carbon::parse($employee->serviceRecord->recruited_date)->format('Y-m-d') : '') }}">
                                        @error('service_record.recruited_date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted">အမြဲတမ်းဝန်ထမ်းအဖွဲ့ဝင်ဖြစ်သည့်ရက်စွဲ</small>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">{{ __('messages.remark') }}</label>
                                    <div class="col-sm-9">
                                        <textarea name="service_record[remark]" class="form-control" rows="3">{{ old('service_record.remark', $employee->serviceRecord->remark ?? '') }}</textarea>
                                    </div>
                                </div>
                                
                                @if(isset($employee->serviceRecord->id))
                                    <input type="hidden" name="service_record[id]" value="{{ $employee->serviceRecord->id }}">
                                @endif
                            </div>

                            <!-- Certificates -->
                            <div class="section-header">{{ __('messages.certificates') }}</div>
                            <div id="certificates">
                                @foreach(old('certificates', $employee->certificates ?? []) as $index => $certificate)
                                    @php $certData = is_object($certificate) ? $certificate->toArray() : $certificate; @endphp
                                    <div class="certificate-entry">
                                        @if(isset($certData['file_url']))
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __('messages.current_file') }}</label>
                                                <div class="col-sm-9">
                                                    <a href="{{ asset('storage/' . $certData['file_url']) }}" target="_blank" class="btn btn-sm btn-info">{{ __('messages.view_file') }}</a>
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" name="certificates[{{ $index }}][remove_file]" value="1">
                                                        <label class="form-check-label">{{ __('messages.remove_file') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.certificate_name') }}</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="certificates[{{ $index }}][certificate_name]" class="form-control" value="{{ $certData['certificate_name'] ?? '' }}">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.issue_date') }}</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="certificates[{{ $index }}][issue_date]" class="form-control" value="{{ isset($certData['issue_date']) ? \Carbon\Carbon::parse($certData['issue_date'])->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.issuer') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="certificates[{{ $index }}][issuer]" class="form-control" value="{{ $certData['issuer'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.description') }}</label>
                                            <div class="col-sm-9">
                                                <textarea name="certificates[{{ $index }}][description]" class="form-control">{{ $certData['description'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.attachment') }}</label>
                                            <div class="col-sm-9">
                                                <input type="file" name="certificates[{{ $index }}][file]" class="form-control" accept="image/*,application/pdf">
                                                <small class="text-muted">{{ __('messages.leave_empty_keep_current') }}</small>
                                            </div>
                                        </div>
                                        @if(isset($certData['id']))
                                            <input type="hidden" name="certificates[{{ $index }}][id]" value="{{ $certData['id'] }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mb-3" onclick="addCertificate()">{{ __('messages.add_certificate') }}</button>

                            <!-- Criminal Records -->
                            <div class="section-header">{{ __('messages.criminal_records') }}</div>
                            <div id="criminal_records">
                                @foreach(old('criminal_records', $employee->criminalRecords ?? []) as $index => $record)
                                    @php $recordData = is_object($record) ? $record->toArray() : $record; @endphp
                                    <div class="criminal-record-entry">
                                        @if(isset($recordData['file_url']))
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __('messages.current_file') }}</label>
                                                <div class="col-sm-9">
                                                    <a href="{{ asset('storage/' . $recordData['file_url']) }}" target="_blank" class="btn btn-sm btn-info">{{ __('messages.view_file') }}</a>
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" name="criminal_records[{{ $index }}][remove_file]" value="1">
                                                        <label class="form-check-label">{{ __('messages.remove_file') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.description') }}</label>
                                            <div class="col-sm-8">
                                                <textarea name="criminal_records[{{ $index }}][description]" class="form-control">{{ $recordData['description'] ?? '' }}</textarea>
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __('messages.attachment') }}</label>
                                            <div class="col-sm-9">
                                                <input type="file" name="criminal_records[{{ $index }}][file]" class="form-control" accept="image/*,application/pdf">
                                                <small class="text-muted">{{ __('messages.leave_empty_keep_current') }}</small>
                                            </div>
                                        </div>
                                        @if(isset($recordData['id']))
                                            <input type="hidden" name="criminal_records[{{ $index }}][id]" value="{{ $recordData['id'] }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mb-3" onclick="addCriminalRecord()">{{ __('messages.add_criminal_record') }}</button>

                            <div class="text-end">
                                <button type="button" class="btn btn-secondary prev-step">{{ __('messages.back') }}</button>
                                <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Indexes - set to the count of existing items
let childIndex = {{ count(old('children', $employee->children ?? [])) }};
let relativeIndex = {{ count(old('relatives', $employee->relatives ?? [])) }};
let educationIndex = {{ count(old('educations', $employee->educations ?? [])) }};
let pastExperienceIndex = {{ count(old('past_experiences', $employee->pastExperiences ?? [])) }};
let trainingIndex = {{ count(old('trainings', $employee->trainings ?? [])) }};
let experienceIndex = {{ count(old('experiences', $employee->experiences ?? [])) }};
let personnelActionIndex = {{ count(old('personnel_actions', $employee->personnelActions ?? [])) }};
let certificateIndex = {{ count(old('certificates', $employee->certificates ?? [])) }};
let criminalRecordIndex = {{ count(old('criminal_records', $employee->criminalRecords ?? [])) }};

// Add Child
function addChild() {
    const entry = `
        <div class="child-entry">
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.name') }}</label>
                <div class="col-sm-8">
                    <input type="text" name="children[${childIndex}][name]" class="form-control">
                </div>
                <div class="col-sm-1">
                    <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.date_of_birth') }}</label>
                <div class="col-sm-9">
                    <input type="date" name="children[${childIndex}][date_of_birth]" class="form-control">
                </div>
            </div>
        </div>`;
    document.getElementById('children').insertAdjacentHTML('beforeend', entry);
    childIndex++;
    bindDeleteButtons();
}

// Add Relative
function addRelative() {
    const entry = `
        <div class="relative-entry">
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.name') }}</label>
                <div class="col-sm-8">
                    <input type="text" name="relatives[${relativeIndex}][name]" class="form-control">
                </div>
                <div class="col-sm-1">
                    <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.relation') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="relatives[${relativeIndex}][relation]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.job') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="relatives[${relativeIndex}][job]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.location') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="relatives[${relativeIndex}][location]" class="form-control">
                </div>
            </div>
        </div>`;
    document.getElementById('relatives').insertAdjacentHTML('beforeend', entry);
    relativeIndex++;
    bindDeleteButtons();
}

// Add Education
function addEducation() {
    const entry = `
        <div class="education-entry">
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.type') }}</label>
                <div class="col-sm-9">
                    <select name="educations[${educationIndex}][type]" class="form-control">
                        <option value="school">{{ __('messages.school') }}</option>
                        <option value="uni">{{ __('messages.university') }}</option>
                        <option value="other">{{ __('messages.other') }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.institution_name') }}</label>
                <div class="col-sm-8">
                    <input type="text" name="educations[${educationIndex}][institution_name]" class="form-control">
                </div>
                <div class="col-sm-1">
                    <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.highest_certificate') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="educations[${educationIndex}][highest_certificate]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.field_of_study') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="educations[${educationIndex}][field_of_study]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.date') }}</label>
                <div class="col-sm-9">
                    <input type="date" name="educations[${educationIndex}][date]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.remark') }}</label>
                <div class="col-sm-9">
                    <textarea name="educations[${educationIndex}][remark]" class="form-control"></textarea>
                </div>
            </div>
        </div>`;
    document.getElementById('educations').insertAdjacentHTML('beforeend', entry);
    educationIndex++;
    bindDeleteButtons();
}

// Add Past Experience
function addPastExperience() {
    const entry = `
        <div class="past-experience-entry">
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.position') }}</label>
                <div class="col-sm-8">
                    <input type="text" name="past_experiences[${pastExperienceIndex}][position]" class="form-control">
                </div>
                <div class="col-sm-1">
                    <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.salary') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="past_experiences[${pastExperienceIndex}][salary]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.location') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="past_experiences[${pastExperienceIndex}][location]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.start_date') }}</label>
                <div class="col-sm-9">
                    <input type="date" name="past_experiences[${pastExperienceIndex}][start_date]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.end_date') }}</label>
                <div class="col-sm-9">
                    <input type="date" name="past_experiences[${pastExperienceIndex}][end_date]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.remark') }}</label>
                <div class="col-sm-9">
                    <textarea name="past_experiences[${pastExperienceIndex}][remark]" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.life_insurance') }}</label>
                <div class="col-sm-9">
                    <select name="past_experiences[${pastExperienceIndex}][life_insurance]" class="form-control">
                        <option value="no">{{ __('messages.no') }}</option>
                        <option value="yes">{{ __('messages.yes') }}</option>
                    </select>
                </div>
            </div>
        </div>`;
    document.getElementById('past_experiences').insertAdjacentHTML('beforeend', entry);
    pastExperienceIndex++;
    bindDeleteButtons();
}

// Add Training
function addTraining() {
    const entry = `
        <div class="training-entry">
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.training_type') }}</label>
                <div class="col-sm-9">
                    <select name="trainings[${trainingIndex}][training_type]" class="form-control">
                        <option value="domestic">{{ __('messages.domestic') }}</option>
                        <option value="foreign">{{ __('messages.foreign') }}</option>
                        <option value="other">{{ __('messages.other') }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.course_name') }}</label>
                <div class="col-sm-8">
                    <input type="text" name="trainings[${trainingIndex}][course_name]" class="form-control">
                </div>
                <div class="col-sm-1">
                    <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.location') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="trainings[${trainingIndex}][location]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.start_date') }}</label>
                <div class="col-sm-9">
                    <input type="date" name="trainings[${trainingIndex}][start_date]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.end_date') }}</label>
                <div class="col-sm-9">
                    <input type="date" name="trainings[${trainingIndex}][end_date]" class="form-control">
                </div>
            </div>
        </div>`;
    document.getElementById('trainings').insertAdjacentHTML('beforeend', entry);
    trainingIndex++;
    bindDeleteButtons();
}

// Add Current Company Experience
function addExperience() {
    const entry = `
        <div class="experience-entry">
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.position') }}</label>
                <div class="col-sm-8">
                    <input type="text" name="experiences[${experienceIndex}][position]" class="form-control">
                </div>
                <div class="col-sm-1">
                    <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.department') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="experiences[${experienceIndex}][department]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.from_date') }}</label>
                <div class="col-sm-9">
                    <input type="date" name="experiences[${experienceIndex}][from_date]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.to_date') }}</label>
                <div class="col-sm-9">
                    <input type="date" name="experiences[${experienceIndex}][to_date]" class="form-control to-date">
                    <input type="hidden" name="experiences[${experienceIndex}][is_current]" value="0">
                    <label><input type="checkbox" class="is-current" name="experiences[${experienceIndex}][is_current]" value="1"> {{ __('messages.currently') }}</label>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.location') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="experiences[${experienceIndex}][location]" class="form-control">
                </div>
            </div>
        </div>`;
    document.getElementById('experiences').insertAdjacentHTML('beforeend', entry);
    experienceIndex++;
    bindDeleteButtons();
    bindCurrentCheckboxes();
}

// Add Personnel Action
function addPersonnelAction() {
    const entry = `
        <div class="personnel-action-entry">
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.type') }}</label>
                <div class="col-sm-9">
                    <select name="personnel_actions[${personnelActionIndex}][type]" class="form-control">
                        <option value="recruit">{{ __('messages.recruit') }}</option>
                        <option value="promote">{{ __('messages.promote') }}</option>
                        <option value="demote">{{ __('messages.demote') }}</option>
                        <option value="transfer">{{ __('messages.transfer') }}</option>
                        <option value="punishment">{{ __('messages.punishment') }}</option>
                        <option value="partnership">{{ __('messages.partnership') }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.position') }}</label>
                <div class="col-sm-8">
                    <input type="text" name="personnel_actions[${personnelActionIndex}][position]" class="form-control">
                </div>
                <div class="col-sm-1">
                    <button type="button" class="btn btn-sm btn-danger delete-entry">{{ __('messages.delete') }}</button>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.department') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="personnel_actions[${personnelActionIndex}][department]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.location') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="personnel_actions[${personnelActionIndex}][location]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.start_date') }}</label>
                <div class="col-sm-9">
                    <input type="date" name="personnel_actions[${personnelActionIndex}][start_date]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.end_date') }}</label>
                <div class="col-sm-9">
                    <input type="date" name="personnel_actions[${personnelActionIndex}][end_date]" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.reason') }}</label>
                <div class="col-sm-9">
                    <textarea name="personnel_actions[${personnelActionIndex}][reason]" class="form-control"></textarea>
                </div>
            </div>
        </div>`;
    document.getElementById('personnel_actions').insertAdjacentHTML('beforeend', entry);
    personnelActionIndex++;
    bindDeleteButtons();
}

// Add Certificate
function addCertificate() {
    const entry = `
        <div class="certificate-entry">
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label">{{ __('messages.certificate_name') }}</label>
                <div class="col-sm-8">
                    <input type="text" name="certificates[${certificateIndex}][certificate_name]" class="form-control">
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
                <label class="col-sm-3 col-form-label">{{ __('messages.attachment') }}</label>
                <div class="col-sm-9">
                    <input type="file" name="certificates[${certificateIndex}][file]" class="form-control" accept="image/*,application/pdf">
                </div>
            </div>
        </div>`;
    document.getElementById('certificates').insertAdjacentHTML('beforeend', entry);
    certificateIndex++;
    bindDeleteButtons();
}

// Add Criminal Record
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
                </div>
            </div>
        </div>`;
    document.getElementById('criminal_records').insertAdjacentHTML('beforeend', entry);
    criminalRecordIndex++;
    bindDeleteButtons();
}

// Delete entry
function bindDeleteButtons() {
    document.querySelectorAll('.delete-entry').forEach(button => {
        button.onclick = function() {
            this.closest('.child-entry, .relative-entry, .education-entry, .past-experience-entry, .training-entry, .experience-entry, .personnel-action-entry, .certificate-entry, .criminal-record-entry').remove();
        };
    });
}

// Current checkbox for experience
function bindCurrentCheckboxes() {
    document.querySelectorAll('.is-current').forEach(checkbox => {
        checkbox.onchange = function() {
            const toDate = this.closest('.form-group').querySelector('.to-date');
            toDate.disabled = this.checked;
            if (this.checked) toDate.value = '';
        };
    });
}

// Step navigation
document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.form-step');
    const nextButtons = document.querySelectorAll('.next-step');
    const prevButtons = document.querySelectorAll('.prev-step');
    let currentStep = 0;

    function showStep(index) {
        steps.forEach((step, i) => step.classList.toggle('active', i === index));
    }

    nextButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        });
    });

    prevButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });

    bindDeleteButtons();
    bindCurrentCheckboxes();
});
</script>
@endsection