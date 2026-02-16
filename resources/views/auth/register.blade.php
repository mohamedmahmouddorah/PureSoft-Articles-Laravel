@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center" style="margin-top: 20px;">
    <div class="col-md-6 col-lg-5">
        <div class="glass-card p-4">
            <h2 class="text-center mb-4">Create New Account</h2>
            
            @if($errors->any())
                <div class="alert alert-danger text-center" role="alert">
                    <ul class="mb-0 list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" id="regForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-white-50">Full Name</label>
                    <div class="custom-input-wrapper position-relative">
                        <i class="bi bi-person custom-input-icon text-white-50 position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                        <input type="text" class="form-control bg-transparent text-white border-secondary ps-5" name="username" value="{{ old('username') }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-white-50">Email Address</label>
                    <div class="custom-input-wrapper position-relative">
                        <i class="bi bi-envelope custom-input-icon text-white-50 position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                        <input type="email" class="form-control bg-transparent text-white border-secondary ps-5" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-white-50">Age</label> <!-- Added Age as per schema -->
                    <div class="custom-input-wrapper position-relative">
                        <i class="bi bi-calendar custom-input-icon text-white-50 position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                        <input type="number" class="form-control bg-transparent text-white border-secondary ps-5" name="age" value="{{ old('age') }}" required min="10">
                    </div>
                </div>

                <div class="mb-3">
                   <label class="form-label text-white-50">Phone</label> <!-- Added Phone as per schema -->
                   <div class="custom-input-wrapper position-relative">
                       <i class="bi bi-telephone custom-input-icon text-white-50 position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                       <input type="text" class="form-control bg-transparent text-white border-secondary ps-5" name="phone" value="{{ old('phone') }}" required>
                   </div>
               </div>

                <div class="mb-3">
                    <label class="form-label text-white-50">Password</label>
                    <div class="custom-input-wrapper position-relative">
                        <i class="bi bi-lock custom-input-icon text-white-50 position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                        <input type="password" class="form-control bg-transparent text-white border-secondary ps-5" name="password" id="u_pass" placeholder="At least 6 characters" required style="padding-right: 45px;">
                        <i class="bi bi-eye password-toggle text-white-50 position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePassword('u_pass', this)" style="cursor: pointer;"></i>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <div class="custom-input-wrapper">
                        <i class="bi bi-lock-fill custom-input-icon"></i>
                        <input type="password" class="form-control" name="password_confirmation" id="u_conf" required style="padding-right: 45px;">
                        <i class="bi bi-eye password-toggle" onclick="togglePassword('u_conf', this)"></i>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">Create Account</button>
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-white-50 text-decoration-none">Already have an account? <span class="text-white fw-bold">Login</span></a>
                </div>
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
