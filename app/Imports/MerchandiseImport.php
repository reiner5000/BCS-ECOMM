<?php
namespace App\Imports;

use App\Models\Merchandise;
use App\Models\Category;
use App\Models\CategoryDetails;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Cache;

class MerchandiseImport implements ToModel
{
    public function model(array $row)
    {
        $merchandise = NULL;
        if($row[0] != 'Merchandise Name' && !is_null($row[0]) && $row[0] !== ''){
                // $category = CategoryDetails::where('name',$row[1])->first();
                // if(!$category){
                //     $mst = new Category();
                //     $mst->name = $row[1];
                //     $mst->type = 'Merchandise';
                //     $mst->created_by = Auth::user()->id;
                //     $mst->save();

                //     $category = new CategoryDetails();
                //     $category->name = $row[1];
                //     $category->category_id = $mst->id;
                //     $category->created_by = Auth::user()->id;
                //     $category->save();
                // }
                // $categoryId = $category->id;

                // $categoryNames = explode(';', $row[4]); // Asumsikan data kategori ada di kolom ke-5 (index 4)
                // $categoryIds = [];
                // foreach ($categoryNames as $name) {
                //     $category = CategoryDetails::firstOrCreate(['name' => trim($name)]);
                //     $categoryIds[] = $category->id;
                // }

            $merchandise = new Merchandise([
                'name' => $row[0],
                'harga' => $row[2],
                'stok' => $row[3],
                'deskripsi' => $row[4],
                'category_detail_id' => str_replace(";", ",", $row[1]),
            ]);

            $merchandise->save();
        }

        // Opsional: Simpan kategori yang diimport ke dalam cache untuk akses cepat
        Cache::put('imported_merchandise', Merchandise::pluck('id', 'name')->toArray(), 60);

        return $merchandise;
    }
}