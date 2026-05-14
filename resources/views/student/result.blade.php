@extends('layouts.app')

@section('content')
<div class="glass-card result-card">
    <h1 class="title">Hasil Kelulusan</h1>
    <p class="subtitle">MTsN 2 Pesawaran T.A 2025/2026</p>

    @php
        $isLulus = strtolower($student->keterangan_kelulusan) === 'lulus';
    @endphp

    <div id="result-status" data-status="{{ $student->keterangan_kelulusan }}">
        @if($isLulus)
            <h2 class="result-lulus lulus-animation">Selamat!</h2>
            <p>Anda dinyatakan</p>
            <div class="status-badge badge-lulus">LULUS</div>
        @else
            <h2 class="result-tidak-lulus">Tetap Semangat!</h2>
            <p>Maaf, Anda dinyatakan</p>
            <div class="status-badge badge-tidak">TIDAK LULUS</div>
            <p style="margin-top: 10px; color: #6B7280;">Jangan menyerah, kegagalan adalah keberhasilan yang tertunda.</p>
        @endif
    </div>

    <div class="student-info">
        <p><strong>Nama</strong> : {{ $student->nama }}</p>
        <p><strong>NISN</strong> : {{ $student->nisn }}</p>
        <p><strong>Kelas</strong> : {{ $student->kelas }}</p>
        <p><strong>Rata-rata</strong> : {{ $student->rata_rata }}</p>
    </div>

    <div class="mt-4" style="margin-top: 30px;">
        <a href="{{ route('student.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
