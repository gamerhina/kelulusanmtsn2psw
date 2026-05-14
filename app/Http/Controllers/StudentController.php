<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        return view('student.index');
    }

    public function check(Request $request)
    {
        $request->validate([
            'login_type' => 'required|in:nama,nisn',
            'login_value' => 'required|string',
            'tanggal_lahir' => 'required|date',
        ]);

        $query = Student::whereDate('tanggal_lahir', $request->tanggal_lahir);

        if ($request->login_type === 'nisn') {
            $query->where('nisn', $request->login_value);
        } else {
            $query->where('nama', $request->login_value);
        }

        $student = $query->first();

        if (!$student) {
            $identitas = $request->login_type === 'nisn' ? 'NISN' : 'nama';
            return back()->with('error', "Data tidak ditemukan. Pastikan $identitas dan tanggal lahir sesuai.");
        }

        return view('student.result', compact('student'));
    }
}
