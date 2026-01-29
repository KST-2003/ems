<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $employee->name }} - လျှို့ဝှက် ကိုယ်ရေးမှတ်တမ်း</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm;
        }
        body {
            font-family: 'Pyidaungsu', 'Zawgyi-One', sans-serif;
            font-size: 15px;
            line-height: 1.8;
            color: #000;
        }
        .container {
            width: 100%;
            position: relative;
        }
        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 50px;
            margin-top: 30px;
        }
        .photo-box {
            position: absolute;
            top: 120px;
            right: 20mm;
            width: 140px;
            height: 140px;
            border: 2px solid #000;
            padding: 4px;
            background: #fff;
        }
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .info-list ol {
            padding-left: 35px;
            margin-left: 20px;
        }
        .info-list li {
            margin-bottom: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            margin-bottom: 35px;
        }
        table th,
        table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
            vertical-align: middle;
            font-size: 14px;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin: 30px 0 10px 0;
        }
        .signature-block {
            width: 400px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 60px;
        }
        .sig-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 15px;
            font-size: 16px;
            line-height: 1.5;
        }
        .sig-label {
            white-space: nowrap;
            font-weight: bold;
            color: #333;
        }
        .sig-separator {
            flex-grow: 1;
            border-bottom: 1px solid #000;
            margin: 0 10px;
            min-width: 100px;
        }
        .sig-row.signature-space {
            margin-bottom: 50px;
        }
        .date-line {
            text-align: center;
            margin-top: 40px;
            font-size: 16px;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .photo-box {
                border: 2px solid #000 !important;
            }
        }
    </style>
</head>
<body onload="window.print()">
<div class="container">
    <div class="header">
        လျှို့ဝှက်<br>
        ကိုယ်ရေးမှတ်တမ်း
    </div>

    <!-- Photo Box -->
    <div class="photo-box">
        <img src="{{ $employee->profile_image_url }}" alt="Profile Photo">
    </div>

    <div class="info-list">
        <ol>
            <li>အမည်(ကျား/မ) — {{ $employee->name }}</li>
            <li>ဝန်ထမ်းအမှတ် — {{ $employee->employee_id ?? '-' }}</li>
            <li>မွေးနေ့(ရက်/လ/နှစ်) — {{ $employee->eng_dob ? $employee->eng_dob->format('d/m/Y') : '-' }}</li>
            <li>လူမျိုး/ဘာသာ — {{ $employee->nationality ?? '-' }} / {{ $employee->religion ?? '-' }}</li>
            <li>အဘအမည် — {{ $employee->father_name ?? '-' }}</li>
            <li>အမိအမည် — {{ $employee->mother_name ?? '-' }}</li>
            <li>နိုင်ငံသားစိစစ်ရေးအမှတ် — {{ $employee->nrc ?? '-' }}</li>
            <li>ဇနီး/ခင်ပွန်းအမည် — {{ $employee->spouse_name ?? '-' }}</li>
            <li>သား/သမီးအမည် —
                @if ($employee->children->isNotEmpty())
                    @foreach ($employee->children as $child)
                        {{ $child->name }}@if (!$loop->last), @endif
                    @endforeach
                @else
                    -
                @endif
            </li>
            <li>လိပ်စာ — {{ $employee->current_address ?? ($employee->permanent_address ?? '-') }}</li>
            <li>ပညာအရည်အချင်း —
                @if ($employee->educations->isNotEmpty())
                    @foreach ($employee->educations as $edu)
                        {{ $edu->highest_certificate }} ({{ $edu->institution_name }})@if (!$loop->last), @endif
                    @endforeach
                @else
                    -
                @endif
            </li>
            <li>လက်ရှိရာထူး/လစာနှုန်း/ဌာန —
                {{ $employee->current_position ?? '-' }} /
                {{ $employee->salary ?? '-' }} /
                {{ $employee->department ?? '-' }}
            </li>
            <li>သွေးအုပ်စု — {{ $employee->blood_type ?? '-' }}</li>
        </ol>
    </div>

    <!-- ၁၄. နိုင်ငံ့ဝန်ထမ်းတာဝန်ထမ်းဆောင်မှုမှတ်တမ်း -->
    <div class="section-title">
        ၁၄။ နိုင်ငံ့ဝန်ထမ်းတာဝန်ထမ်းဆောင်မှုမှတ်တမ်း(စစ်ဘက်/နယ်ဘက်)
    </div>
    <table>
        <thead>
        <tr>
            <th width="8%">စဉ်</th>
            <th width="30%">ရာထူး / ဌာန</th>
            <th colspan="2">တာဝန်ထမ်းဆောင်သည့်ကာလ</th>
            <th width="25%">နေရာ/ဒေသ</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th width="18%">မှ</th>
            <th width="18%">ထိ</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @php
            $pastExperiences = $employee->experiences->where('is_current', false)->sortBy('from_date');
            $currentExperience = $employee->experiences->where('is_current', true)->first();
        @endphp

        @forelse($pastExperiences as $exp)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $exp->position ?? '-' }} / {{ $exp->department ?? '-' }}</td>
                <td>{{ $exp->from_date?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $exp->to_date?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $exp->location ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">-</td>
            </tr>
        @endforelse

        @if($currentExperience)
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td>{{ $pastExperiences->count() + 1 }}</td>
                <td>{{ $currentExperience->position ?? $employee->current_position ?? '-' }} / {{ $currentExperience->department ?? $employee->department ?? '-' }}</td>
                <td>{{ $currentExperience->from_date?->format('d-m-Y') ?? '-' }}</td>
                <td>ယနေ့ထိ</td>
                <td>{{ $currentExperience->location ?? '-' }}</td>
            </tr>
        @endif
        </tbody>
    </table>

    <!-- ၁၅. ပြည်တွင်းသင်တန်းများ -->
    <div class="section-title">၁၅။ ပြည်တွင်းသင်တန်းများ တက်ရောက်မှု</div>
    <table>
        <thead>
        <tr>
            <th width="8%">စဉ်</th>
            <th width="40%">သင်တန်းအမည်</th>
            <th colspan="2">တက်ရောက်သည့်ကာလ</th>
            <th width="25%">နေရာ/ဒေသ</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th>မှ</th>
            <th>ထိ</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($employee->trainings->where('training_type', 'domestic') as $t)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $t->course_name }}</td>
                <td>{{ $t->start_date?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $t->end_date?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $t->location ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">-</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <!-- ၁၆. ပြည်ပသင်တန်းများ -->
    <div class="section-title">၁၆။ ပြည်ပသင်တန်းများ တက်ရောက်မှု</div>
    <table>
        <thead>
        <tr>
            <th width="8%">စဉ်</th>
            <th width="40%">သင်တန်းအမည်</th>
            <th colspan="2">တက်ရောက်သည့်ကာလ</th>
            <th width="25%">နေရာ/နိုင်ငံ</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th>မှ</th>
            <th>ထိ</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($employee->trainings->where('training_type', 'foreign') as $t)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $t->course_name }}</td>
                <td>{{ $t->start_date?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $t->end_date?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $t->location ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">-</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <!-- ၁၇. ပြစ်မှုမှတ်တမ်း -->
    <div class="section-title">၁၇။ ပြစ်မှုမှတ်တမ်း</div>
    <table>
        <thead>
        <tr>
            <th width="40%">ပြစ်ဒဏ်ချမှတ်ခံရသည့်အကြောင်းအရင်း</th>
            <th colspan="2">ပြစ်ဒဏ်ချမှတ်သည့်ကာလ</th>
        </tr>
        <tr>
            <th></th>
            <th>မှ</th>
            <th>ထိ</th>
        </tr>
        </thead>
        <tbody>
        @forelse($employee->criminalRecords as $record)
            <tr>
                <td>{{ $record->description ?? '-' }}</td>
                <td>{{ $record->start_date?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $record->end_date?->format('d-m-Y') ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">-</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <!-- ၁၈. ချီးမြှင့်ခံရသည့် ဘွဲ့ထူး၊ ဂုဏ်ထူးတံဆိပ်များ -->
    <div class="section-title">
        ၁၈။ ချီးမြှင့်ခံရသည့် ဘွဲ့ထူး၊ ဂုဏ်ထူးတံဆိပ်များ
    </div>
    <table>
        <thead>
        <tr>
            <th width="8%">စဉ်</th>
            <th width="60%">ဘွဲ့ထူး ၊ ဂုဏ်ထူး၊ တံဆိပ်အမည်</th>
            <th width="32%">ချီးမြှင့်သည့်နေ့စွဲ</th>
        </tr>
        </thead>
        <tbody>
        @forelse($employee->certificates as $cert)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $cert->certificate_name ?? '-' }}</td>
                <td>{{ $cert->issue_date?->format('d-m-Y') ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">-</td>
            </tr>
            <tr><td colspan="3" style="height: 30px;"></td></tr>
            <tr><td colspan="3" style="height: 30px;"></td></tr>
            <tr><td colspan="3" style="height: 30px;"></td></tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: center;">
        အထက်ပါဖြည့်စွက်ချက်များ မှန်ကန်ကြောင်း လက်မှတ်ရေးထိုးပါသည်။
    </div>

    <div class="signature-block">
        <div class="sig-row signature-space">
            <span class="sig-label">လက်မှတ်</span>
            <span class="sig-separator"></span>
        </div>
        <div class="sig-row">
            <span class="sig-label">အမည်</span>
            <span class="sig-separator">{{ $employee->name }}</span>
        </div>
        <div class="sig-row">
            <span class="sig-label">ရာထူး</span>
            <span class="sig-separator">{{ $employee->current_position ?? '-' }}</span>
        </div>
        <div class="sig-row">
            <span class="sig-label">ဌာန</span>
            <span class="sig-separator">{{ $employee->department ?? '-' }}</span>
        </div>
        <div class="sig-row">
            <span class="sig-label">ဖုန်းနံပါတ်</span>
            <span class="sig-separator">{{ $employee->phone ?? '-' }}</span>
        </div>
    </div>

    <div class="date-line">
        ရက်စွဲ၊ {{ now()->format('Y') }} ခုနှစ်၊ {{ now()->translatedFormat('F') }} လ၊ {{ now()->format('d') }} ရက်
    </div>
</div>
</body>
</html>