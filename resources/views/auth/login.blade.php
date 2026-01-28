<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mon State EMS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Background for the whole page */
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Container for the branding and card */
        .login-wrapper {
            margin-top: 5rem;
        }

        /* Styling the Login Card */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background-color: #f39a35; /* Mon State Orange Theme */
            color: white;
            text-align: center;
            font-weight: 700;
            padding: 1.5rem;
            font-size: 1.25rem;
            border: none;
        }

        .branding img {
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }

        /* Primary Button Styling (Green for Mon State Theme) */
        .btn-primary {
            background-color: #276e17;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1b4d10;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Input Field Styling */
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: #f39a35;
            box-shadow: 0 0 0 0.2rem rgba(243, 154, 53, 0.15);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        /* Centering helper */
        .vh-100 {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>

<div class="container vh-100">
    <div class="row justify-content-center w-100">
        <div class="col-md-5">
            
            <div class="text-center mb-4 branding">
                 <img src="{{asset('assets/img/mon-state-logo.png')}}" alt="Logo" style="height: 140px; width: auto;">
                 <h4 class="mt-3" style="color: #1a237e; font-weight: 700;">Employee Management System</h4>
            </div>

            <div class="card">
                <div class="card-header">အကောင့်ဝင်ရန်</div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address / Username</label>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email" autofocus 
                                placeholder="အီးမေးလ် ထည့်သွင်းပါ။">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">စကားဝှက် (Password)</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                required >

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                               အကောင့်ဝင်မည်။ 
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
           
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>