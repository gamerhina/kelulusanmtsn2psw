@extends('layouts.app')

@section('title', 'Admin Dashboard - Kelulusan')

@section('content')
<style>
    /* Override body alignment for admin so it doesn't jump vertically */
    body {
        align-items: flex-start !important;
        padding-top: 40px;
    }
    .container {
        max-width: 1300px !important;
    }
    .admin-layout {
        display: flex;
        gap: 30px;
        width: 100%;
        margin: 0 auto;
        align-items: flex-start;
        min-height: 80vh;
    }
    .admin-sidebar {
        width: 260px;
        flex-shrink: 0;
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        box-shadow: var(--shadow);
        position: sticky;
        top: 40px;
    }
    .admin-sidebar button {
        display: block;
        width: 100%;
        text-align: left;
        padding: 12px 15px;
        margin-bottom: 10px;
        border: none;
        background: transparent;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        color: var(--dark);
        transition: all 0.3s;
    }
    .admin-sidebar button:hover, .admin-sidebar button.active {
        background: var(--primary);
        color: white;
    }
    .admin-content {
        flex-grow: 1;
        min-width: 0; /* Prevents flex blowout */
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 30px;
        box-shadow: var(--shadow);
    }
    .tab-pane {
        display: none;
    }
    .tab-pane.active {
        display: block;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        backdrop-filter: blur(5px);
    }
    .modal-content {
        background: var(--glass-bg);
        margin: 10% auto;
        padding: 30px;
        border-radius: 15px;
        width: 500px;
        box-shadow: var(--shadow);
    }
</style>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h2 style="margin-bottom: 20px;">Edit Data Siswa</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-2"><label class="form-label">Nama</label><input type="text" name="nama" id="edit_nama" class="form-control" required></div>
            <div class="form-group mb-2"><label class="form-label">Tgl Lahir</label><input type="date" name="tanggal_lahir" id="edit_tanggal_lahir" class="form-control" required></div>
            <div class="form-group mb-2">
                <label class="form-label">Status</label>
                <select name="keterangan_kelulusan" id="edit_keterangan_kelulusan" class="form-control" required>
                    <option value="Lulus">Lulus</option>
                    <option value="Tidak Lulus">Tidak Lulus</option>
                </select>
            </div>
            <div class="form-group mb-2"><label class="form-label">Kelas</label><input type="text" name="kelas" id="edit_kelas" class="form-control" required></div>
            <div class="form-group mb-2"><label class="form-label">NISN</label><input type="text" name="nisn" id="edit_nisn" class="form-control"></div>
            <div class="form-group mb-2"><label class="form-label">Rata-rata</label><input type="number" step="0.01" name="rata_rata" id="edit_rata_rata" class="form-control"></div>
            
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete All Modal -->
<div id="deleteAllModal" class="modal">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <h2 style="color: #991B1B; margin-bottom: 15px;">⚠️ Hapus Semua Data?</h2>
        <p style="margin-bottom: 20px;">Tindakan ini akan menghapus **seluruh** data siswa secara permanen. Masukkan password admin untuk mengonfirmasi.</p>
        <form action="{{ route('admin.delete-all') }}" method="POST">
            @csrf
            <div class="form-group mb-4">
                <input type="password" name="admin_password" class="form-control" placeholder="Masukkan Password Admin" required>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-danger" style="background: #991B1B;">Hapus Permanen</button>
                <button type="button" class="btn btn-secondary" onclick="closeDeleteAllModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<div class="admin-layout">
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <h2 style="margin-bottom: 20px; font-size: 1.5rem;">Menu Admin</h2>
        <button class="tab-btn active" onclick="openTab('tab-data')">📝 Data Kelulusan</button>
        <button class="tab-btn" onclick="openTab('tab-akun')">👤 Pengaturan Akun</button>
        <button class="tab-btn" onclick="openTab('tab-tampilan')">🎨 Tampilan & Slider</button>
        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ccc;">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" style="color: #EF4444;">🚪 Logout</button>
        </form>
    </div>

    <!-- Content -->
    <div class="admin-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- TAB DATA KELULUSAN -->
        <div id="tab-data" class="tab-pane active">
            <h1 class="title mb-4">Kelola Data Kelulusan</h1>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                <div style="background: rgba(255,255,255,0.5); padding: 20px; border-radius: 10px;">
                    <h3 style="margin-bottom: 15px;">Tambah Data Manual</h3>
                    <form action="{{ route('admin.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-2"><input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required></div>
                        <div class="form-group mb-2"><input type="date" name="tanggal_lahir" class="form-control" required></div>
                        <div class="form-group mb-2">
                            <select name="keterangan_kelulusan" class="form-control" required>
                                <option value="">Pilih Status</option>
                                <option value="Lulus">Lulus</option>
                                <option value="Tidak Lulus">Tidak Lulus</option>
                            </select>
                        </div>
                        <div class="form-group mb-2"><input type="text" name="kelas" class="form-control" placeholder="Kelas" required></div>
                        <div class="form-group mb-2"><input type="text" name="nisn" class="form-control" placeholder="NISN"></div>
                        <div class="form-group mb-2"><input type="number" step="0.01" name="rata_rata" class="form-control" placeholder="Rata-rata"></div>
                        <button type="submit" class="btn btn-primary btn-sm mt-2">Simpan</button>
                    </form>
                </div>
                <div style="background: rgba(255,255,255,0.5); padding: 20px; border-radius: 10px;">
                    <h3 style="margin-bottom: 15px;">Import Data (Excel)</h3>
                    <p style="font-size: 0.9rem; margin-bottom: 15px;">Gunakan format template untuk mengimport data secara massal.</p>
                    <a href="{{ route('admin.export-template') }}" class="btn btn-secondary btn-sm mb-4" style="margin-bottom: 15px;">Download Template</a>
                    <form action="{{ route('admin.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-2"><input type="file" name="file" class="form-control" accept=".xlsx,.csv,.xls" required></div>
                        <button type="submit" class="btn btn-primary btn-sm mt-2">Import Data</button>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
                    <h3 style="margin: 0;">Data Siswa</h3>
                    
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <!-- Delete All Data (New) -->
                        <button type="button" class="btn btn-danger btn-sm" onclick="openDeleteAllModal()" style="width: auto; background: #991B1B;">⚠️ Hapus Semua Data</button>

                        <!-- Search Form -->
                        <form action="" method="GET" style="display: flex; gap: 5px;">
                            <input type="hidden" name="per_page" value="{{ $perPage }}">
                            <input type="text" name="search" class="form-control" placeholder="Cari Nama/NISN/Kelas..." value="{{ $search }}" style="width: 200px; padding: 5px 10px; height: 35px;">
                            <button type="submit" class="btn btn-primary btn-sm" style="width: auto; height: 35px;">Cari</button>
                            @if($search)
                                <a href="{{ route('admin.dashboard') }}?per_page={{ $perPage }}" class="btn btn-secondary btn-sm" style="width: auto; height: 35px; line-height: 23px;">Reset</a>
                            @endif
                        </form>

                        <!-- Per Page Select -->
                        <form action="" method="GET" style="display: flex; align-items: center; gap: 10px;">
                            <input type="hidden" name="search" value="{{ $search }}">
                            <label style="font-size: 0.9rem;">Tampilkan:</label>
                            <select name="per_page" class="form-control" style="width: auto; padding: 5px; height: 35px;" onchange="this.form.submit()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="all" {{ $perPage == 'all' ? 'selected' : '' }}>Semua</option>
                            </select>
                        </form>
                    </div>
                </div>
                
                @php
                    function sortUrl($field, $currentSortBy, $currentSortOrder, $currentPerPage, $currentSearch) {
                        $order = ($field == $currentSortBy && $currentSortOrder == 'asc') ? 'desc' : 'asc';
                        return route('admin.dashboard') . "?per_page=$currentPerPage&search=$currentSearch&sort_by=$field&sort_order=$order";
                    }
                    
                    function sortIcon($field, $currentSortBy, $currentSortOrder) {
                        if ($field != $currentSortBy) return '↕️';
                        return $currentSortOrder == 'asc' ? '🔼' : '🔽';
                    }
                @endphp

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th><a href="{{ sortUrl('nama', $sortBy, $sortOrder, $perPage, $search) }}" style="color: white; text-decoration: none;">Nama {!! sortIcon('nama', $sortBy, $sortOrder) !!}</a></th>
                            <th><a href="{{ sortUrl('tanggal_lahir', $sortBy, $sortOrder, $perPage, $search) }}" style="color: white; text-decoration: none;">Tgl Lahir {!! sortIcon('tanggal_lahir', $sortBy, $sortOrder) !!}</a></th>
                            <th><a href="{{ sortUrl('keterangan_kelulusan', $sortBy, $sortOrder, $perPage, $search) }}" style="color: white; text-decoration: none;">Status {!! sortIcon('keterangan_kelulusan', $sortBy, $sortOrder) !!}</a></th>
                            <th><a href="{{ sortUrl('kelas', $sortBy, $sortOrder, $perPage, $search) }}" style="color: white; text-decoration: none;">Kelas {!! sortIcon('kelas', $sortBy, $sortOrder) !!}</a></th>
                            <th><a href="{{ sortUrl('nisn', $sortBy, $sortOrder, $perPage, $search) }}" style="color: white; text-decoration: none;">NISN {!! sortIcon('nisn', $sortBy, $sortOrder) !!}</a></th>
                            <th><a href="{{ sortUrl('rata_rata', $sortBy, $sortOrder, $perPage, $search) }}" style="color: white; text-decoration: none;">Rata-rata {!! sortIcon('rata_rata', $sortBy, $sortOrder) !!}</a></th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                        <tr>
                            <td>{{ ($students instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($students->firstItem() + $index) : ($index + 1) }}</td>
                            <td>{{ $student->nama }}</td>
                            <td>{{ $student->tanggal_lahir }}</td>
                            <td><span style="color: {{ strtolower($student->keterangan_kelulusan) == 'lulus' ? 'green' : 'red' }}; font-weight: bold;">{{ $student->keterangan_kelulusan }}</span></td>
                            <td>{{ $student->kelas }}</td>
                            <td>{{ $student->nisn }}</td>
                            <td>{{ $student->rata_rata }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="editStudent({{ json_encode($student) }})" style="padding: 4px 8px; font-size: 0.8rem;">Edit</button>
                                    <form action="{{ route('admin.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 0.8rem;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center" style="padding: 20px;">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                @if($students instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-4">
                    {{ $students->links('pagination::bootstrap-4') }}
                </div>
                @endif
            </div>
        </div>

        <!-- TAB PENGATURAN AKUN -->
        <div id="tab-akun" class="tab-pane">
            <h1 class="title mb-4">Pengaturan Akun Admin</h1>
            <div style="background: rgba(255,255,255,0.5); padding: 20px; border-radius: 10px; max-width: 500px;">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    <div class="form-group mb-2">
                        <label class="form-label">Email Admin</label>
                        <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diubah">
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Update Profil</button>
                </form>
            </div>
        </div>

        <!-- TAB TAMPILAN & SLIDER -->
        <div id="tab-tampilan" class="tab-pane">
            <h1 class="title mb-4">Pengaturan Tampilan & Slider</h1>
            <div style="background: rgba(255,255,255,0.5); padding: 20px; border-radius: 10px;">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4">
                        <label class="form-label">Nama Aplikasi</label>
                        <input type="text" name="app_name" class="form-control" value="{{ $settings->app_name ?? 'Pengumuman Kelulusan MTsN 2 Pesawaran' }}" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label class="form-label">Warna Utama</label>
                            <input type="color" name="primary_color" class="form-control" value="{{ $settings->primary_color ?? '#4F46E5' }}" style="height: 40px; padding: 2px;">
                        </div>
                        <div>
                            <label class="form-label">Warna Sekunder</label>
                            <input type="color" name="secondary_color" class="form-control" value="{{ $settings->secondary_color ?? '#10B981' }}" style="height: 40px; padding: 2px;">
                        </div>
                        <div>
                            <label class="form-label">Gradien Latar 1</label>
                            <input type="color" name="bg_gradient_start" class="form-control" value="{{ $settings->bg_gradient_start ?? '#f6d365' }}" style="height: 40px; padding: 2px;">
                        </div>
                        <div>
                            <label class="form-label">Gradien Latar 2</label>
                            <input type="color" name="bg_gradient_end" class="form-control" value="{{ $settings->bg_gradient_end ?? '#fda085' }}" style="height: 40px; padding: 2px;">
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label">Ganti Logo Utama (Biarkan kosong jika tidak ingin mengubah)</label>
                        <input type="file" name="logo_upload" class="form-control" accept="image/*">
                    </div>
                    
                    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ccc;">
                    
                    <h3 style="margin-bottom: 15px;">Pengaturan Slider & Animasi Latar</h3>
                    
                    <div class="form-group mb-4">
                        <label class="form-label">Jenis Animasi Latar Depan (Elemen Mengambang)</label>
                        <select name="foreground_animation" class="form-control">
                            <option value="balls" {{ ($settings->foreground_animation ?? 'balls') == 'balls' ? 'selected' : '' }}>Bola Mengambang</option>
                            <option value="wave" {{ ($settings->foreground_animation ?? 'balls') == 'wave' ? 'selected' : '' }}>Gelombang (Wave)</option>
                            <option value="geometric" {{ ($settings->foreground_animation ?? 'balls') == 'geometric' ? 'selected' : '' }}>Bentuk Geometris</option>
                            <option value="none" {{ ($settings->foreground_animation ?? 'balls') == 'none' ? 'selected' : '' }}>Tanpa Animasi</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Jenis Animasi Transisi Slider</label>
                        <select name="slider_animation" class="form-control">
                            <option value="fade" {{ ($settings->slider_animation ?? 'fade') == 'fade' ? 'selected' : '' }}>Fade (Memudar)</option>
                            <option value="slide" {{ ($settings->slider_animation ?? 'fade') == 'slide' ? 'selected' : '' }}>Slide (Bergeser)</option>
                            <option value="none" {{ ($settings->slider_animation ?? 'fade') == 'none' ? 'selected' : '' }}>Tanpa Animasi</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Waktu Transisi Slider (dalam milidetik, contoh: 5000 = 5 detik)</label>
                        <input type="number" name="slider_interval" class="form-control" value="{{ $settings->slider_interval ?? 5000 }}" required>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Daftar Gambar Slider Saat Ini</label>
                        <div id="slider-list" style="background: white; border-radius: 8px; padding: 10px; border: 1px solid #ccc;">
                            @if(empty($settings->slider_images))
                                <p style="padding: 10px; color: #666; font-style: italic;">Belum ada gambar slider.</p>
                            @else
                                @foreach($settings->slider_images as $index => $img)
                                    <div class="slider-item" style="display: flex; align-items: center; gap: 15px; padding: 10px; border-bottom: 1px solid #eee;">
                                        <img src="{{ asset($img) }}" alt="Slider" style="height: 50px; width: 80px; object-fit: cover; border-radius: 4px;">
                                        <input type="hidden" name="existing_slider_images[]" value="{{ $img }}">
                                        <div style="flex-grow: 1; word-break: break-all; font-size: 0.9rem;">{{ $img }}</div>
                                        <div style="display: flex; gap: 5px;">
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="moveUp(this)" style="padding: 4px 8px;">⬆️</button>
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="moveDown(this)" style="padding: 4px 8px;">⬇️</button>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteItem(this)" style="padding: 4px 8px;">❌</button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label">Tambah Gambar Slider Baru (Bisa pilih lebih dari satu)</label>
                        <input type="file" name="slider_upload[]" class="form-control" accept="image/*" multiple>
                    </div>
                    <div class="form-group mb-4">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" name="reset_slider" value="1" style="width: 20px; height: 20px;">
                            <span style="color: #EF4444; font-weight: bold;">Hapus Semua Gambar Slider (Reset)</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2">Simpan Semua Pengaturan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openTab(tabId) {
        document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        
        document.getElementById(tabId).classList.add('active');
        event.currentTarget.classList.add('active');
    }
    
    // Check hash for direct tab linking
    document.addEventListener("DOMContentLoaded", () => {
        if(window.location.hash) {
            const hash = window.location.hash.substring(1);
            const btn = document.querySelector(`button[onclick="openTab('${hash}')"]`);
            if(btn) btn.click();
        }
    });

    function moveUp(btn) {
        const item = btn.closest('.slider-item');
        const prev = item.previousElementSibling;
        if(prev) {
            item.parentNode.insertBefore(item, prev);
        }
    }

    function moveDown(btn) {
        const item = btn.closest('.slider-item');
        const next = item.nextElementSibling;
        if(next) {
            item.parentNode.insertBefore(next, item);
        }
    }

    function deleteItem(btn) {
        if(confirm('Hapus gambar ini dari daftar? (Tidak akan terhapus dari server, hanya dari daftar slider)')) {
            btn.closest('.slider-item').remove();
        }
    }

    function editStudent(student) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        document.getElementById('edit_nama').value = student.nama;
        document.getElementById('edit_tanggal_lahir').value = student.tanggal_lahir;
        document.getElementById('edit_keterangan_kelulusan').value = student.keterangan_kelulusan;
        document.getElementById('edit_kelas').value = student.kelas;
        document.getElementById('edit_nisn').value = student.nisn || '';
        document.getElementById('edit_rata_rata').value = student.rata_rata || '';
        
        form.action = `/admin/${student.id}`;
        modal.style.display = 'block';
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    function openDeleteAllModal() {
        document.getElementById('deleteAllModal').style.display = 'block';
    }

    function closeDeleteAllModal() {
        document.getElementById('deleteAllModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const editModal = document.getElementById('editModal');
        const deleteAllModal = document.getElementById('deleteAllModal');
        if (event.target == editModal) {
            closeModal();
        }
        if (event.target == deleteAllModal) {
            closeDeleteAllModal();
        }
    }
</script>
@endsection
