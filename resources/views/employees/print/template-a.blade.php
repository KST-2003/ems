<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <title>{{ $employee->name }} - လျှို့ဝှက် ကိုယ်ရေးမှတ်တမ်း</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Pyidaungsu', 'Myanmar Text', sans-serif;
            font-size: 14px;
            line-height: 1.8;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .page {
            width: 100%;
            position: relative;
        }

        /* ── Header ── */
        .header-secret {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .header-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        /* ── Photo box ── */
        .photo-box {
            position: absolute;
            top: 100px;
            right: 10px;
            width: 120px;
            height: 140px;
            border: 2px solid #000;
        }
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── Numbered info list ── */
        .info-list {
            margin-right: 140px; /* leave room for photo */
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-num {
            min-width: 42px;
            font-weight: normal;
        }
        .info-label {
            min-width: 220px;
        }
        .info-dash {
            margin: 0 8px;
        }
        .info-value {
            flex: 1;
        }
        .info-gap {
            margin-bottom: 20px;
        }

        /* ── Section titles ── */
        .section-title {
            font-size: 14px;
            margin: 20px 0 6px 0;
        }

        /* ── Tables ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table th,
        table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
            font-size: 13px;
        }
        table th {
            font-weight: bold;
        }

        /* ── Signature block ── */
        .signature-note {
            margin-top: 20px;
            margin-bottom: 16px;
        }
        .sig-block {
            margin-left: 160px;
        }
        .sig-row {
            display: flex;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .sig-label {
            min-width: 180px;
        }
        .sig-sep {
            margin: 0 6px;
        }
        .sig-space {
            margin-bottom: 40px;
        }

        /* ── Date line ── */
        .date-line {
            margin-top: 24px;
            font-size: 14px;
        }

        /* ── Page footer secret stamp ── */
        .footer-secret {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-top: 30px;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">
<div class="page">

    {{-- ── Secret header ── --}}
    <div class="header-secret">လျှို့ဝှက်</div>
    <div class="header-title">ကိုယ်ရေးမှတ်တမ်း</div>

    {{-- ── Photo box ── --}}
    <div class="photo-box">
        @if(!empty($employee->profile_image_url))
            <img src="{{ $employee->profile_image_url }}" alt="Profile Photo">
        @endif
    </div>

    {{-- ── Info list (items 1–13) ── --}}
    <div class="info-list">

        <div class="info-row">
            <span class="info-num">၁။</span>
            <span class="info-label">အမည်(ကျား/မ)</span>
            <span class="info-dash">–</span>
            <span class="info-value">{{ $employee->name ?? '-' }}</span>
        </div>

        <div class="info-row">
            <span class="info-num">၂။</span>
            <span class="info-label">ဝန်ထမ်းအမှတ်</span>
            <span class="info-dash">–</span>
            <span class="info-value">{{ $employee->employee_id ?? '-' }}</span>
        </div>

        <div class="info-row">
            <span class="info-num">၃။</span>
            <span class="info-label">မွေးနေ့(ရက်/လ/နှစ်)</span>
            <span class="info-dash">–</span>
            <span class="info-value">{{ $employee->eng_dob ? $employee->eng_dob->format('d/m/Y') : '-' }}</span>
        </div>

        <div class="info-row">
            <span class="info-num">၄။</span>
            <span class="info-label">လူမျိုး/ဘာသာ</span>
            <span class="info-dash">–</span>
            <span class="info-value">{{ $employee->nationality ?? '-' }} / {{ $employee->religion ?? '-' }}</span>
        </div>

        <div class="info-row">
            <span class="info-num">၅။</span>
            <span class="info-label">အဘအမည်</span>
            <span class="info-dash">–</span>
            <span class="info-value">{{ $employee->father_name ?? '-' }}</span>
        </div>

        <div class="info-row">
            <span class="info-num">၆။</span>
            <span class="info-label">အမိအမည်</span>
            <span class="info-dash">–</span>
            <span class="info-value">{{ $employee->mother_name ?? '-' }}</span>
        </div>

        <div class="info-row">
            <span class="info-num">၇။</span>
            <span class="info-label">နိုင်ငံသားစိစစ်ရေးအမှတ်</span>
            <span class="info-dash">–</span>
            <span class="info-value">{{ $employee->nrc ?? '-' }}</span>
        </div>

        <div class="info-row">
            <span class="info-num">၈။</span>
            <span class="info-label">ဇနီး/ခင်ပွန်းအမည်</span>
            <span class="info-dash">–</span>
            <span class="info-value">{{ $employee->spouse_name ?? '-' }}</span>
        </div>

        <div class="info-row">
            <span class="info-num">၉။</span>
            <span class="info-label">သား/သမီးအမည်</span>
            <span class="info-dash">–</span>
            <span class="info-value">
                @if($employee->children->isNotEmpty())
                    @foreach($employee->children as $child)
                        {{ $child->name }}@if(!$loop->last), @endif
                    @endforeach
                @else
                    -
                @endif
            </span>
        </div>

        <div class="info-row info-gap">
            <span class="info-num">၁၀။</span>
            <span class="info-label">လိပ်စာ</span>
            <span class="info-dash">–</span>
            <span class="info-value">{{ $employee->current_address ?? ($employee->permanent_address ?? '-') }}</span>
        </div>

        <div class="info-row info-gap">
            <span class="info-num">၁၁။</span>
            <span class="info-label">ပညာအရည်အချင်း</span>
            <span class="info-dash">–</span>
            <span class="info-value">
                @if($employee->educations->isNotEmpty())
                    @foreach($employee->educations as $edu)
                        {{ $edu->highest_certificate }} ({{ $edu->institution_name }})@if(!$loop->last), @endif
                    @endforeach
                @else
                    -
                @endif
            </span>
        </div>

        <div class="info-row info-gap">
            <span class="info-num">၁၂။</span>
            <span class="info-label">လက်ရှိရာထူး/လစာနှုန်း/ဌာန</span>
            <span class="info-dash">–</span>
            <span class="info-value">
                {{ $employee->current_position ?? '-' }} /
                {{ $employee->salary ?? '-' }} /
                {{ $employee->department ?? '-' }}
            </span>
        </div>

        <div class="info-row info-gap">
            <span class="info-num">၁၃။</span>
            <span class="info-label">သွေးအုပ်စု</span>
            <span class="info-dash">–</span>
            <span class="info-value">{{ $employee->blood_type ?? '-' }}</span>
        </div>

    </div>{{-- end info-list --}}

    {{-- ── 14. Service history ── --}}
    <div class="section-title">၁၄။ နိုင်ငံ့ဝန်ထမ်းတာဝန်ထမ်းဆောင်မှုမှတ်တမ်း(စစ်ဘက်/နယ်ဘက်)</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width:8%;">စဉ်</th>
                <th rowspan="2" style="width:35%;">ရာထူး/ဌာန</th>
                <th colspan="2">တာဝန်ထမ်းဆောင်သည့်ကာလ</th>
                <th rowspan="2" style="width:25%;">နေရာ/ဒေသ</th>
            </tr>
            <tr>
                <th style="width:16%;">မှ</th>
                <th style="width:16%;">ထိ</th>
            </tr>
        </thead>
        <tbody>
            @php
                $pastExperiences    = $employee->experiences->where('is_current', false)->sortBy('from_date');
                $currentExperience  = $employee->experiences->where('is_current', true)->first();
            @endphp

            @forelse($pastExperiences as $exp)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align:left;">{{ $exp->position ?? '-' }} / {{ $exp->department ?? '-' }}</td>
                    <td>{{ $exp->from_date?->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ $exp->to_date?->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ $exp->location ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="height:30px;"></td></tr>
            @endforelse

            @if($currentExperience)
                <tr>
                    <td>{{ $pastExperiences->count() + 1 }}</td>
                    <td style="text-align:left;">{{ $currentExperience->position ?? $employee->current_position ?? '-' }} / {{ $currentExperience->department ?? $employee->department ?? '-' }}</td>
                    <td>{{ $currentExperience->from_date?->format('d-m-Y') ?? '-' }}</td>
                    <td>ယနေ့ထိ</td>
                    <td>{{ $currentExperience->location ?? '-' }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- ── 15. Domestic training ── --}}
    <div class="section-title">၁၅။ ပြည်တွင်းသင်တန်းများ တက်ရောက်မှု</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width:8%;">စဉ်</th>
                <th rowspan="2" style="width:40%;">သင်တန်းအမည်</th>
                <th colspan="2">တက်ရောက်သည့်ကာလ</th>
                <th rowspan="2" style="width:25%;">နေရာ/ဒေသ</th>
            </tr>
            <tr>
                <th style="width:13%;">မှ</th>
                <th style="width:13%;">ထိ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employee->trainings->where('training_type', 'domestic') as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align:left;">{{ $t->course_name }}</td>
                    <td>{{ $t->start_date?->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ $t->end_date?->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ $t->location ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="height:30px;"></td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── 16. Foreign training ── --}}
    <div class="section-title">၁၆။ ပြည်ပသင်တန်းများ တက်ရောက်မှု</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width:8%;">စဉ်</th>
                <th rowspan="2" style="width:40%;">သင်တန်းအမည်</th>
                <th colspan="2">တက်ရောက်သည့်ကာလ</th>
                <th rowspan="2" style="width:25%;">နေရာ/နိုင်ငံ</th>
            </tr>
            <tr>
                <th style="width:13%;">မှ</th>
                <th style="width:13%;">ထိ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employee->trainings->where('training_type', 'foreign') as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align:left;">{{ $t->course_name }}</td>
                    <td>{{ $t->start_date?->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ $t->end_date?->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ $t->location ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="height:30px;"></td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── 17. Criminal record ── --}}
    {{-- Photo 2: cols = ပြစ်ဒဏ် | ပြစ်ဒဏ်ချမှတ်ရာသည့်အကြောင်းအရင်း | ကာလ (မှ / ထိ) --}}
    <div class="section-title">၁၇။ ပြစ်မှုမှတ်တမ်း</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width:18%;">ပြစ်ဒဏ်</th>
                <th rowspan="2" style="width:50%;">ပြစ်ဒဏ်ချမှတ်ရာသည့်အကြောင်းအရင်း</th>
                <th colspan="2">ပြစ်ဒဏ်ချမှတ်သည့် ကာလ</th>
            </tr>
            <tr>
                <th style="width:16%;">မှ</th>
                <th style="width:16%;">ထိ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employee->criminalRecords as $record)
                <tr>
                    <td>{{ $record->penalty_type ?? '-' }}</td>
                    <td style="text-align:left;">{{ $record->description ?? '-' }}</td>
                    <td>{{ $record->start_date?->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ $record->end_date?->format('d-m-Y') ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="height:30px;"></td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── 18. Awards / Honours ── --}}
    {{-- Photo 2: cols = စဉ် | ဘွဲ့ထူး၊ ဂုဏ်ထူး၊ တံဆိပ်အမည် | အမိန့်အမှတ်/ခုနှစ် --}}
    <div class="section-title">၁၈။ ချီးမြှင့်ခံရသည့် ဘွဲ့ထူး၊ ဂုဏ်ထူးတံဆိပ်များ</div>
    <table>
        <thead>
            <tr>
                <th style="width:8%;">စဉ်</th>
                <th style="width:62%;">ဘွဲ့ထူး ၊ ဂုဏ်ထူး၊ တံဆိပ်အမည်</th>
                <th style="width:30%;">အမိန့်အမှတ်/ခုနှစ်</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employee->certificates as $cert)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align:left;">{{ $cert->certificate_name ?? '-' }}</td>
                    <td>{{ $cert->order_number ?? ($cert->issue_date?->format('d-m-Y') ?? '-') }}</td>
                </tr>
            @empty
                <tr><td>–</td><td>–</td><td>–</td></tr>
                <tr><td colspan="3" style="height:28px;"></td></tr>
                <tr><td colspan="3" style="height:28px;"></td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── Certification statement ── --}}
    <div class="signature-note">
        အထက်ပါဖြည့်စွက်ချက်များ မှန်ကန်ကြောင်း လက်မှတ်ရေးထိုးပါသည်။
    </div>

    {{-- ── Signature block (Photo 2 layout: left-indented, vertical list) ── --}}
    <div class="sig-block">
        <div class="sig-row sig-space">
            <span class="sig-label">လက်မှတ်</span>
            <span class="sig-sep">|</span>
        </div>
        <div class="sig-row">
            <span class="sig-label">အမည်</span>
            <span class="sig-sep">|</span>
            <span>{{ $employee->name }}</span>
        </div>
        <div class="sig-row">
            <span class="sig-label">ရာထူး</span>
            <span class="sig-sep">|</span>
            <span>{{ $employee->current_position ?? '-' }}</span>
        </div>
        <div class="sig-row">
            <span class="sig-label">ဖုန်းနံပါတ်(ရုံး/လက်ကိုင်ဖုန်း)</span>
            <span class="sig-sep">|</span>
            <span>{{ $employee->phone ?? '-' }}</span>
        </div>
        <div class="sig-row">
            <span class="sig-label">အီးမေးလ်</span>
            <span class="sig-sep">|</span>
            <span>{{ $employee->email ?? '-' }}</span>
        </div>
    </div>

    {{-- ── Date line (bottom-left, Photo 2) ── --}}
    <div class="date-line">
        ရက်စွဲ၊ {{ now()->format('Y') }} ခုနှစ်၊ {{ now()->translatedFormat('F') }} လ &nbsp;&nbsp;&nbsp; ရက်
    </div>

    {{-- ── Footer secret stamp ── --}}
    <div class="footer-secret">လျှို့ဝှက်</div>

</div>
</body>
</html>