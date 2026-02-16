@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center" style="margin-top: 80px;">
    <div class="col-md-6 col-lg-5"> <!-- Adjusted width to match legacy -->
        <div class="glass-card p-4 mx-3" style="width: 100%; max-width: 400px; margin: 0 auto;">
            <h2 class="text-center mb-4">Login</h2>
            
            @if($errors->any())
                <div class="alert alert-danger text-center" role="alert">
                    <ul class="mb-0 list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label text-white-50">Email Address</label>
                    <div class="custom-input-wrapper position-relative">
                        <i class="bi bi-envelope custom-input-icon text-white-50 position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                        <input type="email" class="form-control bg-transparent text-white border-secondary ps-5" name="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-white-50">Password</label>
                    <div class="custom-input-wrapper position-relative">
                        <i class="bi bi-lock custom-input-icon text-white-50 position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                        <input type="password" class="form-control bg-transparent text-white border-secondary ps-5" name="password" id="u_pass" placeholder="••••••••" required style="padding-right: 45px;">
                        <i class="bi bi-eye password-toggle text-white-50 position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePassword('u_pass', this)" style="cursor: pointer;"></i>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
                <p class="text-center mt-4 mb-0 text-muted">Don't have an account? <a href="{{ route('register') }}" class="fw-bold">Register now</a></p>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endpush
@endsection
