<?php

namespace App\Exports;

use App\Models\Nilai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class NilaiExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    ShouldAutoSize,
    WithCustomStartCell
{
    public function startCell(): string
    {
        return 'A1';
    }

    public function collection()
    {
        return Nilai::get()->map(function ($nilai, $index) {
            return [
                $index + 1,
                $nilai->nama,
                $nilai->nilai_uas,
                $nilai->nilai_uts,
                $nilai->nilai_un,
                $nilai->kehadiran,
                $nilai->keterlambatan,
                $nilai->prestasi,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Nilai UAS',
            'Nilai UTS',
            'Nilai UN',
            'Kehadiran',
            'Keterlambatan',
            'Prestasi',
        ];
    }

    public function styles(Worksheet $sheet)
    {

        // 🔹 HEADER BORDER (baris 2)
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
        ]);

        // 🔹 BORDER DATA
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:H{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        return [];
    }
}
