@extends('layouts.app')

@section('content')
<div class="glass-card text-center">
    <div class="mb-4">
        <h1 class="title">Admin Login</h1>
        <p class="subtitle">Kelola Data Kelulusan</p>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <div style="position: relative;">
                <input type="password" name="password" id="password" class="form-control" required style="padding-right: 45px;">
                <button type="button" onclick="togglePassword()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6B7280; padding: 5px; display: flex; align-items: center; justify-content: center;">
                    <span id="eye-icon-visible">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </span>
                    <span id="eye-icon-hidden" style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </span>
                </button>
            </div>
        </div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeVisible = document.getElementById('eye-icon-visible');
    const eyeHidden = document.getElementById('eye-icon-hidden');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeVisible.style.display = 'none';
        eyeHidden.style.display = 'block';
    } else {
        passwordInput.type = 'password';
        eyeVisible.style.display = 'block';
        eyeHidden.style.display = 'none';
    }
}
</script>
        <button type="submit" class="btn btn-primary mt-4">Login</button>
    </form>
    
    <div class="mt-4" style="margin-top: 20px;">
        <a href="{{ route('student.index') }}" style="color: #6B7280; font-size: 0.9rem; text-decoration: none;">Kembali ke Halaman Siswa</a>
    </div>
</div>
@endsection
