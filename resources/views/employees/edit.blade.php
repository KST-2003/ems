@extends('layouts.app')

@section('css')
    <style>
        .tab-content {
            padding: 30px;
            border: 1px solid #dee2e6;
            border-top: none;
            background: #fff;
            border-radius: 0 0 8px 8px;
        }

        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 600;
            padding: 12px 20px;
            cursor: pointer;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
            background-color: #f8f9fa;
        }

        .section-header {
            margin: 30px 0 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #ebeef4;
            color: #012970;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .repeater-card {
            border-left: 4px solid #ffc107;
            background: #f9f9f9;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .delete-entry {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .sticky-actions {
            position: sticky;
            bottom: 0;
            background: #fff;
            padding: 20px;
            border-top: 1px solid #dee2e6;
            z-index: 100;
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }

        .current-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #eee;
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>{{ __('messages.edit_employee') }}: {{ $employee->name }}</h1>
    </div>

    <section class="section">
        <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data"
            id="employeeForm">
            @csrf
            @method('PUT')

            <div class="card shadow-sm">
                <div class="card-body pt-3">
                    <ul class="nav nav-tabs nav-tabs-bordered" id="employeeTab" role="tablist">
                        <li class="nav-item"><button type="button" class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#personal">၁။ ကိုယ်ရေးအချက်အလက်</button></li>
                        <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#family">၂။ မိသားစုဝင်များ</button></li>
                        <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#education">၃။ ပညာအရည်အချင်းနှင့် အတွေ့အကြုံ</button></li>
                        <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#service">၄။ တာဝန်ထမ်းဆောင်မှုမှတ်တမ်း</button></li>
                        <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#legal">၅။ ကိုယ်ရေးမှတ်တမ်းအကျဉ်း</button></li>
                    </ul>

                    <div class="tab-content pt-2">
                        <div class="tab-pane fade show active" id="personal">
                            <div class="section-header">အခြေခံအချက်အလက်များ</div>
                            <div class="row align-items-center mb-4">
                                <div class="col-md-2 text-center">
                                    <img src="{{ $employee->profile_image_url }}" class="current-img">
                                    <input type="file" name="profile_image" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-4 mb-3"><label class="form-label">ဝန်ထမ်းအမှတ် (ID)</label>
                                            <input type="text" name="employee_id" class="form-control"
                                                value="{{ old('employee_id', $employee->employee_id) }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3"><label class="form-label">အမည်</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name', $employee->name) }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3"><label class="form-label">ကျား/မ</label>
                                            <select name="gender" class="form-select">
                                                <option value="male"
                                                    {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>ကျား
                                                </option>
                                                <option value="female"
                                                    {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>မ
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3"><label class="form-label">ဖုန်းနံပါတ်</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ old('phone', $employee->phone) }}">
                                        </div>
                                        <div class="col-md-6 mb-3"><label class="form-label">NRC နံပါတ်</label>
                                            <input type="text" name="nrc" class="form-control"
                                                value="{{ old('nrc', $employee->nrc) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3"><label class="form-label">မွေးသက္ကရာဇ် (Eng)</label>
                                    <input type="date" name="eng_dob" class="form-control"
                                        value="{{ old('eng_dob', $employee->eng_dob ? $employee->eng_dob->format('Y-m-d') : '') }}">
                                </div>
                                <div class="col-md-4 mb-3"><label class="form-label">လူမျိုး/ဘာသာ</label>
                                    <input type="text" name="nationality" class="form-control"
                                        value="{{ old('nationality', $employee->nationality) }}">
                                </div>
                                <div class="col-md-4 mb-3"><label class="form-label">သွေးအုပ်စု</label>
                                    <input type="text" name="blood_type" class="form-control"
                                        value="{{ old('blood_type', $employee->blood_type) }}">
                                </div>
                            </div>

                            <div class="section-header">ကိုယ်ကာယအချက်အလက်များ</div>
                            <div class="row">
                                <div class="col-md-3 mb-3"><label class="form-label">အရပ်အမြင့်</label><input
                                        type="text" name="height" class="form-control"
                                        value="{{ old('height', $employee->height) }}"></div>
                                <div class="col-md-3 mb-3"><label class="form-label">ကိုယ်အလေးချိန်</label><input
                                        type="text" name="weight" class="form-control"
                                        value="{{ old('weight', $employee->weight) }}"></div>
                                <div class="col-md-3 mb-3"><label class="form-label">ဆံပင်အရောင်</label><input
                                        type="text" name="hair_color" class="form-control"
                                        value="{{ old('hair_color', $employee->hair_color) }}"></div>
                                <div class="col-md-3 mb-3"><label class="form-label">မျက်နှာအသွင်အပြင်</label><input
                                        type="text" name="notable_trade" class="form-control"
                                        value="{{ old('notable_trade', $employee->notable_trade) }}"></div>
                            </div>

                            <div class="section-header">နေရပ်လိပ်စာ</div>
                            <div class="row">
                                <div class="col-md-6 mb-3"><label class="form-label">လက်ရှိနေရပ်</label>
                                    <textarea name="current_address" class="form-control" rows="2">{{ old('current_address', $employee->current_address) }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3"><label class="form-label">အမြဲတမ်းနေရပ်</label>
                                    <textarea name="permanent_address" class="form-control" rows="2">{{ old('permanent_address', $employee->permanent_address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="family">
                            <div class="section-header">မိဘများ အချက်အလက်</div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card p-3 border"><label class="fw-bold border-bottom mb-2">ဖခင်</label>
                                        <input type="text" name="father_name" class="form-control mb-1"
                                            placeholder="အမည်" value="{{ old('father_name', $employee->father_name) }}">
                                        <input type="text" name="father_job" class="form-control mb-1"
                                            placeholder="အလုပ်" value="{{ old('father_job', $employee->father_job) }}">
                                        <textarea name="father_address" class="form-control" placeholder="နေရပ်လိပ်စာ">{{ old('father_address', $employee->father_address) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card p-3 border"><label class="fw-bold border-bottom mb-2">မိခင်</label>
                                        <input type="text" name="mother_name" class="form-control mb-1"
                                            placeholder="အမည်" value="{{ old('mother_name', $employee->mother_name) }}">
                                        <input type="text" name="mother_job" class="form-control mb-1"
                                            placeholder="အလုပ်" value="{{ old('mother_job', $employee->mother_job) }}">
                                        <textarea name="mother_address" class="form-control" placeholder="နေရပ်လိပ်စာ">{{ old('mother_address', $employee->mother_address) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="section-header">အိမ်ထောင်ဖက်</div>
                            <div class="row card p-3 m-0 border shadow-sm">
                                <div class="row">
                                    <div class="col-md-4 mb-3"><label>အမည်</label><input type="text"
                                            name="spouse_name" class="form-control"
                                            value="{{ old('spouse_name', $employee->spouse->name ?? '') }}"></div>
                                    <div class="col-md-4 mb-3"><label>အလုပ်</label><input type="text"
                                            name="spouse_job" class="form-control"
                                            value="{{ old('spouse_job', $employee->spouse->job ?? '') }}"></div>
                                    <div class="col-md-4 mb-3"><label>နေရပ်</label><input type="text"
                                            name="spouse_job_place" class="form-control"
                                            value="{{ old('spouse_job_place', $employee->spouse->hometown ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="section-header">သား/သမီးများ</div>
                            <div id="children-container">
                                @foreach (old('children', $employee->children) as $index => $child)
                                    <div class="repeater-card">
                                        <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                                        <div class="row">
                                            <div class="col-md-6"><label class="small">အမည်</label><input type="text"
                                                    name="children[{{ $index }}][name]" class="form-control"
                                                    value="{{ is_array($child) ? $child['name'] : $child->name }}"></div>
                                            <div class="col-md-6">
                                                <label class="small">မွေးသက္ကရာဇ်</label>
                                                <input type="date" name="children[{{ $index }}][date_of_birth]"
                                                    class="form-control"
                                                    value="{{ is_array($child) ? $child['date_of_birth'] ?? '' : ($child->date_of_birth ? \Carbon\Carbon::parse($child->date_of_birth)->format('Y-m-d') : '') }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addRepeater('children')">+ သား/သမီး ထည့်ရန်</button>

                            <div class="section-header">မိဘနှစ်ပါး၏ ညီအစ်ကို/မောင်နှမများ</div>
                            <div id="family_tree-container">
                                @foreach (old('family_tree', $employee->parentSiblings) as $index => $sibling)
                                    <div class="repeater-card">
                                        <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                                        <div class="row">
                                            <div class="col-md-2"><label class="small text-muted">ဘက်</label>
                                                <select name="family_tree[{{ $index }}][side]"
                                                    class="form-select">
                                                    <option value="father"
                                                        {{ (is_array($sibling) ? $sibling['side'] : $sibling->side) == 'father' ? 'selected' : '' }}>
                                                        ဖခင်ဘက်</option>
                                                    <option value="mother"
                                                        {{ (is_array($sibling) ? $sibling['side'] : $sibling->side) == 'mother' ? 'selected' : '' }}>
                                                        မိခင်ဘက်</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3"><label class="small text-muted">အမည်</label><input
                                                    type="text" name="family_tree[{{ $index }}][name]"
                                                    class="form-control"
                                                    value="{{ is_array($sibling) ? $sibling['name'] : $sibling->name }}">
                                            </div>
                                            <div class="col-md-4 mb-2"><label class="small">လူမျိုး/ဘာသာ</label>
                                                <input type="text"
                                                    name="family_tree[{{ $index }}][nationality_religion]"
                                                    class="form-control"
                                                    value="{{ is_array($sibling) ? $sibling['nationality_religion'] ?? '' : $sibling->nationality_religion ?? '' }}">
                                            </div>
                                            <div class="col-md-3 mb-2"><label class="small">မွေးရပ်ဇာတိ</label>
                                                <input type="text" name="family_tree[{{ $index }}][hometown]"
                                                    class="form-control"
                                                    value="{{ is_array($sibling) ? $sibling['hometown'] ?? '' : $sibling->hometown ?? '' }}">
                                            </div>
                                            <div class="col-md-2"><label class="small text-muted">တော်စပ်ပုံ</label><input
                                                    type="text" name="family_tree[{{ $index }}][relation]"
                                                    class="form-control"
                                                    value="{{ is_array($sibling) ? $sibling['relation'] : $sibling->relation }}">
                                            </div>
                                            <div class="col-md-2"><label class="small text-muted">အလုပ်</label><input
                                                    type="text" name="family_tree[{{ $index }}][job]"
                                                    class="form-control"
                                                    value="{{ is_array($sibling) ? $sibling['job'] : $sibling->job }}">
                                            </div>
                                            <div class="col-md-3"><label class="small text-muted">နေရပ်</label><input
                                                    type="text" name="family_tree[{{ $index }}][location]"
                                                    class="form-control"
                                                    value="{{ is_array($sibling) ? $sibling['location'] : $sibling->location }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addRepeater('family_tree')">+ ဆွေမျိုး ထည့်ရန်</button>
                        </div>



                       <div class="tab-pane fade" id="education">
    <div class="section-header">ပညာအရည်အချင်း (Education)</div>
    <div id="educations-container">
        @foreach (old('educations', $employee->educations) as $index => $edu)
            <div class="repeater-card">
                <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label class="small">အမျိုးအစား</label>
                        <select name="educations[{{ $index }}][type]" class="form-select">
                            <option value="school" {{ (is_array($edu) ? $edu['type'] : $edu->type) == 'school' ? 'selected' : '' }}>ကျောင်း</option>
                            <option value="uni" {{ (is_array($edu) ? $edu['type'] : $edu->type) == 'uni' ? 'selected' : '' }}>တက္ကသိုလ်</option>
                            <option value="other" {{ (is_array($edu) ? $edu['type'] : $edu->type) == 'other' ? 'selected' : '' }}>အခြား</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small">ကျောင်း/တက္ကသိုလ်အမည်</label>
                        <input type="text" name="educations[{{ $index }}][institution_name]" class="form-control" value="{{ is_array($edu) ? $edu['institution_name'] : $edu->institution_name }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small">ပညာအဆင့်</label>
                        <input type="text" name="educations[{{ $index }}][degree_certificate]" class="form-control" value="{{ is_array($edu) ? $edu['degree_certificate'] ?? '' : $edu->degree_certificate ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small">အထူးပြုဘာသာ (Field)</label>
                        <input type="text" name="educations[{{ $index }}][field_of_study]" class="form-control" value="{{ is_array($edu) ? $edu['field_of_study'] ?? '' : $edu->field_of_study ?? '' }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="small">ရက်စွဲ</label>
                        <input type="date" name="educations[{{ $index }}][date]" class="form-control" value="{{ is_array($edu) ? $edu['date'] ?? '' : ($edu->date ? \Carbon\Carbon::parse($edu->date)->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-9">
                        <label class="small">မှတ်ချက် (Remark)</label>
                        <input type="text" name="educations[{{ $index }}][remark]" class="form-control" value="{{ is_array($edu) ? $edu['remark'] ?? '' : $edu->remark ?? '' }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary mb-4" onclick="addRepeater('educations')">+ ပညာအရည်အချင်း ထည့်ရန်</button>

    <div class="section-header">ပြည်တွင်းပြည်ပ သင်တန်းများတက်ရောက်မှု</div>
    <div id="trainings-container">
        @foreach (old('trainings', $employee->trainings) as $index => $training)
            <div class="repeater-card">
                <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label class="small">အမျိုးအစား</label>
                        <select name="trainings[{{ $index }}][training_type]" class="form-select">
                            <option value="domestic" {{ (is_array($training) ? $training['training_type'] : $training->training_type) == 'domestic' ? 'selected' : '' }}>ပြည်တွင်း</option>
                            <option value="foreign" {{ (is_array($training) ? $training['training_type'] : $training->training_type) == 'foreign' ? 'selected' : '' }}>ပြည်ပ</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="small">သင်တန်းအမည်</label>
                        <input type="text" name="trainings[{{ $index }}][course_name]" class="form-control" value="{{ is_array($training) ? $training['course_name'] : $training->course_name }}">
                    </div>
                    <div class="col-md-4">
                        <label class="small">နေရာ</label>
                        <input type="text" name="trainings[{{ $index }}][location]" class="form-control" value="{{ is_array($training) ? $training['location'] : $training->location }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="small">စတင်သည့်ရက်</label>
                        <input type="date" name="trainings[{{ $index }}][start_date]" class="form-control" value="{{ is_array($training) ? $training['start_date'] : ($training->start_date ? \Carbon\Carbon::parse($training->start_date)->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="small">ပြီးဆုံးသည့်ရက်</label>
                        <input type="date" name="trainings[{{ $index }}][end_date]" class="form-control" value="{{ is_array($training) ? $training['end_date'] : ($training->end_date ? \Carbon\Carbon::parse($training->end_date)->format('Y-m-d') : '') }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary mb-4" onclick="addRepeater('trainings')">+ သင်တန်းမှတ်တမ်း ထည့်ရန်</button>

    <div class="section-header">ချီးမြှင့်ခံရသည့် ဘွဲ့ထူး၊ ဂုဏ်ထူးတံဆိပ်များ</div>
    <div id="certificates-container">
        @foreach (old('certificates', $employee->certificates) as $index => $cert)
            <div class="repeater-card">
                <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label class="small">လက်မှတ်အမည်</label>
                        <input type="text" name="certificates[{{ $index }}][certificate_name]" class="form-control" value="{{ is_array($cert) ? $cert['certificate_name'] : $cert->certificate_name }}">
                    </div>
                    <div class="col-md-4">
                        <label class="small">ထုတ်ပေးသည့်ရက်</label>
                        <input type="date" name="certificates[{{ $index }}][issue_date]" class="form-control" value="{{ is_array($cert) ? $cert['issue_date'] : ($cert->issue_date ? \Carbon\Carbon::parse($cert->issue_date)->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="small">ထုတ်ပေးသည့်ဌာန</label>
                        <input type="text" name="certificates[{{ $index }}][issuer]" class="form-control" value="{{ is_array($cert) ? $cert['issuer'] : $cert->issuer }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <label class="small">အကြောင်းအရာ</label>
                        <input type="text" name="certificates[{{ $index }}][description]" class="form-control" value="{{ is_array($cert) ? $cert['description'] : $cert->description }}">
                    </div>
                    <div class="col-md-4">
                        <label class="small">ဖိုင်အဟောင်း</label>
                        @if($cert->file_path)
                            <div class="small text-muted"><a href="{{ asset('storage/certificates/'.$cert->file_path) }}" target="_blank">View File</a></div>
                        @endif
                        <input type="file" name="certificates[{{ $index }}][file]" class="form-control">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary mb-4" onclick="addRepeater('certificates')">+ လက်မှတ် ထည့်ရန်</button>
</div>




                        {{-- {{ $employee }} --}}
                        <div class="tab-pane fade" id="service">
                            <div class="section-header">နိုင်ငံ့ဝန်ထမ်း တာဝန်ထမ်းဆောင်မှု (လက်ရှိဌာန)</div>
                            <div id="experiences-container">
                                @foreach (old('experiences', $employee->experiences) as $index => $exp)
                                    <div class="repeater-card">
                                        <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>

                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <label class="small">ရာထူး (Position)</label>
                                                <input type="text" name="experiences[{{ $index }}][position]"
                                                    class="form-control"
                                                    value="{{ is_array($exp) ? $exp['position'] ?? '' : $exp->position }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small">ဌာန (Department)</label>
                                                <input type="text" name="experiences[{{ $index }}][department]"
                                                    class="form-control"
                                                    value="{{ is_array($exp) ? $exp['department'] ?? '' : $exp->department }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small">တည်နေရာ (Location)</label>
                                                <input type="text" name="experiences[{{ $index }}][location]"
                                                    class="form-control"
                                                    value="{{ is_array($exp) ? $exp['location'] ?? '' : $exp->location }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="small">မှ (From Date)</label>
                                                <input type="date" name="experiences[{{ $index }}][from_date]"
                                                    class="form-control"
                                                    value="{{ is_array($exp) ? $exp['from_date'] ?? '' : ($exp->from_date ? \Carbon\Carbon::parse($exp->from_date)->format('Y-m-d') : '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small">ထိ (To Date)</label>
                                                <input type="date" name="experiences[{{ $index }}][to_date]"
                                                    class="form-control to-date-field"
                                                    value="{{ is_array($exp) ? $exp['to_date'] ?? '' : ($exp->to_date ? \Carbon\Carbon::parse($exp->to_date)->format('Y-m-d') : '') }}"
                                                    {{ (is_array($exp) ? $exp['is_current'] ?? false : $exp->is_current) ? 'disabled' : '' }}>
                                            </div>
                                            <div class="col-md-4 pt-4">
                                                <div class="form-check">
                                                    <input type="hidden"
                                                        name="experiences[{{ $index }}][is_current]"
                                                        value="0">
                                                    <input type="checkbox" class="form-check-input is-current-check"
                                                        name="experiences[{{ $index }}][is_current]"
                                                        value="1"
                                                        {{ (is_array($exp) ? $exp['is_current'] ?? false : $exp->is_current) ? 'checked' : '' }}>
                                                    <label class="form-check-label small">လက်ရှိထမ်းဆောင်ဆဲ</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addRepeater('experiences')">+ တာဝန်ထမ်းဆောင်မှု ထည့်ရန်</button>

                            <div class="section-header">အမြဲတမ်းဝန်ထမ်း ခန့်အပ်မှုအချက်အလက်</div>
                            <div class="row card p-3 m-0 bg-light border">
                                <div class="col-md-6 mb-3"><label class="form-label">ခန့်အပ်သည့်အဆင့်</label>
                                    <select name="service_record[grade]" class="form-select">
                                        <option value="junior"
                                            {{ old('service_record.grade', $employee->serviceRecord->grade ?? '') == 'junior' ? 'selected' : '' }}>
                                            ငယ် (Junior Grade)</option>
                                        <option value="senior"
                                            {{ old('service_record.grade', $employee->serviceRecord->grade ?? '') == 'senior' ? 'selected' : '' }}>
                                            ကြီး (Senior Grade)</option>
                                        <option value="selection"
                                            {{ old('service_record.grade', $employee->serviceRecord->grade ?? '') == 'selection' ? 'selected' : '' }}>
                                            ရွေးချယ် (Selection Grade)</option>
                                        <option value="higher"
                                            {{ old('service_record.grade', $employee->serviceRecord->grade ?? '') == 'higher' ? 'selected' : '' }}>
                                            အထက် (Higher Grade)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3"><label class="form-label">ခန့်အပ်သည့်ရက်စွဲ</label>
                                    <input type="date" name="service_record[recruited_date]" class="form-control"
                                        value="{{ old('service_record.recruited_date', $employee->serviceRecord && $employee->serviceRecord->recruited_date ? $employee->serviceRecord->recruited_date->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="legal">
                            <div class="section-header">ငယ်စဉ်မှယခုအချိန်ထိ ကိုယ်ရေးရာဇဝင် (Personal History)</div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">၁။ နေခဲ့ဖူးသောကျောင်းများ (ခုနှစ်သက္ကရာဇ်ဖော်ပြရန်)</label>
                                    <textarea name="schools" class="form-control" rows="3">{{ old('schools', $employee->personalRecord->schools ?? '') }}</textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">၂။ နောက်ဆုံးအောင်မြင်ခဲ့သည့်ကျောင်း/အတန်း၊ ခုံအမှတ်၊
                                        ဘာသာရပ်အလိုက်အကျဉ်းဖော်ပြရန်</label>
                                    <textarea name="latest_school" class="form-control" rows="2">{{ old('latest_school', $employee->personalRecord->latest_school ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">၃။ ကျောင်းသားဘဝတွင်
                                        နိုင်ငံရေး/မြို့ရေး/ရွာရေးဆောင်ရွက်မှုများနှင့်အဆင့်အတန်း၊ တာဝန်</label>
                                    <textarea name="school_voluntary" class="form-control" rows="2">{{ old('school_voluntary', $employee->personalRecord->school_voluntary ?? '') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">၄။ ဝါသနာပါပြီးလေ့လာလိုက်စားခဲ့သော ကျန်းမာရေး၊
                                        ကစားခုန်စားမှုများ၊ အနုပညာဆိုင်ရာ အတီးအမှုတ်များ၊ ပညာရေး၊ စက်မှုလက်မှု</label>
                                    <textarea name="hobbies" class="form-control" rows="2">{{ old('hobbies', $employee->personalRecord->hobbies ?? '') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">၅။ လုပ်ကိုင်ခဲ့သော အလုပ်အကိုင်များနှင့်ဌာန/မြို့နယ်</label>
                                    <input type="text" name="jobs_dept" class="form-control"
                                        value="{{ old('jobs_dept', $employee->personalRecord->jobs_dept ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">၆။ တောခိုခဲ့ဖူးလျှင် (သို့) သောင်းကျန်းသူများကြီးစိုးသော
                                        နယ်မြေတွင်နေခဲ့ဖူးလျှင် လုပ်ကိုင်ဆောင်ရွက်ချက်များကိုဖော်ပြပါ</label>
                                    <textarea name="refugee" class="form-control" rows="2">{{ old('refugee', $employee->personalRecord->refugee ?? '') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">၇။ အလုပ်အကိုင်ပြောင်းရွှေ့ခဲ့သော
                                        အကြောင်းအကျိုးနှင့်လစာ</label>
                                    <textarea name="jobtransfer_desc" class="form-control" rows="2">{{ old('jobtransfer_desc', $employee->personalRecord->jobtransfer_desc ?? '') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">၈။ အမှုထမ်းနေစဉ် (သို့) ကိုယ်ပိုင်အလုပ်အကိုင်ဆောင်ရွက်နေစဉ်
                                        နိုင်ငံရေး၊ မြို့/ရွာရေး ဆောင်ရွက်မှုများ၊ ဆောင်ရွက်နေစဉ်
                                        အဆင့်အတန်းနှင့်တာဝန်</label>
                                    <textarea name="citizen_duties" class="form-control" rows="2">{{ old('citizen_duties', $employee->personalRecord->citizen_duties ?? '') }}</textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">၉။ စစ်ဘက်/နယ်ဘက်/ရဲဘက်နှင့် နိုင်ငံရေးဘက်တွင်
                                        ခင်မင်ရင်းနှီးသော မိတ်ဆွေများ ရှိ မရှိ</label>
                                    <input type="text" name="relatives_officials" class="form-control"
                                        value="{{ old('relatives_officials', $employee->personalRecord->relatives_officials ?? '') }}">
                                </div>
                            </div>

                            <div class="section-header">၁၀။ နိုင်ငံခြားသို့ သွားရောက်ခဲ့ဖူးလျှင်</div>
                            <div id="abroads-container">
                                @foreach (old('abroads', $employee->abroads) as $index => $ab)
                                    <div class="repeater-card">
                                        <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <label class="small">သွားရောက်ခဲ့သည့်နိုင်ငံ</label>
                                                <input type="text" name="abroads[{{ $index }}][country]"
                                                    class="form-control"
                                                    value="{{ is_array($ab) ? $ab['country'] ?? '' : $ab->country }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small">အကြောင်းရင်း</label>
                                                <input type="text" name="abroads[{{ $index }}][reason]"
                                                    class="form-control"
                                                    value="{{ is_array($ab) ? $ab['reason'] ?? '' : $ab->reason }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small">တွေ့ဆုံခဲ့သူ</label>
                                                <input type="text" name="abroads[{{ $index }}][host_name]"
                                                    class="form-control"
                                                    value="{{ is_array($ab) ? $ab['host_name'] ?? '' : $ab->host_name }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="small">သွားသည့်နေ့</label>
                                                <input type="date"
                                                    name="abroads[{{ $index }}][departure_date]"
                                                    class="form-control"
                                                    value="{{ is_array($ab) ? $ab['departure_date'] ?? '' : ($ab->departure_date ? $ab->departure_date->format('Y-m-d') : '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small">ပြန်သည့်နေ့</label>
                                                <input type="date" name="abroads[{{ $index }}][arrival_date]"
                                                    class="form-control"
                                                    value="{{ is_array($ab) ? $ab['arrival_date'] ?? '' : ($ab->arrival_date ? $ab->arrival_date->format('Y-m-d') : '') }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-4"
                                onclick="addRepeater('abroads')">+ ပြည်ပခရီးစဉ် ထည့်ရန်</button>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">၁၁။ မိမိနှင့်ခင်မင်ရင်းနှီးသောနိုင်ငံခြားသားရှိမရှိ၊
                                        ရှိကမည်သည့်အလုပ်အကိုင်၊ လူမျိုး၊ တိုင်းပြည်၊ မည်ကဲ့သို့ရင်းနှီးသည်</label>
                                    <textarea name="foreign_friends_desc" class="form-control" rows="2">{{ old('foreign_friends_desc', $employee->personalRecord->foreign_friends_desc ?? '') }}</textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">၁၂။ မိမိအားထောက်ခံသည့်ပုဂ္ဂိုလ် (စစ်ဘက်/နယ်ဘက်အရာရှိ၊
                                        မြို့နယ်/ကျေးရွာ/ရပ်ကွက်အုပ်ချုပ်ရေးမှူး)</label>
                                    <input type="text" name="referal_officials" class="form-control"
                                        value="{{ old('referal_officials', $employee->personalRecord->referal_officials ?? '') }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="card p-3 bg-light border">
                                        <label class="form-label fw-bold">၁၃။ ရာဇဝတ်ပြစ်မှုခံရခြင်း ရှိ/မရှိ</label>
                                        <div class="form-check">
                                            <input type="hidden" name="has_criminal_rec" value="0">
                                            <input type="checkbox" name="has_criminal_rec" class="form-check-input"
                                                value="1"
                                                {{ old('has_criminal_rec', $employee->personalRecord->has_criminal_rec ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label">ရာဇဝတ်ပြစ်မှု ရှိပါသည်</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addRepeater('certificates')">+ လက်မှတ် ထည့်ရန်</button>
                        </div>
                    </div>
                </div>

                <div class="sticky-actions text-center">
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-lg px-4 me-3">Cancel</a>
                    <button type="submit" class="btn btn-warning btn-lg px-5 shadow">Update Employee Info</button>
                </div>
            </div>
        </form>
    </section>
@endsection


<script>
    // counters must be global and set to the larger of current database count or old input count
    var counters = {
        children: {{ max($employee->children->count(), count(old('children', []))) }},
        educations: {{ max($employee->educations->count(), count(old('educations', []))) }},
        past_experiences: {{ max($employee->pastExperiences->count(), count(old('past_experiences', []))) }},
        trainings: {{ max($employee->trainings->count(), count(old('trainings', []))) }},
        experiences: {{ max($employee->experiences->count(), count(old('experiences', []))) }},
        personnel_actions: {{ max($employee->personnelActions->count(), count(old('personnel_actions', []))) }},
        certificates: {{ max($employee->certificates->count(), count(old('certificates', []))) }},
        family_tree: {{ max($employee->parentSiblings->count(), count(old('family_tree', []))) }},
        abroads: {{ max($employee->abroads->count(), count(old('abroads', []))) }}
    };


    function bindCurrentCheckboxes() {
        // Listen for changes on all "Currently Working" checkboxes
        document.querySelectorAll('.is-current-check').forEach(checkbox => {
            checkbox.onchange = function() {
                // Find the "To Date" field in the same repeater card
                const toDateField = this.closest('.repeater-card').querySelector('.to-date-field');

                if (this.checked) {
                    toDateField.disabled = true;
                    toDateField.value = ''; // Clear the date if it's current
                } else {
                    toDateField.disabled = false;
                }
            };
        });
    }



    function addRepeater(type) {
        var index = counters[type]++;
        var container = document.getElementById(type + '-container');
        bindCurrentCheckboxes();
        if (!container) return;

        var html = '';
        if (type === 'children') {
            html =
                `<div class="repeater-card"><button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row"><div class="col-md-6"><label class="small">အမည်</label><input type="text" name="children[${index}][name]" class="form-control"></div>
                <div class="col-md-6"><label class="small">မွေးသက္ကရာဇ်</label><input type="date" name="children[${index}][date_of_birth]" class="form-control"></div></div></div>`;
        } else if (type === 'family_tree') {
            html =
                `<div class="repeater-card"><button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row"><div class="col-md-2"><label class="small">ဘက်</label><select name="family_tree[${index}][side]" class="form-select"><option value="father">ဖခင်</option><option value="mother">မိခင်</option></select></div>
                <div class="col-md-3"><label class="small">အမည်</label><input type="text" name="family_tree[${index}][name]" class="form-control"></div>
                <div class="col-md-4 mb-2"><label class="small">လူမျိုး/ဘာသာ</label><input type="text" name="family_tree[${index}][nationality_religion]" class="form-control"></div>
                <div class="col-md-3 mb-2"><label class="small">မွေးရပ်ဇာတိ</label><input type="text" name="family_tree[${index}][hometown]" class="form-control"></div>
                <div class="col-md-2"><label class="small">တော်စပ်ပုံ</label><input type="text" name="family_tree[${index}][relation]" class="form-control"></div>
                <div class="col-md-2"><label class="small">အလုပ်</label><input type="text" name="family_tree[${index}][job]" class="form-control"></div>
                <div class="col-md-3"><label class="small">နေရပ်</label><input type="text" name="family_tree[${index}][location]" class="form-control"></div></div></div>`;
        } else if (type === 'educations') {
            html =
                `<div class="repeater-card"><button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row"><div class="col-md-3"><label class="small">အမျိုးအစား</label><select name="educations[${index}][type]" class="form-select"><option value="school">ကျောင်း</option><option value="uni">တက္ကသိုလ်</option></select></div>
                <div class="col-md-3"><label class="small">ကျောင်းအမည်</label><input type="text" name="educations[${index}][institution_name]" class="form-control"></div>
                <div class="col-md-3"><label class="small">ပညာအဆင့်</label><input type="text" name="educations[${index}][degree_certificate]" class="form-control"></div>
                <div class="col-md-3"><label class="small">ရက်စွဲ</label><input type="date" name="educations[${index}][date]" class="form-control"></div></div></div>`;
        } else if (type === 'experiences') {
            html = `<div class="repeater-card">
            <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
            <div class="row mb-2">
                <div class="col-md-4"><label class="small text-muted">ရာထူး</label><input type="text" name="experiences[${index}][position]" class="form-control"></div>
                <div class="col-md-4"><label class="small text-muted">ဌာန</label><input type="text" name="experiences[${index}][department]" class="form-control"></div>
                <div class="col-md-4"><label class="small text-muted">တည်နေရာ</label><input type="text" name="experiences[${index}][location]" class="form-control"></div>
            </div>
            <div class="row">
                <div class="col-md-4"><label class="small text-muted">မှ</label><input type="date" name="experiences[${index}][from_date]" class="form-control"></div>
                <div class="col-md-4"><label class="small text-muted">ထိ</label><input type="date" name="experiences[${index}][to_date]" class="form-control"></div>
                <div class="col-md-4 pt-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="experiences[${index}][is_current]" value="1"><label class="form-check-label small">လက်ရှိထမ်းဆောင်ဆဲ</label></div></div>
            </div>
        </div>`;
        } else if (type === 'past_experiences') {
            html = `<div class="repeater-card">
                <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row mb-2">
                    <div class="col-md-4"><label class="small text-muted">ရာထူး</label><input type="text" name="past_experiences[${index}][position]" class="form-control"></div>
                    <div class="col-md-4"><label class="small text-muted">လစာ</label><input type="text" name="past_experiences[${index}][salary]" class="form-control"></div>
                    <div class="col-md-4"><label class="small text-muted">တည်နေရာ</label><input type="text" name="past_experiences[${index}][location]" class="form-control"></div>
                </div>
                <div class="row">
                    <div class="col-md-4"><label class="small text-muted">စတင်သည့်ရက်</label><input type="date" name="past_experiences[${index}][start_date]" class="form-control"></div>
                    <div class="col-md-4"><label class="small text-muted">ပြီးဆုံးသည့်ရက်</label><input type="date" name="past_experiences[${index}][end_date]" class="form-control"></div>
                    <div class="col-md-4">
                        <label class="small text-muted">အသက်အာမခံ</label>
                        <select name="past_experiences[${index}][life_insurance]" class="form-select">
                            <option value="no">မရှိ</option>
                            <option value="yes">ရှိ</option>
                        </select>
                    </div>
                    <div class="col-md-12"><label class="small text-muted">မှတ်ချက်</label><textarea name="past_experiences[${index}][remark]" class="form-control" rows="1"></textarea></div>
                </div>
            </div>`;
        } else if (type === 'abroads') {
            html = `<div class="repeater-card">
            <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
            <div class="row mb-2">
                <div class="col-md-4">
                    <label class="small">သွားရောက်ခဲ့သည့်နိုင်ငံ</label>
                    <input type="text" name="abroads[${index}][country]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="small">အကြောင်းရင်း</label>
                    <input type="text" name="abroads[${index}][reason]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="small">တွေ့ဆုံခဲ့သူ</label>
                    <input type="text" name="abroads[${index}][host_name]" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="small">သွားသည့်နေ့ (Departure Date)</label>
                    <input type="date" name="abroads[${index}][departure_date]" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="small">ပြန်သည့်နေ့ (Arrival Date)</label>
                    <input type="date" name="abroads[${index}][arrival_date]" class="form-control">
                </div>
            </div>
        </div>`;
        } else if (type === 'certificates') {
            // Added issue_date, issuer, and description
            html = `<div class="repeater-card">
            <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
            <div class="row mb-2">
                <div class="col-md-4"><label class="small">အမည် (Name)</label><input type="text" name="certificates[${index}][certificate_name]" class="form-control"></div>
                <div class="col-md-4"><label class="small">ထုတ်ပေးသည့်ရက်</label><input type="date" name="certificates[${index}][issue_date]" class="form-control"></div>
                <div class="col-md-4"><label class="small">ထုတ်ပေးသည့်ဌာန</label><input type="text" name="certificates[${index}][issuer]" class="form-control"></div>
            </div>
            <div class="row">
                <div class="col-md-6"><label class="small">အကြောင်းအရာ</label><input type="text" name="certificates[${index}][description]" class="form-control"></div>
                <div class="col-md-6"><label class="small">ဖိုင်</label><input type="file" name="certificates[${index}][file]" class="form-control"></div>
            </div>
        </div>`;
        } else if (type === 'educations') {
                html = `<div class="repeater-card">
            <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
            <div class="row mb-2">
                <div class="col-md-3"><label class="small">အမျိုးအစား</label>
                    <select name="educations[${index}][type]" class="form-select">
                        <option value="school">ကျောင်း</option><option value="uni">တက္ကသိုလ်</option><option value="other">အခြား</option>
                    </select>
                </div>
                <div class="col-md-5"><label class="small">ကျောင်း/တက္ကသိုလ်အမည်</label><input type="text" name="educations[${index}][institution_name]" class="form-control"></div>
                <div class="col-md-4"><label class="small">ဘွဲ့/လက်မှတ်</label><input type="text" name="educations[${index}][degree_certificate]" class="form-control"></div>
            </div>
            <div class="row">
                <div class="col-md-4"><label class="small">အထူးပြုဘာသာ</label><input type="text" name="educations[${index}][field_of_study]" class="form-control"></div>
                <div class="col-md-4"><label class="small">ရရှိသည့်ရက်စွဲ</label><input type="date" name="educations[${index}][date]" class="form-control"></div>
                <div class="col-md-4"><label class="small">မှတ်ချက်</label><input type="text" name="educations[${index}][remark]" class="form-control"></div>
            </div>
        </div>`;
        }else if (type === 'trainings') {
        html = `<div class="repeater-card">
            <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
            <div class="row mb-2">
                <div class="col-md-3"><label class="small">သင်တန်းအမျိုးအစား</label>
                    <select name="trainings[${index}][training_type]" class="form-select">
                        <option value="domestic">ပြည်တွင်း</option><option value="foreign">ပြည်ပ</option><option value="other">အခြား</option>
                    </select>
                </div>
                <div class="col-md-5"><label class="small">သင်တန်းအမည်</label><input type="text" name="trainings[${index}][course_name]" class="form-control"></div>
                <div class="col-md-4"><label class="small">နေရာ</label><input type="text" name="trainings[${index}][location]" class="form-control"></div>
            </div>
            <div class="row">
                <div class="col-md-6"><label class="small">စတင်သည့်ရက်</label><input type="date" name="trainings[${index}][start_date]" class="form-control"></div>
                <div class="col-md-6"><label class="small">ပြီးဆုံးသည့်ရက်</label><input type="date" name="trainings[${index}][end_date]" class="form-control"></div>
            </div>
        </div>`;
    }

        container.insertAdjacentHTML('beforeend', html);
        bindDelete();
    }

    function bindDelete() {
        document.querySelectorAll('.delete-entry').forEach(function(btn) {
            btn.onclick = function() {
                this.closest('.repeater-card').remove();
            };
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        bindDelete();
        bindCurrentCheckboxes(); // <--- AND HERE
    });
</script>
