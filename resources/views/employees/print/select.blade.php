@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('messages.print_profile') }} - {{ $employee->name }}</h4>
                </div>
                <div class="card-body text-center py-5">
                    <p class="lead mb-5">{{ __('messages.choose_template') }}</p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <a href="{{ route('employees.print.template-a', $employee) }}" target="_blank"
                               class="btn btn-outline-primary btn-lg w-100 py-4">
                                <i class="bi bi-file-text fs-3"></i><br>
                                Template A<br><small>{{ __('messages.simple_personal_record') }}</small>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('employees.print.template-b', $employee) }}" target="_blank"
                               class="btn btn-outline-success btn-lg w-100 py-4">
                                <i class="bi bi-journal-text fs-3"></i><br>
                                Template B<br><small>{{ __('messages.full_service_book') }}</small>
                            </a>
                        </div>
                    </div>

                    <div class="mt-5">
                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-secondary">
                            {{ __('messages.back_to_profile') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection