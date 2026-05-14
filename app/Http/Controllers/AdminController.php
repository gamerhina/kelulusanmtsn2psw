<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsTemplateExport;

class AdminController extends Controller
{
    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('admin');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $query = Student::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nisn', 'like', "%$search%")
                  ->orWhere('kelas', 'like', "%$search%");
            });
        }

        $query->orderBy($sortBy, $sortOrder);
        
        if ($perPage == 'all') {
            $students = $query->get();
        } else {
            $students = $query->paginate((int)$perPage)->withQueryString();
        }
        
        return view('admin.dashboard', compact('students', 'perPage', 'search', 'sortBy', 'sortOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'tanggal_lahir' => 'required|string',
            'keterangan_kelulusan' => 'required|string',
            'kelas' => 'required|string',
            'nisn' => 'nullable|string',
            'rata_rata' => 'nullable|numeric',
        ]);

        try {
            $parsedDate = \Carbon\Carbon::parse($validated['tanggal_lahir'])->format('Y-m-d');
        } catch (\Exception $e) {
            return back()->withErrors(['tanggal_lahir' => 'Format tanggal lahir tidak valid.']);
        }
        
        $validated['tanggal_lahir'] = $parsedDate;
        $validated['keterangan_kelulusan'] = strtoupper($validated['keterangan_kelulusan']);

        // Use updateOrCreate if NISN is provided, otherwise just create
        if ($request->filled('nisn')) {
            Student::updateOrCreate(['nisn' => $request->nisn], $validated);
        } else {
            Student::create($validated);
        }

        return back()->with('success', 'Data siswa berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string',
            'tanggal_lahir' => 'required|string',
            'keterangan_kelulusan' => 'required|string',
            'kelas' => 'required|string',
            'nisn' => 'nullable|string|unique:students,nisn,' . $id,
            'rata_rata' => 'nullable|numeric',
        ]);

        try {
            $parsedDate = \Carbon\Carbon::parse($validated['tanggal_lahir'])->format('Y-m-d');
        } catch (\Exception $e) {
            return back()->withErrors(['tanggal_lahir' => 'Format tanggal lahir tidak valid.']);
        }
        
        $validated['tanggal_lahir'] = $parsedDate;
        $validated['keterangan_kelulusan'] = strtoupper($validated['keterangan_kelulusan']);
        $student->update($validated);

        return back()->with('success', 'Data siswa berhasil diupdate.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        Excel::import(new StudentsImport, $request->file('file'));

        return back()->with('success', 'Data berhasil diimport.');
    }

    public function exportTemplate()
    {
        return Excel::download(new StudentsTemplateExport, 'template_kelulusan.xlsx');
    }

    public function destroy($id)
    {
        Student::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function deleteAll(Request $request)
    {
        $request->validate([
            'admin_password' => 'required'
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->admin_password, Auth::user()->password)) {
            return back()->with('error', 'Konfirmasi password salah. Data tidak dihapus.');
        }

        Student::truncate();

        return back()->with('success', 'Seluruh data siswa berhasil dihapus.');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];
        if ($request->filled('password')) {
            $rules['password'] = 'min:6|confirmed';
        }
        $request->validate($rules);

        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        $user->save();

        return back()->with('success', 'Profil admin berhasil diubah.');
    }

    public function updateSettings(Request $request)
    {
        $settings = \App\Models\Setting::firstOrCreate(['id' => 1]);
        
        $data = $request->except(['_token', 'logo_upload', 'slider_upload', 'existing_slider_images', 'reset_slider']);
        
        if ($request->hasFile('logo_upload')) {
            $logoName = time() . '_logo.' . $request->logo_upload->extension();
            $request->logo_upload->move(public_path('img'), $logoName);
            $data['logo_image'] = 'img/' . $logoName;
        }

        $images = $request->input('existing_slider_images', []);

        if ($request->hasFile('slider_upload')) {
            foreach($request->file('slider_upload') as $file) {
                $sliderName = time() . '_' . uniqid() . '_slider.' . $file->extension();
                $file->move(public_path('img'), $sliderName);
                $images[] = 'img/' . $sliderName;
            }
        }

        if ($request->has('reset_slider')) {
            $images = [];
        }

        $data['slider_images'] = $images;

        $settings->update($data);

        return back()->with('success', 'Pengaturan tampilan berhasil disimpan.');
    }
}
