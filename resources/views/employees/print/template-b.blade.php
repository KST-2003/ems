<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $employee->name ?? 'Employee' }} - ပုံစံ အဝ (၂၂၂) အဆက်</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 15mm;
        }
        body {
            font-family: 'Pyidaungsu', 'Zawgyi-One', sans-serif;
            font-size: 13px;
            line-height: 1.5;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 15px;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
            text-align: left;
            word-wrap: break-word;
            font-size: 13px;
        }
        th {
            font-weight: normal;
            background-color: #f5f5f5;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .img-container {
            width: 100%;
            height: 140px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border: none;
        }
        .img-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .header-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
        }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header-title">ပုံစံ အဝ (၂၂၂) အဆက်</div>

    <table>
        <colgroup>
            <col style="width: 29%" />
            <col style="width: 11%" />
            <col style="width: 11%" />
            <col style="width: 24%" />
            <col style="width: 23%" />
        </colgroup>
        <thead>
            <tr>
                <th rowspan="2">
                    <p>အမည် - <b>{{ $employee->name }}</b></p>
                    <p>(အဘ) – {{ $employee->father_name ?? '-' }}</p>
                    <p>(မိ) – {{ $employee->mother_name ?? '-' }}</p>
                    <p>လူမျိုး / ဘာသာ - {{ $employee->nationality ?? '-' }} / {{ $employee->religion ?? '-' }}</p>
                </th>
                <th colspan="2" class="center">မွေးသက္ကရာဇ်</th>
                <th class="center bold">သက်ပြည့်</th>
                <th class="center bold">ကိုယ်ပိုင်အမှတ် / ရာထူး</th>
            </tr>
            <tr>
                <th colspan="2">
                    <p>အင်္ဂလိပ် - {{ $employee->eng_dob ? \Carbon\Carbon::parse($employee->eng_dob)->format('d-m-Y') : '-' }}</p>
                    <p>မြန်မာ - {{ $employee->mm_dob ?? '-' }}</p>
                </th>
                <th class="center">
                    {{ $employee->eng_dob ? \Carbon\Carbon::parse($employee->eng_dob)->age . ' နှစ်' : '-' }}
                </th>
                <th rowspan="4" style="padding: 0;">
                    <div class="img-container">
                        @if(!empty($employee->profile_image_url))
                            <img src="{{ $employee->profile_image_url }}" alt="Photo">
                        @else
                            <span style="color:#ccc; font-size: 12px;">ဓာတ်ပုံ မရှိပါ</span>
                        @endif
                    </div>
                </th>
            </tr>
            <tr>
                <th>
                    {{ $employee->employee_id ?? '-' }} <br>
                    {{ $employee->current_position ?? '-' }}
                </th>
                <th colspan="3">
                    <p>မှတ်ပုံတင် အမှတ် - {{ $employee->nrc ?? '-' }}</p>
                    <p>နိုင်ငံသားစိစစ်ရေးကဒ်အမှတ် - {{ $employee->nrc ?? '-' }}</p>
                </th>
            </tr>
            <tr>
                <th>အမြဲတမ်းနေရပ်</th>
                <th colspan="3">လက်ရှိနေရပ်</th>
            </tr>
            <tr>
                <td>{{ $employee->permenant_address ?? '-' }}</td>
                <td colspan="3">{{ $employee->current_address ?? '-' }}</td>
            </tr>
        </thead>
        <tbody>
            <tr class="center bold">
                <td>ပညာအဆင့်</td>
                <td colspan="2">ကျောင်း / တက္ကသိုလ်</td>
                <td>ရရှိသည့်နှစ်</td>
                <td>ထူးခြားချက်</td>
            </tr>
            @if($employee->educations && $employee->educations->isNotEmpty())
                @foreach($employee->educations as $edu)
                    <tr>
                        <td>{{ $edu->highest_certificate ?? '-' }}</td>
                        <td colspan="2">{{ $edu->institution_name ?? '-' }}</td>
                        <td class="center">{{ $edu->date ? \Carbon\Carbon::parse($edu->date)->format('Y') : '-' }}</td>
                        <td>{{ $edu->remark ?? '-' }}</td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="5" style="height: 40px;"></td></tr>
            @endif

            <!-- လက်ရှိဝန်ထမ်းအဖွဲ့ဝင်သည့်နေ့ -->
            <tr>
                <td class="bold">လက်ရှိဝန်ထမ်းအဖွဲ့ဝင်သည့်နေ့</td>
                <td colspan="2" class="center">
                    {{ optional($employee->serviceRecord)->recruited_date ? \Carbon\Carbon::parse($employee->serviceRecord->recruited_date)->format('d-m-Y') : '-' }}
                </td>
                <td colspan="2" class="center bold">
                    အဆင့် — 
                    @switch(optional($employee->serviceRecord)->grade)
                        @case('junior') ငယ် @break
                        @case('senior') ၎င်း (ကြီး) @break
                        @case('selection') ၎င်း (ရွေးချယ်) @break
                        @case('higher') ၎င်း (အထက်) @break
                        @default -
                    @endswitch
                </td>
            </tr>

            <!-- ချီးမြှင့်ခံရသည့် ဘွဲ့ထူး၊ ဂုဏ်ထူးတံဆိပ်များ -->
            <tr>
                <td colspan="5" class="bold center">ချီးမြှင့်ခံရသည့် ဘွဲ့ထူး၊ ဂုဏ်ထူးတံဆိပ်များ</td>
            </tr>
            <tr>
                <td colspan="3">အမျိုးအမည်</td>
                <td colspan="2">ချီးမြှင့်သည့်နေ့စွဲ</td>
            </tr>
            @if($employee->certificates && $employee->certificates->isNotEmpty())
                @foreach($employee->certificates as $cert)
                    <tr>
                        <td colspan="3">{{ $cert->certificate_name ?? '-' }}</td>
                        <td colspan="2" class="center">
                            {{ $cert->issue_date ? \Carbon\Carbon::parse($cert->issue_date)->format('d-m-Y') : '-' }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="5" style="height: 60px;"></td></tr>
            @endif

            <!-- အခြားတက်ရောက်ဖူးသောသင်တန်းများ -->
            <tr>
                <td colspan="6" class="bold center" style="background-color: #f0f0f0;">
                    အခြားတက်ရောက်ဖူးသောသင်တန်းများ
                </td>
            </tr>
            <tr class="center bold">
                <td>သင်တန်းအမည်</td>
                <td colspan="2">မှ</td>
                <td colspan="2">ထိ</td>
                <td>နေရာ</td>
            </tr>
            @if($employee->trainings && $employee->trainings->isNotEmpty())
                @foreach($employee->trainings as $t)
                    <tr>
                        <td>{{ $t->course_name ?? '-' }}</td>
                        <td colspan="2">{{ $t->start_date ? \Carbon\Carbon::parse($t->start_date)->format('d-m-Y') : '-' }}</td>
                        <td colspan="2">{{ $t->end_date ? \Carbon\Carbon::parse($t->end_date)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $t->location ?? '-' }}</td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="6" style="height: 60px;"></td></tr>
            @endif

            <!-- ခင်ပွန်း/ဇနီး နှင့် သား/သမီးများ -->
            <tr>
                <td colspan="3">
                    <p><b>ခင်ပွန်း / ဇနီးအမည်</b> - {{ $employee->spouse_name ?? '-' }}</p>
                    <p><b>အလုပ်အကိုင်</b> - {{ $employee->spouse_job ?? '-' }}</p>
                    <p><b>ဒေသ</b> - {{ $employee->spouse_job_place ?? '-' }}</p>
                </td>
                <td colspan="3">
                    <p class="bold center">သား / သမီးများ</p>
                    <p><b>အမည်</b></p>
                    @foreach($employee->children as $child)
                        {{ $child->name ?? '-' }}<br>
                    @endforeach
                    <p><b>မွေးနေ့</b></p>
                    @foreach($employee->children as $child)
                        {{ $child->date_of_birth ? \Carbon\Carbon::parse($child->date_of_birth)->format('d-m-Y') : '-' }}<br>
                    @endforeach
                </td>
            </tr>

            <!-- ကျွမ်းကျင်သောစကား / ဝါသနာ -->
            <tr>
                <td colspan="3">
                    <b>ကျွမ်းကျင်သောစကား</b><br>
                    {{ $employee->lang_proficiency ?? '-' }}
                </td>
                <td colspan="3">
                    <b>ဝါသနာထုံမှု</b><br>
                    {{ $employee->hobby ?? '-' }}
                </td>
            </tr>

            <!-- ယခင်ဆောင်ရွက်ခဲ့ဖူးသောလုပ်ငန်း (Past Jobs) -->
            <tr>
                <td colspan="6" class="bold center" style="background-color: #f0f0f0;">
                    ယခင်ဆောင်ရွက်ခဲ့ဖူးသောလုပ်ငန်း
                </td>
            </tr>
            <tr class="center bold">
                <td>လုပ်ငန်း / ရာထူး (လစာနှုန်း)</td>
                <td>ဒေသ</td>
                <td colspan="2">မှ</td>
                <td colspan="2">ထိ</td>
            </tr>
            @if($employee->pastExperiences && $employee->pastExperiences->isNotEmpty())
                @foreach($employee->pastExperiences as $job)
                    <tr>
                        <td>{{ $job->position ?? '-' }} ({{ $job->salary ?? '-' }})</td>
                        <td>{{ $job->location ?? '-' }}</td>
                        <td colspan="2">{{ $job->start_date ? \Carbon\Carbon::parse($job->start_date)->format('d-m-Y') : '-' }}</td>
                        <td colspan="2">{{ $job->end_date ? \Carbon\Carbon::parse($job->end_date)->format('d-m-Y') : '-' }}</td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="6" style="height: 60px;"></td></tr>
            @endif

            <!-- ရာထူးခန့်အပ် / ပြောင်းရွှေ့ / အပြစ်ပေး စသည် -->
            <tr>
                <td colspan="6" class="bold center" style="background-color: #f0f0f0;">
                    ရာထူးခန့်အပ်ခြင်း / တိုးမြှင့်ခြင်း / လျော့ချခြင်း / ပြောင်းရွှေ့ခြင်း / အပြစ်ပေးခြင်း စသည်
                </td>
            </tr>
            <tr class="center bold">
                <td>စဉ်</td>
                <td>အမိန့်အမှတ် / အမျိုးအစား</td>
                <td>ရာထူး</td>
                <td>ဌာန</td>
                <td>ဒေသ</td>
                <td>ရက်စွဲ</td>
            </tr>
            @if($employee->personnelActions && $employee->personnelActions->isNotEmpty())
                @foreach($employee->personnelActions as $index => $action)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $action->type ?? '-' }}</td>
                        <td>{{ $action->position ?? '-' }}</td>
                        <td>{{ $action->department ?? '-' }}</td>
                        <td>{{ $action->location ?? '-' }}</td>
                        <td class="center">
                            {{ $action->start_date ? \Carbon\Carbon::parse($action->start_date)->format('d-m-Y') : '-' }}
                            @if($action->end_date)
                                <br>~ {{ \Carbon\Carbon::parse($action->end_date)->format('d-m-Y') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="6" style="height: 80px;"></td></tr>
            @endif
        </tbody>
    </table>


    <div style="max-width: 800px; margin: 0 auto;">

        <div style="margin-bottom: 60px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                <div style="flex: 1;">
                    ရက်စွဲ၊ {{ toMyanmarDate(now()) }}
                </div>
                <div style="width: 300px; display: flex; justify-content: space-between;">
                    <span>လက်မှတ်</span>
                    <span>-</span>
                </div>
            </div>

            <div style="display: flex; position: relative;">
                <div style="flex: 1; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    ရုံးတံဆိပ်
                </div>

                <div style="width: 300px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>အမည်</span>
                        <span>-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>ကိုယ်ပိုင်အမှတ်</span>
                        <span>-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>ရာထူး</span>
                        <span>-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>ဌာန</span>
                        <span>-</span>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 60px;">
            <div style="text-align: left; padding-left: 15%; margin-bottom: 20px; font-weight: bold;">
                ထပ်ဆင့်ထောက်ခံပါသည်။
            </div>

            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                <div style="flex: 1;">
                 ရက်စွဲ၊ {{ toMyanmarDate(now()) }}
                </div>
                <div style="width: 300px; display: flex; justify-content: space-between;">
                    <span>လက်မှတ်</span>
                    <span>-</span>
                </div>
            </div>

            <div style="display: flex; position: relative;">
                <div style="flex: 1; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    ရုံးတံဆိပ်
                </div>

                <div style="width: 300px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>အမည်</span>
                        <span>-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>ကိုယ်ပိုင်အမှတ်</span>
                        <span>-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>ရာထူး</span>
                        <span>-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>ဌာန</span>
                        <span>-</span>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 60px;">
            <div style="text-align: left; padding-left: 15%; margin-bottom: 20px; font-weight: bold;">
                ထပ်ဆင့်ထောက်ခံပါသည်။
            </div>

            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                <div style="flex: 1;">
                  ရက်စွဲ၊ {{ toMyanmarDate(now()) }}
                </div>
                <div style="width: 300px; display: flex; justify-content: space-between;">
                    <span>လက်မှတ်</span>
                    <span>-</span>
                </div>
            </div>

            <div style="display: flex; position: relative;">
                <div style="flex: 1; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    ရုံးတံဆိပ်
                </div>

                <div style="width: 300px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>အမည်</span>
                        <span>-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>ကိုယ်ပိုင်အမှတ်</span>
                        <span>-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>ရာထူး</span>
                        <span>-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>ဌာန</span>
                        <span>-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>