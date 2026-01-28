@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Calendar & Holidays</h1>
    <p class="text-muted">Manage public holidays and special working/closing days.</p>
</div>

<section class="section">
    <div class="row">
        {{-- Add New Entry --}}
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Add Exception</h5>
                    <form action="{{ route('calendar.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Event Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Thingyan Festival" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="holiday">Public Holiday (Closed)</option>
                                <option value="close_exception">Special Closure (Closed)</option>
                                <option value="open_exception">Working Weekend (Open)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save to Calendar</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Calendar List --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">2026 Calendar Overview</h5>
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calendarEntries as $entry)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($entry->date)->format('d M Y') }}</td>
                                <td><strong>{{ $entry->name }}</strong></td>
                                <td>
                                    <span class="badge @if($entry->type == 'open_exception') bg-success @else bg-danger @endif">
                                        {{ ucfirst(str_replace('_', ' ', $entry->type)) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('calendar.destroy', $entry->id) }}" method="POST" onsubmit="return confirm('Remove this date?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection