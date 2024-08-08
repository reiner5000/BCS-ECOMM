<?php
namespace App\Imports;

use App\Models\Category;
use App\Models\CategoryDetails;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Cache;

class CategoryDetailImport implements ToModel
{
    public function model(array $row)
    {
        // Coba dapatkan ID kategori dari cache
        if($row[0] != 'Category Name' && !is_null($row[0]) && $row[0] !== ''){
            $categories = Cache::get('imported_categories', []);
            $categoryId = $categories[$row[0]] ?? null;
    
            if (!$categoryId) {
                // Opsional: Handle kasus dimana kategori tidak ditemukan
                return null;
            }
    
            return new CategoryDetails([
                'name' => $row[1],
                'category_id' => $categoryId,
            ]);
        }else{
            return null;
        }
        
    }
}