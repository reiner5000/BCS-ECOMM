<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiCategoryImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new CategoryImport(),
            1 => new CategoryDetailImport(),
        ];
    }

    public function headingRow(): int
    {
        return 1;
    }
}