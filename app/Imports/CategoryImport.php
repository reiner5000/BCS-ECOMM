<?php
namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Cache;

class CategoryImport implements ToModel
{
    public function model(array $row)
    {
        $category = NULL;
        if($row[0] != 'Category Name' && !is_null($row[0]) && $row[0] !== ''){
            $category = new Category([
                'name' => $row[0],
                'type' => $row[1],
            ]);

            $category->save();
        }

        // Opsional: Simpan kategori yang diimport ke dalam cache untuk akses cepat
        Cache::put('imported_categories', Category::pluck('id', 'name', 'type')->toArray(), 60);

        return $category;
    }
}