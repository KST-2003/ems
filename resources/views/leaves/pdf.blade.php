<!DOCTYPE html>
<html>
<head>
    <title>Employee Leaves - {{ $employee->name }}</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'pyidaungsu', sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        h1 {
            text-align: center;
            font-family: 'pyidaungsu', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-family: 'pyidaungsu', sans-serif;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>{{ __('messages.employee_leaves') }} - {{ $employee->name }}</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('messages.leave_type') }}</th>
                <th>{{ __('messages.start_date') }}</th>
                <th>{{ __('messages.end_date') }}</th>
                <th>{{ __('messages.duration') }}</th>
                <th>{{ __('messages.remark') }}</th>
                <th>{{ __('messages.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $index => $leave)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $leave->leaveType->name ?? '-' }}</td>
                    <td>{{ $leave->start_date->format('d-m-Y') }}</td>
                    <td>{{ $leave->end_date->format('d-m-Y') }}</td>
                    <td>{{ $leave->start_date->diffInDays($leave->end_date) + 1 }}</td>
                    <td>{{ $leave->reason ?? '-' }}</td>
                    <td>{{ $leave->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>