<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CategoryTemplateExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [];

        // Sheet pertama untuk Category
        $sheets[] = new class implements \Maatwebsite\Excel\Concerns\WithHeadings {
            public function headings(): array
            {
                return ['Nama Kategori'];
            }
        };

        // Sheet kedua untuk CategoryDetail
        $sheets[] = new class implements \Maatwebsite\Excel\Concerns\WithHeadings {
            public function headings(): array
            {
                return ['Nama Detail', 'Nama Kategori'];
            }
        };

        return $sheets;
    }
}