@extends('layouts.app')

@section('content')
<div class="glass-card text-center">
    <div class="mb-4">
        @if(isset($settings) && $settings->logo_image)
            <img src="{{ asset($settings->logo_image) }}" alt="Logo" style="height: 100px; margin-bottom: 15px;">
        @endif
        <h1 class="title">{{ isset($settings) ? $settings->app_name : 'Pengumuman Kelulusan' }}</h1>
        <p class="subtitle">MTsN 2 Pesawaran T.A 2025/2026</p>
    </div>

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('student.check') }}" method="POST">
        @csrf
        <input type="hidden" name="login_type" id="login_type" value="nisn">
        
        <div class="mb-4">
            <label class="form-label" style="text-align: center;">Metode Login Siswa</label>
            <div style="display: flex; background: rgba(0,0,0,0.05); padding: 5px; border-radius: 12px; margin-bottom: 20px;">
                <button type="button" id="btn-nama" onclick="setLoginType('nama')" 
                    style="flex: 1; border-radius: 8px; border: none; padding: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s; background: transparent; color: var(--dark);">
                    Nama Lengkap
                </button>
                <button type="button" id="btn-nisn" class="active" onclick="setLoginType('nisn')" 
                    style="flex: 1; border-radius: 8px; border: none; padding: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s; background: var(--primary); color: white;">
                    NISN
                </button>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label" id="login_label">NISN</label>
            <input type="text" name="login_value" id="login_value" class="form-control" placeholder="Masukkan NISN" required>
        </div>
        <div class="form-group">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Cek Kelulusan</button>
    </form>
    
    <div class="mt-4" style="margin-top: 30px;">
        <a href="{{ route('admin.login') }}" style="color: #6B7280; font-size: 0.9rem; text-decoration: none;">Login Admin</a>
    </div>
</div>

<script>
function setLoginType(type) {
    const inputType = document.getElementById('login_type');
    const label = document.getElementById('login_label');
    const input = document.getElementById('login_value');
    const btnNama = document.getElementById('btn-nama');
    const btnNisn = document.getElementById('btn-nisn');
    
    inputType.value = type;
    
    if (type === 'nisn') {
        label.innerText = 'NISN';
        input.placeholder = 'Masukkan NISN';
        btnNisn.classList.add('active');
        btnNisn.style.background = 'var(--primary)';
        btnNisn.style.color = 'white';
        btnNama.classList.remove('active');
        btnNama.style.background = 'transparent';
        btnNama.style.color = 'var(--dark)';
    } else {
        label.innerText = 'Nama Lengkap';
        input.placeholder = 'Masukkan nama lengkap';
        btnNama.classList.add('active');
        btnNama.style.background = 'var(--primary)';
        btnNama.style.color = 'white';
        btnNisn.classList.remove('active');
        btnNisn.style.background = 'transparent';
        btnNisn.style.color = 'var(--dark)';
    }
}
</script>
@endsection
