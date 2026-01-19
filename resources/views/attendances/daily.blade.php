@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Daily Attendance: {{ \Carbon\Carbon::parse($date)->format('d-M-Y') }}</h5>
        <button type="button" id="selectAllPresent" class="btn btn-sm btn-outline-success">Select All Present</button>
    </div>
    <div class="card-body">
        <form action="{{ route('attendances.store') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Employee Information</th>
                        <th>Present</th>
                        <th>On-Duty</th>
                        <th>Status / Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                        @php 
                            $onLeave = $emp->leaves->where('status', 'ongoing')->first();
                            $att = $emp->attendances->first();
                        @endphp
                        <tr class="{{ $onLeave ? 'table-secondary' : '' }}">
                            <td>
                                <strong>{{ $emp->name }}</strong><br>
                                <small class="text-muted">{{ $emp->employee_id }} | {{ $emp->department }}</small>
                            </td>
                            <td>
                                <input type="radio" name="attendance[{{ $emp->id }}]" value="present" 
                                    class="present-radio" {{ $onLeave ? 'disabled' : '' }}
                                    {{ ($att && $att->status == 'present') ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="radio" name="attendance[{{ $emp->id }}]" value="on_duty" 
                                    {{ $onLeave ? 'disabled' : '' }}
                                    {{ ($att && $att->status == 'on_duty') ? 'checked' : '' }}>
                            </td>
                            <td>
                                @if($onLeave)
                                    <span class="badge bg-info text-dark">On Leave: {{ $onLeave->leaveType->name }}</span>
                                    <a href="{{ route('leaves.edit', $onLeave->id) }}" class="btn btn-xs btn-warning ms-2">
                                        End Leave Early
                                    </a>
                                @else
                                    <span class="text-success small">Available</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary mt-3">Save Daily Attendance</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('selectAllPresent').addEventListener('click', function() {
        document.querySelectorAll('.present-radio:not(:disabled)').forEach(radio => {
            radio.checked = true;
        });
    });
</script>
@endsection