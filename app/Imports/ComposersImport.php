<?php

namespace App\Imports;

use App\Models\Composer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;

class ComposersImport implements ToModel
{
    use Importable;

    public function model(array $row)
    {
        if($row[0] != 'Composer Name'){
            return new Composer([
                'name' => $row[0],
                'profile_desc' => $row[1],
                'instagram' => $row[2],
                'twitter' => $row[3],
                'facebook' => $row[4],
                'asal_negara' => $row[5],
            ]);
        }else{
            return null;
        }
    }
}