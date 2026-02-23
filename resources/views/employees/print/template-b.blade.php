<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <title>ဝန်ထမ်းမှတ်တမ်းအကျဉ်း - ပုံစံ အဝ (၂၂၂)</title>
    <style>
        @font-face {
            font-family: 'Pyidaungsu';
            src: local('Pyidaungsu');
        }
        @page {
            size: A4;
            margin: 15mm 15mm 15mm 20mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Pyidaungsu', 'Myanmar Text', sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .page {
            width: 100%;
            padding: 10px 0;
        }
        .page-break {
            page-break-after: always;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }
        .no-border td {
            border: none;
            padding: 3px 6px;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 6px;
        }
        .form-id {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 4px;
        }
        .photo-box {
            width: 110px;
            height: 145px;
            border: 2px solid #000;
            display: block;
            margin: 0 auto;
        }
        .mb4 { margin-bottom: 4px; }
        .mt6 { margin-top: 6px; }
        .right { text-align: right; }
        .tall-row { height: 70px; }
    </style>
</head>
<body>

<!-- ============================================================ -->
<!-- PAGE 1  (IMG_0370)                                           -->
<!-- ============================================================ -->
<div class="page page-break">

    <div class="title">ဝန်ထမ်းမှတ်တမ်းအကျဉ်း</div>
    <div class="form-id">ပုံစံ &nbsp;– အဝ (၂၂၂)</div>

    <!-- ROW 1 : Name / DOB / Age / ID+Rank  +  Photo -->
    <table>
        <tr>
            <!-- Name block -->
            <td style="width:34%;">
                အမည် – {{ $employee->name }}<br>
                (အဘ) – {{ $employee->father_name }}<br>
                (မိ) – {{ $employee->mother_name }}<br>
                လူမျိုး/ဘာသာ – {{ $employee->nationality }}/{{ $employee->religion }}
            </td>
            <!-- DOB block -->
            <td style="width:26%;">
                <span class="bold">မွေးသက္ကရာဇ်</span><br>
                အင်္ဂလိပ် – {{ $employee->dob_eng }}<br>
                မြန်မာ– {{ $employee->dob_mm }}
            </td>
            <!-- Age -->
            <td style="width:14%;" class="center">သက်ပြည့်</td>
            <!-- ID/Rank + photo (rowspan 2) -->
            <td style="width:26%;" class="center" rowspan="2">
                ကိုယ်ပိုင်အမှတ်/<br>ရာထူး
                <div style="margin-top:6px;">
                    <div class="photo-box">
                        @if(!empty($employee->image))
                            <img src="{{ $employee->image }}" style="width:100%;height:100%;object-fit:cover;">
                        @endif
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <!-- Birthplace / NRC block -->
            <td>
                မွေးရပ် –<br>
                မှတ်ပုံတင်အမှတ် –<br>
                နိုင်ငံသားစိစစ်ရေးကတ်အမှတ်–
            </td>
            <!-- Empty middle cells under DOB / Age -->
            <td colspan="2" style="height:70px;"></td>
        </tr>
        <!-- Permanent / Current address headers -->
        <tr>
            <td class="bold">အမြဲတမ်းနေရပ်</td>
            <td colspan="3" class="bold">လက်ရှိနေရပ်</td>
        </tr>
        <tr style="height:38px;">
            <td>{{ $employee->permanent_address }}</td>
            <td colspan="3">{{ $employee->current_address }}</td>
        </tr>
    </table>

    <!-- Education -->
    <table>
        <tr class="center">
            <td style="width:34%;">ပညာအဆင့်</td>
            <td style="width:30%;">ကျောင်း/တက္ကသိုလ်</td>
            <td style="width:16%;">ရရှိသည့်နှစ်</td>
            <td style="width:20%;">ထူးခြားချက်</td>
        </tr>
        <tr style="height:55px;">
            <td></td><td></td><td></td><td></td>
        </tr>
    </table>

    {{--
        5-column table — widths set via colgroup:
        C1=33%  C2=18%  C3=12%  C4=25%  C5=12%

        Exact Word-HTML rowspan/colspan structure:
        R1: C1          | C2+C3(cs2)          | C4+C5(cs2)
        R2: C1(rs2)     | C2+C3(cs2,rs2)      | C4          | C5
        R3:                                    | C4(rs3)     | C5(rs3)
        R4: C1+C2+C3(cs3)
        R5: C1          | C2          | C3
        R6: C1(rs2)     | C2(rs2)     | C3(rs2)  | C4+C5(cs2)
        R7:                                        | C4          | C5
        R8: C1          | C2+C3(cs2)              | C4(rs2)     | C5(rs2)
        R9: C1          | C2+C3(cs2)
    --}}
    <table>
        <colgroup>
            <col style="width:33%">
            <col style="width:18%">
            <col style="width:12%">
            <col style="width:25%">
            <col style="width:12%">
        </colgroup>

        {{-- R1 --}}
        <tr>
            <td>လက်ရှိဝန်ထမ်းအဖွဲ့ဝင်သည့်နေ့</td>
            <td colspan="2">ခန့်အပ်သည့်နေ့</td>
            <td colspan="2">ချီးမြှင့်ခဲ့သော ဆုဘွဲ့တံဆိပ်</td>
        </tr>

        {{-- R2: C1 rowspan=2, C2+C3 rowspan=2, C4=အမျိုးအမည်, C5=ခုနှစ် --}}
        <tr>
            <td rowspan="2">
                အမြဲတမ်းခန့်ထားခြင်း<br>
                (ငယ်)<br>
                ၎င်း(ကြီး)<br>
                ၎င်း(ရွေးချယ်)<br>
                ၎င်း(အထက်)
            </td>
            <td colspan="2" rowspan="2"></td>
            <td>အမျိုးအမည်</td>
            <td>ခုနှစ်</td>
        </tr>

        {{-- R3: C1+C2+C3 still rowspanned; C4 rowspan=3, C5 rowspan=3 (empty award data) --}}
        <tr>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
        </tr>

        {{-- R4: Training header colspan=3; C4+C5 still rowspanned --}}
        <tr>
            <td colspan="3">အခြားတက်ရောက်ဖူးသောသင်တန်းများ</td>
        </tr>

        {{-- R5: Training sub-headers; C4+C5 still rowspanned --}}
        <tr>
            <td>သင်တန်းအမည်</td>
            <td>မှ</td>
            <td>ထိ</td>
        </tr>

        {{-- R6: C1 rowspan=2, C2 rowspan=2, C3 rowspan=2 (training data); C4+C5(cs2) = spouse info --}}
        <tr>
            <td rowspan="2" class="tall-row"></td>
            <td rowspan="2"></td>
            <td rowspan="2"></td>
            <td colspan="2">
                ခင်ပွန်း / ဇနီးအမည် –<br>
                အလုပ်အကိုင် –<br>
                ဒေသ –<br>
                <br><br><br>
            </td>
        </tr>

        {{-- R7: C1+C2+C3 still rowspanned; C4=သား/သမီး, C5=မွေးနေ့ --}}
        <tr>
            <td>သား/သမီးများ</td>
            <td>မွေးနေ့</td>
        </tr>

        {{-- R8: Language header | Hobby header | C4 rowspan=2, C5 rowspan=2 (children data) --}}
        <tr>
            <td>ကျွမ်းကျင်သောဘာသာစကား</td>
            <td colspan="2" class="center">ဝါသနာထုံမှု</td>
            <td rowspan="2"></td>
            <td rowspan="2"></td>
        </tr>

        {{-- R9: Language & Hobby looped from DB with Burmese ordinal numbers --}}
        @php
            $myanmarNums = ['၁','၂','၃','၄','၅','၆','၇','၈','၉','၁၀'];
            $languages = $employee->languages ?? [];
            $hobbies   = $employee->hobbies   ?? [];
            $maxRows   = max(count($languages), count($hobbies), 1);
        @endphp
        <tr>
            <td>
                @foreach($languages as $i => $lang)
                    ({{ $myanmarNums[$i] }}) {{ $lang }}<br>
                @endforeach
            </td>
            <td colspan="2">
                @foreach($hobbies as $i => $hobby)
                    ({{ $myanmarNums[$i] }}) {{ $hobby }}<br>
                @endforeach
            </td>
        </tr>
    </table>

    <div class="form-id mt6">ပုံစံ &nbsp;– အဝ (၂၂၂) အဆက် (၁)</div>

    <!-- 3 nearest relatives box -->
    <table>
        <tr>
            <td class="center" style="padding: 6px;">အနီးစပ်ဆုံးဆွေမျိုး (၃) ဦး</td>
        </tr>
    </table>

</div><!-- end page 1 -->


<!-- ============================================================ -->
<!-- PAGE 2  (IMG_0369)                                           -->
<!-- ============================================================ -->
<div class="page page-break">

    <!-- 3 nearest relatives table (continued from page 1) -->
    <table>
        <tr class="center">
            <td style="width:24%;">အမည်</td>
            <td style="width:15%;">တော်စပ်ပုံ</td>
            <td style="width:26%;">အလုပ်အကိုင်</td>
            <td style="width:35%;">နေရပ်</td>
        </tr>
        <tr style="height:140px;">
            <td></td><td></td><td></td><td></td>
        </tr>
    </table>

    <!-- Previous work experience -->
    <table>
        <tr>
            <td colspan="5" class="center">ယခင်ဆောင်ရွက်ခဲ့ဖူးသောလုပ်ငန်း</td>
        </tr>
        <tr class="center">
            <td style="width:30%;">
                လုပ်ငန်း/ရာထူး<br>
                (လစာနှုန်း)
            </td>
            <td style="width:22%;">ဒေသ</td>
            <td colspan="2" style="width:30%;">မှ (နေ့ရက်) ထိ</td>
            <td style="width:18%;">မှတ်ချက်</td>
        </tr>
        <tr class="center" style="font-size:10px;">
            <td></td>
            <td></td>
            <td style="width:15%;">မှ</td>
            <td style="width:15%;">ထိ</td>
            <td></td>
        </tr>
        <tr style="height:80px;">
            <td></td><td></td><td></td><td></td><td></td>
        </tr>
    </table>

    <!-- Appointment/transfer/punishment history -->
    <table>
        <tr>
            <td colspan="5" class="center">ရာထူးခန့်ခြင်း/တိုး/လျော့/ပြောင်းရွှေ့ခြင်း/အပြစ်ပေးခြင်း/ခွင့်ပေးခြင်း/ပူးတွဲဆောင်ရွက်ခြင်းစသည့်</td>
        </tr>
        <tr class="center">
            <td style="width:5%;">စဉ်</td>
            <td style="width:33%;">လုပ်ငန်း/ရာထူးအမည်</td>
            <td style="width:20%;">ဒေသ</td>
            <td style="width:25%;">မှ (နေ့ရက်) ထိ</td>
            <td style="width:17%;">မှတ်ချက်</td>
        </tr>
        <tr style="height:260px;">
            <td></td><td></td><td></td><td></td><td></td>
        </tr>
    </table>

    <!-- Right-aligned extra column header matching image -->
    <div style="text-align:right; margin-top:2px; font-size:10px;">အသက်အမဲခံ ထားရှိလျှင်</div>

    <div class="form-id mt6">ပုံစံ &nbsp;– အဝ (၂၂၂) အဆက် (၂)</div>

    <div style="margin-top:16px; font-size:11px; line-height:1.8;">
        ပူးတွဲပါ ဝန်ထမ်းမှတ်တမ်းအကျဉ်းပုံစံ အဝ(၂၂၂)တွင် ဖြည့်စွက်ထားသော အချက်အလက်များ မှန်ကန်ကြောင်း ကိုယ်တိုင်ကတိပြုလက်မှတ်ရေးထိုးပါသည်။
    </div>

</div><!-- end page 2 -->


<!-- ============================================================ -->
<!-- PAGE 3  (IMG_0368) – Signature page (3 endorsements)         -->
<!-- ============================================================ -->
<div class="page">

    @for ($i = 0; $i < 3; $i++)
    <div style="margin-bottom: 38px;">

        @if ($i > 0)
        <div style="font-weight:bold; margin-bottom:10px; margin-left:40px;">ထပ်ဆင့်ထောက်ခံပါသည်။</div>
        @endif

        <!-- Date line -->
        <table class="no-border" style="margin-bottom:4px;">
            <tr>
                <td style="width:55%;">ရက်စွဲ၊ ၂၀၂&ensp;&ensp; ခုနှစ်၊ ဖေဖော်ဝါရီလ &ensp;&ensp;&ensp; ရက်</td>
                <td>လက်မှတ် –</td>
            </tr>
        </table>

        <!-- Office stamp + signature details side by side -->
        <table class="no-border">
            <tr>
                <td style="width:45%; text-align:center; vertical-align:middle; padding-top:20px; height:80px;">
                    ရုံးတံဆိပ်
                </td>
                <td style="vertical-align:top; padding-top:4px;">
                    အမည် –<br>
                    ကိုယ်ပိုင်အမှတ် –<br>
                    ရာထူး –<br>
                    ဌာန –
                </td>
            </tr>
        </table>

    </div>
    @endfor

</div><!-- end page 3 -->

</body>
</html>