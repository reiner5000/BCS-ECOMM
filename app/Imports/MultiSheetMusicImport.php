<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Importable;

class MultiSheetMusicImport implements WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            0 => new SheetMusicImport(),
            1 => new SheetMusicDetailImport(),
        ];
    }
}