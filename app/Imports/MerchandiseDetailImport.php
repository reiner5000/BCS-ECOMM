<?php
namespace App\Imports;

use App\Models\Merchandise;
use App\Models\MerchandiseDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Cache;

class MerchandiseDetailImport implements ToModel
{
    public function model(array $row)
    {
        // Coba dapatkan ID kategori dari cache
        if($row[0] != 'Merchandise Name' && !is_null($row[0]) && $row[0] !== ''){
            $merchandise = Cache::get('imported_merchandise', []);
            $merchandiseId = $merchandise[$row[0]] ?? null;
    
            if (!$merchandiseId) {
                // Opsional: Handle kasus dimana kategori tidak ditemukan
                return null;
            }
    
            if($row[1] == 'size' || $row[1] == 'Size'){
                return new MerchandiseDetail([
                    'size' => $row[2],
                    'color' => '',
                    'merchandise_id' => $merchandiseId,
                ]);
            }else{
                return new MerchandiseDetail([
                    'size' => '',
                    'color' => $row[2],
                    'merchandise_id' => $merchandiseId,
                ]);
            }
        }else{
            return null;
        }
        
    }
}