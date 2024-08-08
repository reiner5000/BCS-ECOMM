<?php

namespace App\Imports;

use App\Models\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;

class CollectionImport implements ToModel
{
    use Importable;

    public function model(array $row)
    {
        if($row[0] != 'Collection Name'){
            return new Collection([
                'name' => $row[0],
                'short_description' => $row[1],
            ]);
        }else{
            return null;
        }
    }
}