<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Importable;

class MultiMerchandiseImport implements WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            0 => new MerchandiseImport(),
            1 => new MerchandiseDetailImport(),
        ];
    }
}