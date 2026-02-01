@extends('layouts.app')

@section('css')
<style>
    .tab-content { padding: 30px; border: 1px solid #dee2e6; border-top: none; background: #fff; border-radius: 0 0 8px 8px; }
    .nav-tabs .nav-link { color: #495057; font-weight: 600; padding: 12px 20px; }
    .nav-tabs .nav-link.active { color: #0d6efd; border-bottom: 3px solid #0d6efd; background-color: #f8f9fa; }
    .section-header { margin: 30px 0 15px; padding-bottom: 8px; border-bottom: 2px solid #ebeef4; color: #012970; font-weight: 700; font-size: 1.1rem; }
    .repeater-card { border-left: 4px solid #6c757d; background: #f9f9f9; padding: 20px; margin-bottom: 20px; position: relative; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .delete-entry { position: absolute; top: 10px; right: 10px; }
    .sticky-actions { position: sticky; bottom: 0; background: #fff; padding: 20px; border-top: 1px solid #dee2e6; z-index: 100; box-shadow: 0 -5px 15px rgba(0,0,0,0.05); margin-top: 30px; }
    .form-label { font-weight: 600; color: #444; }
</style>
@endsection

@section('content')
<div class="pagetitle">
    <h1>{{ __('messages.create_employee') }}</h1>
</div>

<section class="section">
    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" id="employeeForm">
        @csrf

        <div class="card">
            <div class="card-body pt-3">
                <ul class="nav nav-tabs nav-tabs-bordered" id="employeeTab" role="tablist">
                    <li class="nav-item"><button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#personal">၁။ ကိုယ်ရေးအချက်အလက်</button></li>
                    <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#family">၂။ မိသားစုဝင်များ</button></li>
                    <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#education">၃။ ပညာအရည်အချင်းနှင့် အတွေ့အကြုံ</button></li>
                    <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#service">၄။ တာဝန်ထမ်းဆောင်မှုမှတ်တမ်း</button></li>
                    <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#legal">၅။ ကိုယ်ရေးမှတ်တမ်းအကျဉ်း</button></li>
                </ul>

                <div class="tab-content pt-2">
                    <div class="tab-pane fade show active" id="personal">
                        <div class="section-header">အခြေခံအချက်အလက်များ</div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">ဝန်ထမ်းအမှတ် (ID) <span class="text-danger">*</span></label>
                                <input type="text" name="employee_id" class="form-control" required value="{{ old('employee_id') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">အမည် <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">ကျား/မ</label>
                                <select name="gender" class="form-select">
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ကျား</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>မ</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3"><label class="form-label">NRC နံပါတ်</label><input type="text" name="nrc" class="form-control" value="{{ old('nrc') }}"></div>
                            <div class="col-md-4 mb-3"><label class="form-label">လူမျိုး/ဘာသာ</label><input type="text" name="nationality" class="form-control" placeholder="ဥပမာ- မွန်/ဗုဒ္ဓ" value="{{ old('nationality') }}"></div>
                            <div class="col-md-4 mb-3"><label class="form-label">မွေးသက္ကရာဇ် (အင်္ဂလိပ်)</label><input type="date" name="eng_dob" class="form-control" value="{{ old('eng_dob') }}"></div>
                        </div>

                        <div class="section-header">ကိုယ်ကာယအချက်အလက်များ</div>
                        <div class="row">
                            <div class="col-md-3 mb-3"><label class="form-label">သွေးအုပ်စု</label><input type="text" name="blood_type" class="form-control" value="{{ old('blood_type') }}"></div>
                            <div class="col-md-3 mb-3"><label class="form-label">အရပ်အမြင့်</label><input type="text" name="height" class="form-control" value="{{ old('height') }}"></div>
                            <div class="col-md-3 mb-3"><label class="form-label">ကိုယ်အလေးချိန်</label><input type="text" name="weight" class="form-control" value="{{ old('weight') }}"></div>
                            <div class="col-md-3 mb-3"><label class="form-label">ဆံပင်အရောင်</label><input type="text" name="hair_color" class="form-control" value="{{ old('hair_color') }}"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">မျက်နှာအသွင်အပြင်/ထူးခြားချက်</label><input type="text" name="notable_trade" class="form-control" value="{{ old('notable_trade') }}"></div>
                            <div class="col-md-6 mb-3"><label class="form-label">ဓာတ်ပုံတင်ရန်</label><input type="file" name="profile_image" class="form-control" accept="image/*"></div>
                        </div>

                        <div class="section-header">နေရပ်လိပ်စာ</div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">လက်ရှိနေရပ်လိပ်စာ</label><textarea name="current_address" class="form-control" rows="2">{{ old('current_address') }}</textarea></div>
                            <div class="col-md-6 mb-3"><label class="form-label">အမြဲတမ်းနေရပ်လိပ်စာ</label><textarea name="permanent_address" class="form-control" rows="2">{{ old('permanent_address') }}</textarea></div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="family">
                        <div class="section-header">မိဘအချက်အလက်များ</div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card p-3 border shadow-sm">
                                    <label class="fw-bold border-bottom mb-2">ဖခင်အချက်အလက်</label>
                                    <input type="text" name="father_name" class="form-control mb-2" placeholder="အမည်" value="{{ old('father_name') }}">
                                    <input type="text" name="father_job" class="form-control mb-2" placeholder="အလုပ်အကိုင်" value="{{ old('father_job') }}">
                                    <textarea name="father_address" class="form-control" placeholder="နေရပ်လိပ်စာ">{{ old('father_address') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card p-3 border shadow-sm">
                                    <label class="fw-bold border-bottom mb-2">မိခင်အချက်အလက်</label>
                                    <input type="text" name="mother_name" class="form-control mb-2" placeholder="အမည်" value="{{ old('mother_name') }}">
                                    <input type="text" name="mother_job" class="form-control mb-2" placeholder="အလုပ်အကိုင်" value="{{ old('mother_job') }}">
                                    <textarea name="mother_address" class="form-control" placeholder="နေရပ်လိပ်စာ">{{ old('mother_address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="section-header">အိမ်ထောင်ဖက်အချက်အလက်</div>
                        <div class="row card p-3 m-0 border shadow-sm">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4 mb-3"><label class="form-label">အမည်</label><input type="text" name="spouse_name" class="form-control" value="{{ old('spouse_name') }}"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">အလုပ်အကိုင်</label><input type="text" name="spouse_job" class="form-control" value="{{ old('spouse_job') }}"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">နေရပ်လိပ်စာ</label><input type="text" name="spouse_job_place" class="form-control" value="{{ old('spouse_job_place') }}"></div>
                                </div>
                            </div>
                        </div>

                        <div class="section-header">သား/သမီးများ</div>
                        <div id="children-container"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addRepeater('children')">+ သား/သမီး ထည့်ရန်</button>

                        <div class="section-header">မိဘနှစ်ပါး၏ ညီအစ်ကို/မောင်နှမများ (Template C Expansion)</div>
                        <div id="family_tree-container"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addRepeater('family_tree')">+ ဆွေမျိုးစု ထည့်ရန်</button>
                    </div>

                    <div class="tab-pane fade" id="education">
                        <div class="section-header">ပညာအရည်အချင်း</div>
                        <div id="educations-container"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRepeater('educations')">+ ပညာအရည်အချင်း ထည့်ရန်</button>

                        <div class="section-header">ယခင် အလုပ်အကိုင်မှတ်တမ်း (ပြင်ပအတွေ့အကြုံ)</div>
                        <div id="past_experiences-container"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRepeater('past_experiences')">+ အလုပ်အကိုင်မှတ်တမ်း ထည့်ရန်</button>

                        <div class="section-header">သင်တန်းတက်ရောက်မှုမှတ်တမ်း</div>
                        <div id="trainings-container"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRepeater('trainings')">+ သင်တန်းမှတ်တမ်း ထည့်ရန်</button>
                    </div>

                    <div class="tab-pane fade" id="service">
                        <div class="section-header">နိုင်ငံ့ဝန်ထမ်း တာဝန်ထမ်းဆောင်မှုမှတ်တမ်း (လက်ရှိဌာန)</div>
                        <div id="experiences-container"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRepeater('experiences')">+ တာဝန်ထမ်းဆောင်မှုမှတ်တမ်း ထည့်ရန်</button>

                        <div class="section-header">ဝန်ထမ်းရေးရာ ဆောင်ရွက်ချက်များ (ရာထူးတိုး/ပြောင်း)</div>
                        <div id="personnel_actions-container"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRepeater('personnel_actions')">+ ဆောင်ရွက်ချက် ထည့်ရန်</button>

                        <div class="section-header">အမြဲတမ်းဝန်ထမ်း ခန့်အပ်မှုအချက်အလက်</div>
                        <div class="row card p-3 m-0 bg-light border">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ခန့်အပ်သည့်အဆင့်</label>
                                <select name="service_record[grade]" class="form-select">
                                    <option value="junior">ငယ် (Junior Grade)</option>
                                    <option value="senior">ကြီး (Senior Grade)</option>
                                    <option value="selection">ရွေးချယ် (Selection Grade)</option>
                                    <option value="higher">အထက် (Higher Grade)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ခန့်အပ်သည့်ရက်စွဲ</label>
                                <input type="date" name="service_record[recruited_date]" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="legal">
                        <div class="section-header">နိုင်ငံခြားသို့ သွားရောက်ခဲ့သည့်မှတ်တမ်း</div>
                        <div id="abroads-container"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRepeater('abroads')">+ ပြည်ပခရီးစဉ် ထည့်ရန်</button>

                        <div class="section-header">ကိုယ်ရေးမှတ်တမ်း အကျဉ်းချုပ် (Background)</div>
                        <div class="row">
                            <div class="col-md-12 mb-3"><label class="form-label">ပညာသင်ကြားခဲ့သည့် ကျောင်းများ</label><textarea name="schools" class="form-control" rows="2">{{ old('schools') }}</textarea></div>
                            <div class="col-md-12 mb-3"><label class="form-label">ထူးခြားသည့် ဆောင်ရွက်ချက်များ</label><textarea name="school_voluntary" class="form-control" rows="2">{{ old('school_voluntary') }}</textarea></div>
                            <div class="col-md-12 mb-3"><label class="form-label">နိုင်ငံသားတစ်ဦးအနေဖြင့် ဆောင်ရွက်ရမည့် တာဝန်များ</label><input type="text" name="citizen_duties" class="form-control" value="{{ old('citizen_duties') }}"></div>
                        </div>

                        <div class="section-header">ပြစ်မှုမှတ်တမ်း (Criminal Record)</div>
                        <div id="criminal_records-container"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRepeater('criminal_records')">+ ပြစ်မှုမှတ်တမ်း ထည့်ရန်</button>

                        <div class="section-header">လက်မှတ်များ တင်ရန် (Certificates)</div>
                        <div id="certificates-container"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRepeater('certificates')">+ လက်မှတ် ထည့်ရန်</button>
                    </div>
                </div>
            </div>

            <div class="sticky-actions text-center">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-lg px-4 me-3">Cancel</a>
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow">ဝန်ထမ်းအချက်အလက် သိမ်းဆည်းမည်</button>
            </div>
        </div>
    </form>
</section>
@endsection

<script>
    const counters = {
        children: 0,
        educations: 0,
        past_experiences: 0,
        trainings: 0,
        experiences: 0,
        personnel_actions: 0,
        certificates: 0,
        criminal_records: 0,
        family_tree: 0,
        abroads: 0
    };

    function addRepeater(type) {
        const index = counters[type]++;
        const container = document.getElementById(`${type}-container`);
        let html = '';

        if (type === 'children') {
            html = `<div class="repeater-card">
                <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row">
                    <div class="col-md-6"><label class="small text-muted">အမည်</label><input type="text" name="children[${index}][name]" class="form-control"></div>
                    <div class="col-md-6"><label class="small text-muted">မွေးသက္ကရာဇ်</label><input type="date" name="children[${index}][date_of_birth]" class="form-control"></div>
                </div>
            </div>`;
        } 
        else if (type === 'educations') {
            html = `<div class="repeater-card">
                <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row">
                    <div class="col-md-3">
                        <label class="small text-muted">အမျိုးအစား</label>
                        <select name="educations[${index}][type]" class="form-select">
                            <option value="school">ကျောင်း</option>
                            <option value="uni">တက္ကသိုလ်</option>
                            <option value="other">အခြား</option>
                        </select>
                    </div>
                    <div class="col-md-3"><label class="small text-muted">ကျောင်း/တက္ကသိုလ်အမည်</label><input type="text" name="educations[${index}][institution_name]" class="form-control"></div>
                    <div class="col-md-3"><label class="small text-muted">ရရှိသည့် ဘွဲ့/လက်မှတ်</label><input type="text" name="educations[${index}][degree_certificate]" class="form-control"></div>
                    <div class="col-md-3"><label class="small text-muted">ရက်စွဲ</label><input type="date" name="educations[${index}][date]" class="form-control"></div>
                </div>
            </div>`;
        }
        else if (type === 'experiences') {
            html = `<div class="repeater-card">
                <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row mb-2">
                    <div class="col-md-4"><label class="small text-muted">ရာထူး</label><input type="text" name="experiences[${index}][position]" class="form-control"></div>
                    <div class="col-md-4"><label class="small text-muted">ဌာန</label><input type="text" name="experiences[${index}][department]" class="form-control"></div>
                    <div class="col-md-4"><label class="small text-muted">တည်နေရာ</label><input type="text" name="experiences[${index}][location]" class="form-control"></div>
                </div>
                <div class="row">
                    <div class="col-md-4"><label class="small text-muted">မှ</label><input type="date" name="experiences[${index}][from_date]" class="form-control"></div>
                    <div class="col-md-4"><label class="small text-muted">ထိ</label><input type="date" name="experiences[${index}][to_date]" class="form-control to-date-field"></div>
                    <div class="col-md-4 pt-4"><div class="form-check"><input class="form-check-input is-current-check" type="checkbox" name="experiences[${index}][is_current]" value="1"><label class="form-check-label">လက်ရှိ</label></div></div>
                </div>
            </div>`;
        }
        else if (type === 'past_experiences') {
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
                        <select name="past_experiences[${index}][life_insurance]" class="form-select"><option value="no">မရှိ</option><option value="yes">ရှိ</option></select>
                    </div>
                </div>
            </div>`;
        }
        else if (type === 'trainings') {
            html = `<div class="repeater-card">
                <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row">
                    <div class="col-md-3">
                        <label class="small text-muted">အမျိုးအစား</label>
                        <select name="trainings[${index}][training_type]" class="form-select"><option value="domestic">ပြည်တွင်း</option><option value="foreign">ပြည်ပ</option></select>
                    </div>
                    <div class="col-md-3"><label class="small text-muted">သင်တန်းအမည်</label><input type="text" name="trainings[${index}][course_name]" class="form-control"></div>
                    <div class="col-md-3"><label class="small text-muted">စတင်သည့်ရက်</label><input type="date" name="trainings[${index}][start_date]" class="form-control"></div>
                    <div class="col-md-3"><label class="small text-muted">ပြီးဆုံးသည့်ရက်</label><input type="date" name="trainings[${index}][end_date]" class="form-control"></div>
                </div>
            </div>`;
        }
        else if (type === 'family_tree') {
            html = `<div class="repeater-card">
                <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row">
                    <div class="col-md-2"><label class="small text-muted">ဘက်</label><select name="family_tree[${index}][side]" class="form-select"><option value="father">ဖခင်ဘက်</option><option value="mother">မိခင်ဘက်</option></select></div>
                    <div class="col-md-3"><label class="small text-muted">အမည်</label><input type="text" name="family_tree[${index}][name]" class="form-control"></div>
                    <div class="col-md-2"><label class="small text-muted">တော်စပ်ပုံ</label><input type="text" name="family_tree[${index}][relation]" class="form-control"></div>
                    <div class="col-md-2"><label class="small text-muted">အလုပ်</label><input type="text" name="family_tree[${index}][job]" class="form-control"></div>
                    <div class="col-md-3"><label class="small text-muted">နေရပ်</label><input type="text" name="family_tree[${index}][location]" class="form-control"></div>
                </div>
            </div>`;
        }
        else if (type === 'personnel_actions') {
            html = `<div class="repeater-card">
                <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row mb-2">
                    <div class="col-md-3"><label class="small text-muted">အမျိုးအစား</label><select name="personnel_actions[${index}][type]" class="form-select"><option value="recruit">ခန့်အပ်ခြင်း</option><option value="promote">ရာထူးတိုး</option><option value="transfer">ပြောင်းရွှေ့</option></select></div>
                    <div class="col-md-3"><label class="small text-muted">ရာထူး</label><input type="text" name="personnel_actions[${index}][position]" class="form-control"></div>
                    <div class="col-md-3"><label class="small text-muted">ဌာန</label><input type="text" name="personnel_actions[${index}][department]" class="form-control"></div>
                    <div class="col-md-3"><label class="small text-muted">ရက်စွဲ</label><input type="date" name="personnel_actions[${index}][start_date]" class="form-control"></div>
                </div>
            </div>`;
        }
        else if (type === 'abroads') {
            html = `<div class="repeater-card">
                <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row">
                    <div class="col-md-3"><label class="small text-muted">နိုင်ငံ</label><input type="text" name="abroads[${index}][country]" class="form-control"></div>
                    <div class="col-md-3"><label class="small text-muted">အကြောင်းအရင်း</label><input type="text" name="abroads[${index}][reason]" class="form-control"></div>
                    <div class="col-md-3"><label class="small text-muted">ထွက်ခွာရက်</label><input type="date" name="abroads[${index}][departure_date]" class="form-control"></div>
                    <div class="col-md-3"><label class="small text-muted">ရောက်ရှိရက်</label><input type="date" name="abroads[${index}][arrival_date]" class="form-control"></div>
                </div>
            </div>`;
        }
        else if (type === 'certificates') {
            html = `<div class="repeater-card">
                <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row mb-2">
                    <div class="col-md-6"><label class="small text-muted">လက်မှတ်အမည်</label><input type="text" name="certificates[${index}][certificate_name]" class="form-control"></div>
                    <div class="col-md-6"><label class="small text-muted">ဖိုင်တင်ရန်</label><input type="file" name="certificates[${index}][file]" class="form-control"></div>
                </div>
            </div>`;
        }
        else if (type === 'criminal_records') {
            html = `<div class="repeater-card">
                <button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                <div class="row">
                    <div class="col-md-8"><label class="small text-muted">ပြစ်မှုအကျဉ်း</label><input type="text" name="criminal_records[${index}][description]" class="form-control"></div>
                    <div class="col-md-4"><label class="small text-muted">ဖိုင်တင်ရန်</label><input type="file" name="criminal_records[${index}][file]" class="form-control"></div>
                </div>
            </div>`;
        }

        container.insertAdjacentHTML('beforeend', html);
        bindDelete();
        bindCurrentCheckboxes();
    }

    function bindDelete() {
        document.querySelectorAll('.delete-entry').forEach(btn => {
            btn.onclick = function() { this.parentElement.remove(); };
        });
    }

    function bindCurrentCheckboxes() {
        document.querySelectorAll('.is-current-check').forEach(checkbox => {
            checkbox.onchange = function() {
                const toDate = this.closest('.repeater-card').querySelector('.to-date-field');
                toDate.disabled = this.checked;
                if (this.checked) toDate.value = '';
            };
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Initial rows
        addRepeater('children');
    });
</script>
