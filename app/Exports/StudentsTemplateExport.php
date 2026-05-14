<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['Budi Santoso', '2010-05-14', 'Lulus', 'IX A', '1234567890', '85.5'],
            ['Siti Aminah', '2010-08-20', 'Lulus', 'IX B', '0987654321', '88.0'],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Tanggal Lahir',
            'Keterangan Kelulusan',
            'Kelas',
            'NISN',
            'Rata_rata',
        ];
    }
}
