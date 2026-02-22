<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $employee->name ?? 'Employee' }} - ပုံစံ(၁) ကိုယ်ရေးရာဇဝင်</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm;
        }
        body {
            font-family: 'Pyidaungsu', 'Zawgyi-One', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #000;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .page-break { page-break-before: always; }
        
        .header-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        
        .photo-box {
            position: absolute;
            top: 40px;
            right: 0;
            width: 130px;
            height: 150px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Key-Value Form Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .col-num { width: 5%; text-align: left; }
        .col-label { width: 45%; }
        .col-value { width: 50%; }

        /* Data Grids */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 25px 0;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 13px;
        }
        .data-table th {
            text-align: center;
            font-weight: bold;
        }

        /* Signatures */
        .signature-block {
            margin-top: 40px;
            margin-left: 20%;
            width: 70%;
        }
        .sig-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .sig-label { width: 40%; }
        .sig-value { width: 60%; }

        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .photo-box { border: 1px solid #000 !important; }
        }
    </style>
</head>
<body onload="window.print()">

    {{-- Fetching Relationship Data that wasn't eagerly loaded in the controller --}}
    @php
        $fatherSiblings = $employee->parentSiblings->where('side', 'father');
        $motherSiblings = $employee->parentSiblings->where('side', 'mother');

        // Fetch spouse relatives using DB facade as they don't have a direct model relationship defined
        $spouseSiblings = \Illuminate\Support\Facades\DB::table('spouse_relatives')->where('employee_id', $employee->id)->where('type', 'sibling')->get();
        $spousePaternal = \Illuminate\Support\Facades\DB::table('spouse_relatives')->where('employee_id', $employee->id)->where('type', 'paternal')->get();
        $spouseMaternal = \Illuminate\Support\Facades\DB::table('spouse_relatives')->where('employee_id', $employee->id)->where('type', 'maternal')->get();
    @endphp

    <div style="position: relative;">
        <div class="text-right font-bold" style="font-size: 16px;">ပုံစံ(၁)</div>
        
        <div class="header-title">
            ကိုယ်ရေးရာဇဝင်<br>
            [နည်းဥပဒေ ၂၄(ခ)]
        </div>

        <div class="photo-box">
            @if(!empty($employee->profile_image_url))
                <img src="{{ $employee->profile_image_url }}" alt="Photo">
            @else
                <span style="color:#999; font-size: 12px;">ဓာတ်ပုံ</span>
            @endif
        </div>

        <table class="info-table">
            <tr>
                <td class="col-num">၁။</td>
                <td class="col-label">အမည်</td>
                <td class="col-value">- {{ $employee->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၂။</td>
                <td class="col-label">ငယ်အမည်</td>
                <td class="col-value">- {{ $employee->home_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၃။</td>
                <td class="col-label">အခြားအမည်</td>
                <td class="col-value">- {{ $employee->nick_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၄။</td>
                <td class="col-label">အသက်(မွေးသက္ကရာဇ်)</td>
                <td class="col-value">
                    - {{ $employee->eng_dob ? \Carbon\Carbon::parse($employee->eng_dob)->format('d-m-Y') : '-' }}
                    @if($employee->eng_dob)
                        ({{ \Carbon\Carbon::parse($employee->eng_dob)->age }} နှစ်)
                    @endif
                </td>
            </tr>
            <tr>
                <td class="col-num">၅။</td>
                <td class="col-label">လူမျိုးနှင့်ကိုးကွယ်သည့်ဘာသာ</td>
                <td class="col-value">- {{ $employee->nationality ?? '-' }} / {{ $employee->religion ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၆။</td>
                <td class="col-label">အရပ်အမြင့်</td>
                <td class="col-value">- {{ $employee->height ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၇။</td>
                <td class="col-label">ဆံပင်အရောင်</td>
                <td class="col-value">- {{ $employee->hair_color ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၈။</td>
                <td class="col-label">မျက်စိအရောင်</td>
                <td class="col-value">- {{ $employee->eye_color ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၉။</td>
                <td class="col-label">ထင်ရှားသည့်အမှတ်အသား</td>
                <td class="col-value">- {{ $employee->notable_trade ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၁၀။</td>
                <td class="col-label">အသားအရောင်</td>
                <td class="col-value">- {{ $employee->skin_color ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၁၁။</td>
                <td class="col-label">ကိုယ်အလေးချိန်</td>
                <td class="col-value">- {{ $employee->weight ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၁၂။</td>
                <td class="col-label">မွေးဖွားရာဇာတိ</td>
                <td class="col-value">- {{ $employee->pob ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၁၃။</td>
                <td class="col-label">နိုင်ငံသားစိစစ်ရေးကတ်ပြားအမှတ်</td>
                <td class="col-value">- {{ $employee->nrc ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၁၄။</td>
                <td class="col-label">ယခုနေရပ်လိပ်စာအပြည့်အစုံ</td>
                <td class="col-value">- {{ $employee->current_address ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၁၅။</td>
                <td class="col-label">အမြဲတမ်းနေရပ်လိပ်စာအပြည့်အစုံ</td>
                <td class="col-value">- {{ $employee->permanent_address ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၁၆။</td>
                <td class="col-label">ယခင်နေခဲ့ဖူးသောဒေသနှင့်<br>နေထိုင်ခဲ့သည့်လိပ်စာများ</td>
                <td class="col-value">- {{ $employee->old_address ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-num">၁၇။</td>
                <td colspan="2">တပ်မတော်သို့ဝင်ရောက်ခဲ့လျှင်/တပ်မတော်သားဖြစ်လျှင် -</td>
            </tr>
            <tr>
                <td></td>
                <td>(က) ကိုယ်ပိုင်အမှတ်</td>
                <td>- {{ $employee->badge_no ?? '-' }}</td>
            </tr>
            <tr>
                <td></td>
                <td>(ခ) တပ်သို့ဝင်သည့်နေ့</td>
                <td>- {{ $employee->entry_date ? \Carbon\Carbon::parse($employee->entry_date)->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <td></td>
                <td>(ဂ) ဗိုလ်လောင်းသင်တန်းအမှတ်စဉ်</td>
                <td>- {{ $employee->batch_class_no ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>
    <table class="info-table">
        <tr>
            <td class="col-num"></td>
            <td class="col-label">(ဃ) ပြန်တမ်းဝင်ဖြစ်သည့်နေ့</td>
            <td class="col-value">- {{ $employee->date_comission ? \Carbon\Carbon::parse($employee->date_comission)->format('d-m-Y') : '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>(င) တပ်ထွက်သည့်နေ့</td>
            <td>- {{ $employee->date_discharge ? \Carbon\Carbon::parse($employee->date_discharge)->format('d-m-Y') : '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>(စ) ထွက်သည့်အကြောင်း</td>
            <td>- {{ $employee->reason_discharge ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>(ဆ) အမှုထမ်းဆောင်ခဲ့သောတပ်များ</td>
            <td>- {{ $employee->units_served ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>(ဇ) တပ်တွင်းရာဇဝင်အကျဉ်း/ပြစ်မှု</td>
            <td>- {{ $employee->disciplinary_record ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>(ဈ) အငြိမ်းစားလစာ</td>
            <td>- {{ $employee->pension ?? '-' }}</td>
        </tr>

        <tr>
            <td class="col-num">၁၈။</td>
            <td class="col-label">ပညာအရည်အချင်း</td>
            <td class="col-value">
                - @if($employee->educations->isNotEmpty())
                    {{ $employee->educations->pluck('degree_certificate')->filter()->implode('၊ ') }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="col-num">၁၉။</td>
            <td class="col-label">အဘအမည်၊ လူမျိုး၊ ကိုးကွယ်သည့်ဘာသာ၊<br>ဇာတိနှင့်အလုပ်အကိုင်</td>
            <td class="col-value">
                - {{ $employee->father_name ?? '-' }} ၊ {{ $employee->father_nationality ?? '-' }} ၊ {{ $employee->father_religion ?? '-' }} ၊ {{ $employee->father_pob ?? '-' }} ၊ {{ $employee->father_job ?? '-' }}
            </td>
        </tr>
        <tr>
            <td class="col-num">၂၀။</td>
            <td class="col-label">၎င်း၏နေရပ်လိပ်စာအပြည့်အစုံ</td>
            <td class="col-value">- {{ $employee->father_address ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၂၁။</td>
            <td class="col-label">အမိအမည်၊ လူမျိုး၊ ကိုးကွယ်သည့်ဘာသာ၊<br>ဇာတိနှင့်အလုပ်အကိုင်</td>
            <td class="col-value">
                - {{ $employee->mother_name ?? '-' }} ၊ {{ $employee->mother_nationality ?? '-' }} ၊ {{ $employee->mother_religion ?? '-' }} ၊ {{ $employee->mother_pob ?? '-' }} ၊ {{ $employee->mother_job ?? '-' }}
            </td>
        </tr>
        <tr>
            <td class="col-num">၂၂။</td>
            <td class="col-label">၎င်း၏နေရပ်လိပ်စာအပြည့်အစုံ</td>
            <td class="col-value">- {{ $employee->mother_address ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၂၃။</td>
            <td class="col-label">ကာယကံရှင်မွေးဖွားချိန်၌ မိဘနှစ်ပါးသည်<br>နိုင်ငံသား ဟုတ်/မဟုတ်</td>
            <td class="col-value">- {{ $employee->is_parent_citizen ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၂၄။</td>
            <td class="col-label">လက်ရှိအလုပ်အကိုင်နှင့်အဆင့်</td>
            <td class="col-value">- {{ $employee->current_position ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၂၅။</td>
            <td class="col-label">လက်ရှိရာထူးရသည့်နေ့</td>
            <td class="col-value">- {{ $employee->current_position_date ? \Carbon\Carbon::parse($employee->current_position_date)->format('d-m-Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၂၆။</td>
            <td class="col-label">လက်ရှိအလုပ်အကိုင်ရလာပုံ</td>
            <td class="col-value">- {{ $employee->position_acquire ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၂၇။</td>
            <td class="col-label">ပြိုင်အရွေးခံ(သို့) တိုက်ရိုက်ခန့်</td>
            <td class="col-value">- {{ $employee->acquire_type ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၂၈။</td>
            <td class="col-label">လစာဝင်ငွေ</td>
            <td class="col-value">- {{ $employee->salary ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၂၉။</td>
            <td class="col-label">ဌာန၊နေရာ</td>
            <td class="col-value">- {{ $employee->department_place ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၃၀။</td>
            <td class="col-label">အလုပ်အကိုင်အတွက်ထောက်ခံသူများ</td>
            <td class="col-value">- {{ $employee->job_refer ?? '-' }}</td>
        </tr>
    </table>

    <div style="margin-top: 15px;">
        <span>၃၁။ ယခင်လုပ်ကိုင်ဖူးသည့်အလုပ်အကိုင်</span>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="8%">စဉ်</th>
                    <th width="40%">အဆင့်</th>
                    <th width="25%">တပ်/ဌာန</th>
                    <th width="27%">နေရာ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employee->pastExperiences as $index => $exp)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $exp->position ?? '-' }}</td>
                        <td>{{ $exp->department ?? '-' }}</td>
                        <td>{{ $exp->location ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">-</td></tr>
                    <tr><td colspan="4" style="height:25px;"></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>
    <div>
        <span>၃၂။ ညီအစ်ကိုမောင်နှမများ</span>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="8%">စဉ်</th>
                    <th>အမည်</th>
                    <th>လူမျိုး/ဘာသာ</th>
                    <th>ဇာတိ</th>
                    <th>အလုပ်အကိုင်</th>
                    <th>နေရပ်လိပ်စာ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employee->relatives as $index => $rel)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $rel->name }}</td>
                        <td>{{ $rel->nationality_religion }}</td>
                        <td>{{ $rel->hometown }}</td>
                        <td>{{ $rel->job }}</td>
                        <td>{{ $rel->location }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">-</td></tr>
                    <tr><td colspan="6" style="height:25px;"></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        <span>၃၃။ အဘ၏ညီအစ်ကိုမောင်နှမများ</span>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="8%">စဉ်</th>
                    <th>အမည်</th>
                    <th>လူမျိုး/ဘာသာ</th>
                    <th>ဇာတိ</th>
                    <th>အလုပ်အကိုင်</th>
                    <th>နေရပ်လိပ်စာ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fatherSiblings as $index => $fsib)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $fsib->name }}</td>
                        <td>{{ $fsib->nationality_religion }}</td>
                        <td>{{ $fsib->hometown }}</td>
                        <td>{{ $fsib->job }}</td>
                        <td>{{ $fsib->location }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">-</td></tr>
                    <tr><td colspan="6" style="height:25px;"></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        <span>၃၄။ အမိ၏ညီအစ်ကိုမောင်နှမများ</span>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="8%">စဉ်</th>
                    <th>အမည်</th>
                    <th>လူမျိုး/ဘာသာ</th>
                    <th>ဇာတိ</th>
                    <th>အလုပ်အကိုင်</th>
                    <th>နေရပ်လိပ်စာ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($motherSiblings as $index => $msib)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $msib->name }}</td>
                        <td>{{ $msib->nationality_religion }}</td>
                        <td>{{ $msib->hometown }}</td>
                        <td>{{ $msib->job }}</td>
                        <td>{{ $msib->location }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">-</td></tr>
                    <tr><td colspan="6" style="height:25px;"></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>
    <div>
        <span>၃၅။ ခင်ပွန်း/ဇနီးသည်</span>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="8%">စဉ်</th>
                    <th>အမည်</th>
                    <th>လူမျိုး/ဘာသာ</th>
                    <th>ဇာတိ</th>
                    <th>အလုပ်အကိုင်</th>
                    <th>နေရပ်လိပ်စာ</th>
                </tr>
            </thead>
            <tbody>
                @if($employee->spouse && $employee->spouse->name)
                    <tr>
                        <td class="text-center">၁</td>
                        <td>{{ $employee->spouse->name }}</td>
                        <td>{{ $employee->spouse->nationality_religion }}</td>
                        <td>{{ $employee->spouse->hometown }}</td>
                        <td>{{ $employee->spouse->job }}</td>
                        <td>{{ $employee->spouse->address }}</td>
                    </tr>
                @else
                    <tr><td colspan="6" class="text-center">-</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <div>
        <span>၃၆။ သားသမီးများ</span>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="8%">စဉ်</th>
                    <th>အမည်</th>
                    <th>လူမျိုး/ဘာသာ</th>
                    <th>ဇာတိ</th>
                    <th>အလုပ်အကိုင်</th>
                    <th>နေရပ်လိပ်စာ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employee->children as $index => $child)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $child->name }}</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">-</td></tr>
                    <tr><td colspan="6" style="height:25px;"></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        <span>၃၇။ ခင်ပွန်း/ဇနီးသည်၏ ညီအစ်ကိုမောင်နှမများ</span>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="8%">စဉ်</th>
                    <th>အမည်</th>
                    <th>လူမျိုး/ဘာသာ</th>
                    <th>ဇာတိ</th>
                    <th>အလုပ်အကိုင်</th>
                    <th>နေရပ်လိပ်စာ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spouseSiblings as $index => $ssib)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $ssib->name }}</td>
                        <td>{{ $ssib->nationality_religion }}</td>
                        <td>{{ $ssib->hometown }}</td>
                        <td>{{ $ssib->job }}</td>
                        <td>{{ $ssib->address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">-</td></tr>
                    <tr><td colspan="6" style="height:25px;"></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>
    <div>
        <span>၃၈။ ခင်ပွန်း/ဇနီးသည် အဘနှင့် ညီအစ်ကိုမောင်နှမများ</span>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="8%">စဉ်</th>
                    <th>အမည်</th>
                    <th>လူမျိုး/ဘာသာ</th>
                    <th>ဇာတိ</th>
                    <th>အလုပ်အကိုင်</th>
                    <th>နေရပ်လိပ်စာ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spousePaternal as $index => $sp)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $sp->name }}</td>
                        <td>{{ $sp->nationality_religion }}</td>
                        <td>{{ $sp->hometown }}</td>
                        <td>{{ $sp->job }}</td>
                        <td>{{ $sp->address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">-</td></tr>
                    <tr><td colspan="6" style="height:25px;"></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        <span>၃၉။ ခင်ပွန်း/ဇနီးသည် အမိနှင့် ညီအစ်ကိုမောင်နှမများ</span>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="8%">စဉ်</th>
                    <th>အမည်</th>
                    <th>လူမျိုး/ဘာသာ</th>
                    <th>ဇာတိ</th>
                    <th>အလုပ်အကိုင်</th>
                    <th>နေရပ်လိပ်စာ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spouseMaternal as $index => $sm)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $sm->name }}</td>
                        <td>{{ $sm->nationality_religion }}</td>
                        <td>{{ $sm->hometown }}</td>
                        <td>{{ $sm->job }}</td>
                        <td>{{ $sm->address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">-</td></tr>
                    <tr><td colspan="6" style="height:25px;"></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        <p>၄၀။ မိမိနှင့် မိမိ၏ ခင်ပွန်း/ဇနီးသည်၏ မိဘ၊ ညီအစ်ကိုမောင်နှမများ၊ သားသမီးများသည် နိုင်ငံရေးပါတီများတွင် ဝင်ရောက်ဆောင်ရွက်မှု ရှိ/မရှိ (ရှိပါက အသေးစိတ်ဖော်ပြရန်)။</p>
        <p style="margin-left: 30px;">
            - {{ $employee->isin_election ? 'ရှိပါသည် (' . $employee->election_description . ')' : 'မရှိပါ' }}
        </p>
    </div>

    <div class="page-break"></div>
    <div class="header-title" style="margin-top: 20px;">
        ငယ်စဉ်မှယခုအချိန်ထိကိုယ်ရေးရာဇဝင်
    </div>

    @php
        $pr = $employee->personalRecord;
    @endphp

    <table class="info-table" style="margin-top: 20px;">
        <tr>
            <td class="col-num">၁။</td>
            <td class="col-label">နေခဲ့ဖူးသောကျောင်းများ<br>(ခုနှစ်၊သက္ကရာဇ်ဖော်ပြရန်)</td>
            <td class="col-value">- {{ $pr->schools ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၂။</td>
            <td class="col-label">နောက်ဆုံးအောင်မြင်ခဲ့သည့်ကျောင်း<br>/အတန်း၊ခုံအမှတ်၊ ဘာသာရပ်အတိအကျဖော်ပြရန်</td>
            <td class="col-value">- {{ $pr->latest_school ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၃။</td>
            <td class="col-label">ကျောင်းသားဘဝတွင် နိုင်ငံရေး/မြို့ရေး/ရွာရေး<br>ဆောင်ရွက်မှုများနှင့်အဆင့်အတန်း၊ တာဝန်</td>
            <td class="col-value">- {{ $pr->school_voluntary ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၄။</td>
            <td class="col-label">ဝါသနာပါပြီး၊လေ့လာလိုက်စားခဲ့သော<br>ကျန်းမာရေးကစားခုန်စားမှုများ၊ အနုပညာဆိုင်ရာ<br>အတီးအမှုတ်များ၊ ပညာရေး၊ စက်မှုလက်မှု</td>
            <td class="col-value">- {{ $pr->hobbies ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၅။</td>
            <td class="col-label">လုပ်ကိုင်ခဲ့သော အလုပ်အကိုင်များနှင့်<br>ဌာန/မြို့နယ်</td>
            <td class="col-value">- {{ $pr->jobs_dept ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၆။</td>
            <td class="col-label">တောခိုခဲ့ဖူးလျှင် (သို့) သောင်းကျန်းသူများ<br>ကြီးစိုးသောနယ်မြေတွင်နေခဲ့ဖူးလျှင်<br>လုပ်ကိုင်ဆောင်ရွက်ချက်များကိုဖော်ပြပါ</td>
            <td class="col-value">- {{ $pr->refugee ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၇။</td>
            <td class="col-label">အလုပ်အကိုင်ပြောင်းရွှေ့ခဲ့သော<br>အကြောင်းအကျိုးနှင့်လစာ</td>
            <td class="col-value">- {{ $pr->jobtransfer_desc ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၈။</td>
            <td class="col-label">အမှုထမ်းနေစဉ် (သို့) ကိုယ်ပိုင်အလုပ်အကိုင်<br>ဆောင်ရွက်နေစဉ် နိုင်ငံရေး၊ မြို့/ရွာရေး<br>ဆောင်ရွက်မှုများ၊ ဆောင်ရွက်နေစဉ်<br>အဆင့်အတန်းနှင့်တာဝန်</td>
            <td class="col-value">- {{ $pr->citizen_duties ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၉။</td>
            <td class="col-label">စစ်ဘက်/နယ်ဘက်/ရဲဘက်နှင့် နိုင်ငံရေးဘက်တွင်<br>ခင်မင်ရင်းနှီးသော မိတ်ဆွေများ ရှိ မရှိ</td>
            <td class="col-value">- {{ $pr->relatives_officials ?? '-' }}</td>
        </tr>
    </table>

    <div class="page-break"></div>
    <div>
        <span>၁၀။ နိုင်ငံခြားသို့ သွားရောက်ခဲ့ဖူးလျှင်</span>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="8%">စဉ်</th>
                    <th>သွားရောက်ခဲ့သည့်နိုင်ငံ</th>
                    <th>သွားရောက်ခဲ့သည့်အကြောင်း</th>
                    <th>တွေ့ဆုံခဲ့သည့်ကုမ္ပဏီ/<br>လူပုဂ္ဂိုလ်ဌာန</th>
                    <th>သွား/ပြန်သည့်နေ့</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employee->abroads as $index => $ab)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $ab->country }}</td>
                        <td>{{ $ab->reason }}</td>
                        <td>{{ $ab->host_name }}</td>
                        <td class="text-center">
                            {{ $ab->departure_date ? \Carbon\Carbon::parse($ab->departure_date)->format('d-m-Y') : '-' }} <br>to<br>
                            {{ $ab->arrival_date ? \Carbon\Carbon::parse($ab->arrival_date)->format('d-m-Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">-</td></tr>
                    <tr><td colspan="5" style="height:25px;"></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <table class="info-table" style="margin-top: 20px;">
        <tr>
            <td class="col-num">၁၁။</td>
            <td class="col-label">မိမိနှင့်ခင်မင်ရင်းနှီးသောနိုင်ငံခြားသားရှိမရှိ၊<br>ရှိကမည်သည့်အလုပ်အကိုင်၊ လူမျိုး၊ တိုင်းပြည်၊<br>မည်ကဲ့သို့ရင်းနှီးသည်</td>
            <td class="col-value">- {{ $pr->foreign_friends_desc ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၁၂။</td>
            <td class="col-label">မိမိအားထောက်ခံသည့်ပုဂ္ဂိုလ် (စစ်ဘက်/<br>နယ်ဘက်အရာရှိ၊ မြို့နယ်/ကျေးရွာ/<br>ရပ်ကွက်အုပ်ချုပ်ရေးမှူး)</td>
            <td class="col-value">- {{ $pr->referal_officials ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">၁၃။</td>
            <td class="col-label">ရာဇဝတ်ပြစ်မှုခံရခြင်း ရှိ/မရှိ</td>
            <td class="col-value">
                - {{ isset($pr->has_criminal_rec) && $pr->has_criminal_rec ? 'ရှိပါသည်' : 'မရှိပါ' }}
            </td>
        </tr>
    </table>

    <div style="margin-top: 30px; text-indent: 50px;">
        အထက်ပါဇယားကွက်များအတွင်း ဖြည့်စွက်ရေးသွင်းထားသော အကြောင်းအရာများအား မှန်ကန်ကြောင်းတာဝန်ခံ လက်မှတ်ရေးထိုးပါသည်။
    </div>

    <div class="signature-block">
        <div class="sig-row">
            <div class="sig-label">လက်မှတ်</div>
            <div class="sig-value">။</div>
        </div>
        <div class="sig-row">
            <div class="sig-label">ကိုယ်ပိုင်အမှတ်(သို့မဟုတ်)</div>
            <div class="sig-value">။ &nbsp;&nbsp;&nbsp; {{ $employee->employee_id ?? '-' }}</div>
        </div>
        <div class="sig-row">
            <div class="sig-label">နိုင်ငံသားစိစစ်ရေးကတ်ပြားအမှတ်</div>
            <div class="sig-value">။ &nbsp;&nbsp;&nbsp; {{ $employee->nrc ?? '-' }}</div>
        </div>
        <div class="sig-row">
            <div class="sig-label">အဆင့်၊ ရာထူး</div>
            <div class="sig-value">။ &nbsp;&nbsp;&nbsp; {{ $employee->current_position ?? '-' }}</div>
        </div>
        <div class="sig-row">
            <div class="sig-label">အမည်</div>
            <div class="sig-value">။ &nbsp;&nbsp;&nbsp; {{ $employee->name ?? '-' }}</div>
        </div>
        <div class="sig-row">
            <div class="sig-label">တပ်/ဌာန</div>
            <div class="sig-value">။ &nbsp;&nbsp;&nbsp; {{ $employee->department ?? '-' }} ({{ $employee->department_place ?? '' }})</div>
        </div>
        <div class="sig-row" style="margin-top: 20px;">
            <div class="sig-label">ရက်စွဲ၊ &nbsp;&nbsp; {{ toMyanmarDate(now()) }}</div>
        </div>
    </div>

</body>
</html>