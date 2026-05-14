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
            <input type="email" name="email" class="form-control" placeholder="admin@mtsn2psw.sch.id" required>
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Login</button>
    </form>
    
    <div class="mt-4" style="margin-top: 20px;">
        <a href="{{ route('student.index') }}" style="color: #6B7280; font-size: 0.9rem; text-decoration: none;">Kembali ke Halaman Siswa</a>
    </div>
</div>
@endsection
