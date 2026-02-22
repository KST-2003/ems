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
            border-left: 4px solid #0d6efd;
            /* Blue for new entries */
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
    </style>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>{{ __('messages.add_employee') }}</h1>
    </div>

    <section class="section">
        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" id="employeeForm">
            @csrf

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
                                    <img src="{{ asset('images/no-profile.png') }}" class="rounded-circle border"
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                    <input type="file" name="profile_image" class="form-control form-control-sm mt-2">
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-4 mb-3"><label class="form-label">ဝန်ထမ်းအမှတ် (ID)</label>
                                            <input type="text" name="employee_id" class="form-control"
                                                value="{{ old('employee_id') }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3"><label class="form-label">အမည်</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name') }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">ငယ်အမည်</label>
                                            <input type="text" name="home_name" class="form-control"
                                                value="{{ old('home_name', $employee ?? (('')->home_name ?? '')) }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">အခြားအမည်</label>
                                            <input type="text" name="nick_name" class="form-control"
                                                value="{{ old('nick_name', $employee ?? (('')->nick_name ?? '')) }}">
                                        </div>
                                        <div class="col-md-4 mb-3"><label class="form-label">ကျား/မ</label>
                                            <select name="gender" class="form-select">
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ကျား
                                                </option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>မ
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3"><label class="form-label">ဖုန်းနံပါတ်</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ old('phone') }}">
                                        </div>
                                        <div class="col-md-6 mb-3"><label class="form-label">NRC နံပါတ်</label>
                                            <input type="text" name="nrc" class="form-control"
                                                value="{{ old('nrc') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3"><label class="form-label">မွေးသက္ကရာဇ် (Eng)</label>
                                    <input type="date" name="eng_dob" class="form-control" value="{{ old('eng_dob') }}">
                                </div>
                                <div class="col-md-4 mb-3"><label class="form-label">လူမျိုး/ဘာသာ</label>
                                    <input type="text" name="nationality" class="form-control"
                                        value="{{ old('nationality') }}">
                                </div>
                                <div class="col-md-4 mb-3"><label class="form-label">သွေးအုပ်စု</label>
                                    <input type="text" name="blood_type" class="form-control"
                                        value="{{ old('blood_type') }}">
                                </div>
                            </div>

                            <div class="section-header">ကိုယ်ကာယအချက်အလက်များ</div>
                            <div class="row">
                                <div class="col-md-3 mb-3"><label class="form-label">အရပ်အမြင့်</label><input
                                        type="text" name="height" class="form-control" value="{{ old('height') }}">
                                </div>
                                <div class="col-md-3 mb-3"><label class="form-label">ကိုယ်အလေးချိန်</label><input
                                        type="text" name="weight" class="form-control" value="{{ old('weight') }}">
                                </div>
                                <div class="col-md-3 mb-3"><label class="form-label">ဆံပင်အရောင်</label><input
                                        type="text" name="hair_color" class="form-control"
                                        value="{{ old('hair_color') }}"></div>
                                <div class="col-md-3 mb-3"><label class="form-label">မျက်နှာအသွင်အပြင်</label><input
                                        type="text" name="notable_trade" class="form-control"
                                        value="{{ old('notable_trade') }}"></div>
                            </div>

                            <div class="section-header">နေရပ်လိပ်စာ</div>
                            <div class="row">
                                <div class="col-md-6 mb-3"><label class="form-label">လက်ရှိနေရပ်</label>
                                    <textarea name="current_address" class="form-control" rows="2">{{ old('current_address') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3"><label class="form-label">အမြဲတမ်းနေရပ်</label>
                                    <textarea name="permanent_address" class="form-control" rows="2">{{ old('permanent_address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="family">
                            <div class="section-header">မိဘများ အချက်အလက်</div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card p-3 border"><label class="fw-bold border-bottom mb-2">ဖခင်</label>
                                        <input type="text" name="father_name" class="form-control mb-1"
                                            placeholder="အမည်" value="{{ old('father_name') }}">
                                        <input type="text" name="father_job" class="form-control mb-1"
                                            placeholder="အလုပ်" value="{{ old('father_job') }}">
                                        <textarea name="father_address" class="form-control" placeholder="နေရပ်လိပ်စာ">{{ old('father_address') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card p-3 border"><label class="fw-bold border-bottom mb-2">မိခင်</label>
                                        <input type="text" name="mother_name" class="form-control mb-1"
                                            placeholder="အမည်" value="{{ old('mother_name') }}">
                                        <input type="text" name="mother_job" class="form-control mb-1"
                                            placeholder="အလုပ်" value="{{ old('mother_job') }}">
                                        <textarea name="mother_address" class="form-control" placeholder="နေရပ်လိပ်စာ">{{ old('mother_address') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="section-header">အိမ်ထောင်ဖက်</div>
                            <div class="row card p-3 m-0 border shadow-sm">
                                <div class="row">
                                    <div class="col-md-4 mb-3"><label>အမည်</label><input type="text"
                                            name="spouse_name" class="form-control" value="{{ old('spouse_name') }}">
                                    </div>
                                    <div class="col-md-4 mb-3"><label>အလုပ်</label><input type="text"
                                            name="spouse_job" class="form-control" value="{{ old('spouse_job') }}">
                                    </div>
                                    <div class="col-md-4 mb-3"><label>နေရပ်</label><input type="text"
                                            name="spouse_job_place" class="form-control"
                                            value="{{ old('spouse_job_place') }}"></div>
                                </div>
                            </div>

                            <div class="section-header">သား/သမီးများ</div>
                            <div id="children-container"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addRepeater('children')">+ သား/သမီး ထည့်ရန်</button>

                            <div class="section-header">မိဘနှစ်ပါး၏ ညီအစ်ကို/မောင်နှမများ</div>
                            <div id="family_tree-container"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addRepeater('family_tree')">+ ဆွေမျိုး ထည့်ရန်</button>
                        </div>

                        <div class="tab-pane fade" id="education">
                            <div class="section-header">ပညာအရည်အချင်း (Education)</div>
                            <div id="educations-container"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-4"
                                onclick="addRepeater('educations')">+ ပညာအရည်အချင်း ထည့်ရန်</button>

                            <div class="section-header">ပြည်တွင်းပြည်ပ သင်တန်းများတက်ရောက်မှု</div>
                            <div id="trainings-container"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-4"
                                onclick="addRepeater('trainings')">+ သင်တန်းမှတ်တမ်း ထည့်ရန်</button>

                            <div class="section-header">ချီးမြှင့်ခံရသည့် ဘွဲ့ထူး၊ ဂုဏ်ထူးတံဆိပ်များ</div>
                            <div id="certificates-container"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-4"
                                onclick="addRepeater('certificates')">+ လက်မှတ် ထည့်ရန်</button>
                        </div>

                        <div class="tab-pane fade" id="service">
                            <div class="section-header">နိုင်ငံ့ဝန်ထမ်း တာဝန်ထမ်းဆောင်မှု (လက်ရှိဌာန)</div>
                            <div id="experiences-container"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addRepeater('experiences')">+ တာဝန်ထမ်းဆောင်မှု ထည့်ရန်</button>

                            <div class="section-header">အမြဲတမ်းဝန်ထမ်း ခန့်အပ်မှုအချက်အလက်</div>
                            <div class="row card p-3 m-0 bg-light border">
                                <div class="col-md-6 mb-3"><label class="form-label">ခန့်အပ်သည့်အဆင့်</label>
                                    <select name="service_record[grade]" class="form-select">
                                        <option value="junior">ငယ် (Junior Grade)</option>
                                        <option value="senior">ကြီး (Senior Grade)</option>
                                        <option value="selection">ရွေးချယ် (Selection Grade)</option>
                                        <option value="higher">အထက် (Higher Grade)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3"><label class="form-label">ခန့်အပ်သည့်ရက်စွဲ</label>
                                    <input type="date" name="service_record[recruited_date]" class="form-control"
                                        value="{{ old('service_record.recruited_date') }}">
                                </div>
                            </div>
                            <div class="section-header">စစ်ဘက်ဆိုင်ရာ အချက်အလက်များ (Military Info)</div>
                            <div class="card p-4 border shadow-sm mb-4 bg-white">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">ကိုယ်ပိုင်အမှတ် (Badge No.)</label>
                                        <input type="text" name="badge_no" class="form-control"
                                            value="{{ old('badge_no') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">တပ်ထဲဝင်သည့်ရက်</label>
                                        <input type="date" name="entry_date" class="form-control"
                                            value="{{ old('entry_date') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">ဗိုလ်လောင်းသင်တန်းအမှတ်စဉ်</label>
                                        <input type="text" name="batch_class_no" class="form-control"
                                            value="{{ old('batch_class_no') }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">ပြန်တမ်းဝင်ဖြစ်သည့်နေ့</label>
                                        <input type="date" name="date_comission" class="form-control"
                                            value="{{ old('date_comission') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">တပ်ထွက်သည့်နေ့</label>
                                        <input type="date" name="date_discharge" class="form-control"
                                            value="{{ old('date_discharge') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">အငြမ်းစားလစာ</label>
                                        <input type="text" name="pension" class="form-control"
                                            value="{{ old('pension') }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">ထွက်သည့်အကြောင်း</label>
                                        <input type="text" name="reason_discharge" class="form-control"
                                            value="{{ old('reason_discharge') }}">
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label fw-bold">အမှုထမ်းဆောင်ခဲ့သောတပ်များ</label>
                                        <input type="text" name="units_served" class="form-control"
                                            value="{{ old('units_served') }}">
                                    </div>

                                    <div class="col-md-12 mb-0">
                                        <label class="form-label fw-bold">တပ်တွင်းရာဇဝင်အကျဉ်း/ပြစ်မှု</label>
                                        <textarea name="disciplinary_record" class="form-control" rows="3">{{ old('disciplinary_record') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="legal">
                            <div class="section-header">ငယ်စဉ်မှယခုအချိန်ထိ ကိုယ်ရေးရာဇဝင် (Personal History)</div>
                            <div class="row">
                                <div class="col-md-12 mb-3"><label class="form-label">၁။ နေခဲ့ဖူးသောကျောင်းများ</label>
                                    <textarea name="schools" class="form-control" rows="3">{{ old('schools') }}</textarea>
                                </div>
                                <div class="col-md-12 mb-3"><label class="form-label">၂။
                                        နောက်ဆုံးအောင်မြင်ခဲ့သည့်ကျောင်း/အတန်း</label>
                                    <textarea name="latest_school" class="form-control" rows="2">{{ old('latest_school') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3"><label class="form-label">၃။ ကျောင်းသားဘဝ
                                        ဆောင်ရွက်မှုများ</label>
                                    <textarea name="school_voluntary" class="form-control" rows="2">{{ old('school_voluntary') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3"><label class="form-label">၄။ ဝါသနာနှင့်
                                        လေ့လာလိုက်စားမှုများ</label>
                                    <textarea name="hobbies" class="form-control" rows="2">{{ old('hobbies') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3"><label class="form-label">၅။ လုပ်ကိုင်ခဲ့သော
                                        အလုပ်အကိုင်များ</label><input type="text" name="jobs_dept"
                                        class="form-control" value="{{ old('jobs_dept') }}"></div>
                                <div class="col-md-6 mb-3"><label class="form-label">၆။
                                        တောခိုခဲ့ဖူးလျှင်/သောင်းကျန်းသူနယ်မြေ နေခဲ့ဖူးလျှင်</label>
                                    <textarea name="refugee" class="form-control" rows="2">{{ old('refugee') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3"><label class="form-label">၇။ အလုပ်အကိုင်ပြောင်းရွှေ့ခဲ့သော
                                        အကြောင်းအရင်း</label>
                                    <textarea name="jobtransfer_desc" class="form-control" rows="2">{{ old('jobtransfer_desc') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3"><label class="form-label">၈။ နိုင်ငံရေး၊ မြို့/ရွာရေး
                                        ဆောင်ရွက်မှုများ</label>
                                    <textarea name="citizen_duties" class="form-control" rows="2">{{ old('citizen_duties') }}</textarea>
                                </div>
                                <div class="col-md-12 mb-3"><label class="form-label">၉။ အရာရှိ/နိုင်ငံရေးဘက်တွင်
                                        ခင်မင်သူများ ရှိ/မရှိ</label><input type="text" name="relatives_officials"
                                        class="form-control" value="{{ old('relatives_officials') }}"></div>
                            </div>

                            <div class="section-header">၁၀။ နိုင်ငံခြားသို့ သွားရောက်ခဲ့ဖူးလျှင်</div>
                            <div id="abroads-container"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-4"
                                onclick="addRepeater('abroads')">+ ပြည်ပခရီးစဉ် ထည့်ရန်</button>

                            <div class="row">
                                <div class="col-md-12 mb-3"><label class="form-label">၁၁။
                                        ခင်မင်ရင်းနှီးသောနိုင်ငံခြားသားရှိမရှိ</label>
                                    <textarea name="foreign_friends_desc" class="form-control" rows="2">{{ old('foreign_friends_desc') }}</textarea>
                                </div>
                                <div class="col-md-12 mb-3"><label class="form-label">၁၂။
                                        မိမိအားထောက်ခံသည့်ပုဂ္ဂိုလ်</label><input type="text" name="referal_officials"
                                        class="form-control" value="{{ old('referal_officials') }}"></div>
                                <div class="col-md-12 mb-3">
                                    <div class="card p-3 bg-light border">
                                        <label class="form-label fw-bold">၁၃။ ရာဇဝတ်ပြစ်မှုခံရခြင်း ရှိ/မရှိ</label>
                                        <div class="form-check">
                                            <input type="hidden" name="has_criminal_rec" value="0">
                                            <input type="checkbox" name="has_criminal_rec" class="form-check-input"
                                                value="1" {{ old('has_criminal_rec') ? 'checked' : '' }}>
                                            <label class="form-check-label">ရာဇဝတ်ပြစ်မှု ရှိပါသည်</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sticky-actions text-center">
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-lg px-4 me-3">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow">Save New Employee</button>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('scripts')
    <script>
        var counters = {
            children: 0,
            educations: 0,
            trainings: 0,
            experiences: 0,
            certificates: 0,
            family_tree: 0,
            abroads: 0
        };

        function addRepeater(type) {
            var index = counters[type]++;
            var container = document.getElementById(type + '-container');
            if (!container) return;

            var html = '';
            switch (type) {
                case 'children':
                    html =
                        `<div class="repeater-card"><button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                    <div class="row"><div class="col-md-6"><label class="small">အမည်</label><input type="text" name="children[${index}][name]" class="form-control"></div>
                    <div class="col-md-6"><label class="small">မွေးသက္ကရာဇ်</label><input type="date" name="children[${index}][date_of_birth]" class="form-control"></div></div></div>`;
                    break;
                case 'family_tree':
                    html =
                        `<div class="repeater-card"><button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                    <div class="row"><div class="col-md-2"><label class="small">ဘက်</label><select name="family_tree[${index}][side]" class="form-select"><option value="father">ဖခင်</option><option value="mother">မိခင်</option></select></div>
                    <div class="col-md-3"><label class="small">အမည်</label><input type="text" name="family_tree[${index}][name]" class="form-control"></div>
                    <div class="col-md-4"><label class="small">လူမျိုး/ဘာသာ</label><input type="text" name="family_tree[${index}][nationality_religion]" class="form-control"></div>
                    <div class="col-md-3"><label class="small">မွေးရပ်ဇာတိ</label><input type="text" name="family_tree[${index}][hometown]" class="form-control"></div>
                    <div class="col-md-2 mt-2"><label class="small">တော်စပ်ပုံ</label><input type="text" name="family_tree[${index}][relation]" class="form-control"></div>
                    <div class="col-md-2 mt-2"><label class="small">အလုပ်</label><input type="text" name="family_tree[${index}][job]" class="form-control"></div>
                    <div class="col-md-8 mt-2"><label class="small">နေရပ်</label><input type="text" name="family_tree[${index}][location]" class="form-control"></div></div></div>`;
                    break;
                case 'educations':
                    html = `<div class="repeater-card"><button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                    <div class="row mb-2">
                        <div class="col-md-3"><label class="small">အမျိုးအစား</label><select name="educations[${index}][type]" class="form-select"><option value="school">ကျောင်း</option><option value="uni">တက္ကသိုလ်</option><option value="other">အခြား</option></select></div>
                        <div class="col-md-5"><label class="small">ကျောင်း/တက္ကသိုလ်အမည်</label><input type="text" name="educations[${index}][institution_name]" class="form-control"></div>
                        <div class="col-md-4"><label class="small">ဘွဲ့/လက်မှတ်</label><input type="text" name="educations[${index}][degree_certificate]" class="form-control"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><label class="small">အထူးပြုဘာသာ</label><input type="text" name="educations[${index}][field_of_study]" class="form-control"></div>
                        <div class="col-md-4"><label class="small">ရရှိသည့်ရက်စွဲ</label><input type="date" name="educations[${index}][date]" class="form-control"></div>
                        <div class="col-md-4"><label class="small">မှတ်ချက်</label><input type="text" name="educations[${index}][remark]" class="form-control"></div>
                    </div></div>`;
                    break;
                case 'trainings':
                    html =
                        `<div class="repeater-card"><button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                    <div class="row mb-2">
                        <div class="col-md-3"><label class="small">အမျိုးအစား</label><select name="trainings[${index}][training_type]" class="form-select"><option value="domestic">ပြည်တွင်း</option><option value="foreign">ပြည်ပ</option></select></div>
                        <div class="col-md-5"><label class="small">သင်တန်းအမည်</label><input type="text" name="trainings[${index}][course_name]" class="form-control"></div>
                        <div class="col-md-4"><label class="small">နေရာ</label><input type="text" name="trainings[${index}][location]" class="form-control"></div>
                    </div>
                    <div class="row"><div class="col-md-6"><label class="small">စတင်သည့်ရက်</label><input type="date" name="trainings[${index}][start_date]" class="form-control"></div>
                    <div class="col-md-6"><label class="small">ပြီးဆုံးသည့်ရက်</label><input type="date" name="trainings[${index}][end_date]" class="form-control"></div></div></div>`;
                    break;
                case 'experiences':
                    html = `<div class="repeater-card"><button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                    <div class="row mb-2">
                        <div class="col-md-4"><label class="small">ရာထူး</label><input type="text" name="experiences[${index}][position]" class="form-control"></div>
                        <div class="col-md-4"><label class="small">ဌာန</label><input type="text" name="experiences[${index}][department]" class="form-control"></div>
                        <div class="col-md-4"><label class="small">တည်နေရာ</label><input type="text" name="experiences[${index}][location]" class="form-control"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><label class="small">မှ</label><input type="date" name="experiences[${index}][from_date]" class="form-control"></div>
                        <div class="col-md-4"><label class="small">ထိ</label><input type="date" name="experiences[${index}][to_date]" class="form-control to-date-field"></div>
                        <div class="col-md-4 pt-4"><div class="form-check"><input type="hidden" name="experiences[${index}][is_current]" value="0"><input type="checkbox" class="form-check-input is-current-check" name="experiences[${index}][is_current]" value="1"><label class="form-check-label small">လက်ရှိထမ်းဆောင်ဆဲ</label></div></div>
                    </div></div>`;
                    break;
                case 'abroads':
                    html =
                        `<div class="repeater-card"><button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                    <div class="row mb-2">
                        <div class="col-md-4"><label class="small">သွားရောက်ခဲ့သည့်နိုင်ငံ</label><input type="text" name="abroads[${index}][country]" class="form-control"></div>
                        <div class="col-md-4"><label class="small">အကြောင်းရင်း</label><input type="text" name="abroads[${index}][reason]" class="form-control"></div>
                        <div class="col-md-4"><label class="small">တွေ့ဆုံခဲ့သူ</label><input type="text" name="abroads[${index}][host_name]" class="form-control"></div>
                    </div>
                    <div class="row"><div class="col-md-6"><label class="small">သွားသည့်နေ့</label><input type="date" name="abroads[${index}][departure_date]" class="form-control"></div>
                    <div class="col-md-6"><label class="small">ပြန်သည့်နေ့</label><input type="date" name="abroads[${index}][arrival_date]" class="form-control"></div></div></div>`;
                    break;
                case 'certificates':
                    html =
                        `<div class="repeater-card"><button type="button" class="btn btn-danger btn-sm delete-entry">X</button>
                    <div class="row mb-2">
                        <div class="col-md-4"><label class="small">အမည် (Name)</label><input type="text" name="certificates[${index}][certificate_name]" class="form-control"></div>
                        <div class="col-md-4"><label class="small">ထုတ်ပေးသည့်ရက်</label><input type="date" name="certificates[${index}][issue_date]" class="form-control"></div>
                        <div class="col-md-4"><label class="small">ထုတ်ပေးသည့်ဌာန</label><input type="text" name="certificates[${index}][issuer]" class="form-control"></div>
                    </div>
                    <div class="row"><div class="col-md-6"><label class="small">အကြောင်းအရာ</label><input type="text" name="certificates[${index}][description]" class="form-control"></div>
                    <div class="col-md-6"><label class="small">ဖိုင်</label><input type="file" name="certificates[${index}][file]" class="form-control"></div></div></div>`;
                    break;
            }

            container.insertAdjacentHTML('beforeend', html);
            bindDelete();
            bindCurrentCheckboxes();
        }

        function bindDelete() {
            document.querySelectorAll('.delete-entry').forEach(btn => {
                btn.onclick = function() {
                    this.closest('.repeater-card').remove();
                };
            });
        }

        function bindCurrentCheckboxes() {
            document.querySelectorAll('.is-current-check').forEach(checkbox => {
                checkbox.onchange = function() {
                    const toDateField = this.closest('.repeater-card').querySelector('.to-date-field');
                    if (this.checked) {
                        toDateField.disabled = true;
                        toDateField.value = '';
                    } else {
                        toDateField.disabled = false;
                    }
                };
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Optional: Pre-add one field for each mandatory section
            // addRepeater('experiences'); 
            bindDelete();
            bindCurrentCheckboxes();
        });
    </script>
@endsection
