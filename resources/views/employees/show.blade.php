@extends('layouts.app')

@section('css')
    <style>
        .profile-image {
            max-width: 150px;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
        }

        .section-title {
            border-bottom: 2px solid #007bff;
            margin-top: 30px;
            margin-bottom: 15px;
            font-weight: bold;
            color: #007bff;
            text-transform: uppercase;
            font-size: 1.1rem;
        }

        .info-label {
            font-weight: 600;
            color: #555;
        }

        .table th {
            background-color: #f8f9fa;
            font-size: 0.9rem;
        }

        .bg-light-box {
            background-color: #fdfdfd;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="pagetitle">
        <div class="row align-items-center">
            <div class="col-8">
                <h1>ဝန်ထမ်းကိုယ်ရေးမှတ်တမ်းအပြည့်အစုံ</h1>
            </div>
            <div class="col-4 text-end">
                <a href="{{ route('employees.print.select', $employee) }}" class="btn btn-primary btn-sm"><i
                        class="bi bi-printer"></i> Print</a>
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm"><i
                        class="bi bi-pencil"></i> Edit</a>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body p-4">

                {{-- CATEGORY 1: BASIC INFO & PHYSICAL --}}
                <div class="row">
                    <div class="col-md-9">
                        <h5 class="section-title">၁။ အခြေခံအချက်အလက်များ (Basic Info)</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><span class="info-label">အမည်:</span> {{ $employee->name }}</p>
                                <p><span class="info-label">ငယ်အမည်:</span> {{ $employee->home_name ?? '-' }}</p>
                                <p><span class="info-label">အခြားအမည်:</span> {{ $employee->nick_name ?? '-' }}</p>
                                <p><span class="info-label">ကိုယ်ပိုင်အမှတ်:</span> {{ $employee->employee_id ?? '-' }}</p>
                                <p><span class="info-label">မွေးသက္ကရာဇ်:</span> {{ $employee->mm_dob }} /
                                    {{ $employee->eng_dob ? \Carbon\Carbon::parse($employee->eng_dob)->format('Y-m-d') : '-' }}
                                </p>
                                <p><span class="info-label">မှတ်ပုံတင်အမှတ်:</span> {{ $employee->nrc ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><span class="info-label">အရပ်/အလေးချိန်:</span> {{ $employee->height ?? '-' }} /
                                    {{ $employee->weight ?? '-' }}</p>
                                <p><span class="info-label">ဆံပင်/အသားအရောင်:</span> {{ $employee->hair_color ?? '-' }} /
                                    {{ $employee->skin_color ?? '-' }}</p>
                                <p><span class="info-label">ထူးခြားအမှတ်အသား:</span> {{ $employee->notable_trade ?? '-' }}
                                </p>
                                <p><span class="info-label">သွေးအမျိုးအစား:</span> {{ $employee->blood_type ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <img src="{{ $employee->profile_image_url }}" alt="Profile" class="profile-image shadow-sm mb-2">
                    </div>
                </div>

                {{-- CATEGORY 2: PARENTS & FAMILY --}}
                <h5 class="section-title">၂။ မိဘနှင့် မိသားစုအချက်အလက်များ (Parents & Family)</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="bg-light-box">
                            <p><strong>ဖခင်အမည်:</strong> {{ $employee->father_name }}</p>
                            <p class="small text-muted mb-0">{{ $employee->father_nationality }} /
                                {{ $employee->father_religion }} / {{ $employee->father_job }}</p>
                            <p class="small text-muted">{{ $employee->father_address }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light-box">
                            <p><strong>မိခင်အမည်:</strong> {{ $employee->mother_name }}</p>
                            <p class="small text-muted mb-0">{{ $employee->mother_nationality }} /
                                {{ $employee->mother_religion }} / {{ $employee->mother_job }}</p>
                            <p class="small text-muted">{{ $employee->mother_address }}</p>
                        </div>
                    </div>
                </div>

                <h6>မိဘနှစ်ပါး၏ ညီအစ်ကိုမောင်နှမများ (Parents' Siblings)</h6>
                <table class="table table-sm table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>ဘက်</th>
                            <th>အမည်</th>
                            <th>တော်စပ်ပုံ</th>
                            <th>အလုပ်အကိုင်/နေရပ်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employee->parentSiblings as $sibling)
                            <tr>
                                <td>{{ $sibling->side == 'father' ? 'ဖခင်ဘက်' : 'မိခင်ဘက်' }}</td>
                                <td>{{ $sibling->name }}</td>
                                <td>{{ $sibling->relation }}</td>
                                <td>{{ $sibling->job }} ({{ $sibling->location }})</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">အချက်အလက်မရှိပါ။</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-md-6">
                        <h6>အိမ်ထောင်ဖက် (Spouse)</h6>
                        <p>{{ $employee->spouse->name ?? 'မရှိပါ' }}
                            {{ $employee->spouse ? "({$employee->spouse->job})" : '' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>သားသမီးများ (Children)</h6>
                        @forelse($employee->children as $child)
                            <div>- {{ $child->name }}
                                ({{ $child->date_of_birth ? \Carbon\Carbon::parse($child->date_of_birth)->format('Y-m-d') : '-' }})
                            </div>
                        @empty
                            <p class="text-muted">မရှိပါ</p>
                        @endforelse
                    </div>
                </div>

                {{-- CATEGORY 3: EDUCATION, TRAINING & CERTIFICATES --}}
                <h5 class="section-title">၃။ ပညာအရည်အချင်းနှင့် သင်တန်းများ (Education & Training)</h5>
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>အမျိုးအစား</th>
                            <th>ကျောင်း/တက္ကသိုလ်</th>
                            <th>ဘွဲ့/လက်မှတ်</th>
                            <th>ရက်စွဲ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employee->educations as $edu)
                            <tr>
                                <td>{{ $edu->type }}</td>
                                <td>{{ $edu->institution_name }}</td>
                                <td>{{ $edu->degree_certificate }}</td>
                                <td>{{ $edu->date ? \Carbon\Carbon::parse($edu->date)->format('Y-m-d') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h6>သင်တန်းဆင်းမှုမှတ်တမ်း (Trainings)</h6>
                <table class="table table-sm table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>သင်တန်းအမည်</th>
                            <th>နေရာ</th>
                            <th>ကာလ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employee->trainings as $t)
                            <tr>
                                <td>{{ $t->course_name }}</td>
                                <td>{{ $t->location }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->start_date)->format('Y-m-d') }} မှ
                                    {{ \Carbon\Carbon::parse($t->end_date)->format('Y-m-d') }} ထိ</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">မရှိပါ</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <h6>ဂုဏ်ထူးဆောင်လက်မှတ်များ (Certificates)</h6>
                <div class="row">
                    @foreach ($employee->certificates as $cert)
                        <div class="col-md-4 mb-2">- {{ $cert->certificate_name }} ({{ $cert->issuer }})</div>
                    @endforeach
                </div>

                {{-- CATEGORY 4: SERVICE RECORD & EXPERIENCE --}}
                <h5 class="section-title">၄။ တာဝန်ထမ်းဆောင်မှုမှတ်တမ်း (Service Record)</h5>
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>ရာထူး</th>
                            <th>ဌာန</th>
                            <th>ကာလ</th>
                            <th>တည်နေရာ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employee->experiences as $exp)
                            <tr>
                                <td>{{ $exp->position }}</td>
                                <td>{{ $exp->department }}</td>
                                <td>{{ \Carbon\Carbon::parse($exp->from_date)->format('Y-m-d') }} မှ
                                    {{ $exp->is_current ? 'ယနေ့ထိ' : \Carbon\Carbon::parse($exp->to_date)->format('Y-m-d') }}
                                </td>
                                <td>{{ $exp->location }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h5 class="section-title mt-4">စစ်ဘက်ဆိုင်ရာ အချက်အလက်များ (Military Info)</h5>
                <div class="card p-3 shadow-sm border-0 bg-light">
                    <div class="row">
                        <div class="col-md-4">
                            <p><span class="info-label">ကိုယ်ပိုင်အမှတ်:</span> {{ $employee->badge_no ?? '-' }}</p>
                            <p><span class="info-label">တပ်ထဲဝင်သည့်ရက်:</span>
                                {{ $employee->entry_date ? \Carbon\Carbon::parse($employee->entry_date)->format('Y-m-d') : '-' }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><span class="info-label">ဗိုလ်လောင်းသင်တန်းအမှတ်စဉ်</span>
                                {{ $employee->batch_class_no ? $employee->batch_class_no : '-' }}
                            </p>
                            <p><span class="info-label">ပြန်တမ်းဝင်ဖြစ်သည့်နေ့:</span>
                                {{ $employee->date_comission ? \Carbon\Carbon::parse($employee->date_comission)->format('Y-m-d') : '-' }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><span class="info-label">တပ်ထွက်သည့်နေ့:</span>
                                {{ $employee->date_discharge ? \Carbon\Carbon::parse($employee->date_discharge)->format('Y-m-d') : '-' }}
                            </p>
                        </div>
                        <div class="col-md-12">
                            <hr>
                             <p><span class="info-label">အငြမ်းစားလစာ:</span> {{ $employee->pension ?? '-' }}</p>
                            <p><span class="info-label">အမှုထမ်းဆောင်ခဲ့သောတပ်များ:</span>
                                {{ $employee->units_served ?? '-' }}</p>
                            <p><span class="info-label">တပ်တွင်းရာဇဝင်အကျဉ်း/ပြစ်မှု:</span> {{ $employee->disciplinary_record ?? '-' }}
                            </p>
                             <p><span class="info-label">တပ်ထွက်သည့်အကြောင်းအရင်း:</span>
                                {{ $employee->reason_discharge ?? '-' }}</p>
                           
                        </div>
                    </div>
                </div>

                {{-- CATEGORY 5: TAB 5 BACKGROUND & ABROAD --}}
                <h5 class="section-title">၅။ ကိုယ်ရေးရာဇဝင်နှင့် နောက်ခံအချက်အလက်များ (Background)</h5>
                @if ($employee->personalRecord)
                    <div class="bg-light-box mb-4">
                        <div class="row">
                            <div class="col-12 mb-2"><strong>ကျောင်းများ:</strong> {{ $employee->personalRecord->schools }}
                            </div>
                            <div class="col-12 mb-2"><strong>ထူးခြားချက်:</strong>
                                {{ $employee->personalRecord->school_voluntary }}</div>
                            <div class="col-md-6"><strong>ဝါသနာ:</strong> {{ $employee->personalRecord->hobbies }}</div>
                            <div class="col-md-6"><strong>ပြစ်မှုမှတ်တမ်း:</strong>
                                {{ $employee->personalRecord->has_criminal_rec ? 'ရှိသည်' : 'မရှိပါ' }}</div>
                        </div>
                    </div>
                @endif

                <h6>နိုင်ငံခြားသို့ သွားရောက်ခဲ့ဖူးခြင်း (Abroad History)</h6>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>နိုင်ငံ</th>
                            <th>အကြောင်းရင်း</th>
                            <th>တွေ့ဆုံသူ</th>
                            <th>ကာလ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employee->abroads as $ab)
                            <tr>
                                <td>{{ $ab->country }}</td>
                                <td>{{ $ab->reason }}</td>
                                <td>{{ $ab->host_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($ab->departure_date)->format('Y-m-d') }} /
                                    {{ \Carbon\Carbon::parse($ab->arrival_date)->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">မရှိပါ</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="text-center mt-5">
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </section>
@endsection
