<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $tanggalLahir = null;
        if (isset($row['tanggal_lahir'])) {
            if (is_numeric($row['tanggal_lahir'])) {
                $tanggalLahir = Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
            } else {
                try {
                    $tanggalLahir = \Carbon\Carbon::createFromFormat('n/j/Y', $row['tanggal_lahir'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $tanggalLahir = date('Y-m-d', strtotime($row['tanggal_lahir']));
                }
            }
        }

        return Student::updateOrCreate(
            ['nisn' => $row['nisn'] ?? null],
            [
                'nama' => $row['nama'] ?? null,
                'tanggal_lahir' => $tanggalLahir,
                'keterangan_kelulusan' => $row['keterangan_kelulusan'] ?? null,
                'kelas' => $row['kelas'] ?? null,
                'rata_rata' => isset($row['rata_rata']) ? (float)$row['rata_rata'] : null,
            ]
        );
    }
}
