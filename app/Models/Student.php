<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'keterangan_kelulusan',
        'kelas',
        'nisn',
        'rata_rata',
    ];
}
