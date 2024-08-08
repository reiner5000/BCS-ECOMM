<?php
namespace App\Imports;

use App\Models\Partitur;
use App\Models\PartiturDetail;
use App\Models\Category;
use App\Models\CategoryDetails;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SheetMusicDetailImport implements ToModel
{
    public function model(array $row)
    {
        // Coba dapatkan ID kategori dari cache
        if($row[0] != 'Sheet Music Name' && !is_null($row[0]) && $row[0] !== ''){
            $sheetmusic = Cache::get('imported_sheetmusic', []);
            $sheetmusicId = $sheetmusic[$row[0]] ?? null;
    
            if (!$sheetmusicId) {
                // Opsional: Handle kasus dimana kategori tidak ditemukan
                return null;
            }

            // $categoryNames = explode(';', $row[2]); // Memisahkan nama-nama kategori
            // $categoryIds = [];
            // foreach ($categoryNames as $name) {
            //     $name = trim($name);
            //     $category = CategoryDetails::where('name', $name)->first();
            //     if (!$category) {
            //         $mst = new Category();
            //         $mst->name = $name;
            //         $mst->type = 'Sheet Music';
            //         $mst->created_by = Auth::user()->id;
            //         $mst->save();

            //         $category = new CategoryDetails();
            //         $category->name = $name;
            //         $category->category_id = $mst->id;
            //         $category->created_by = Auth::user()->id;
            //         $category->save();
            //     }
            //     $categoryIds[] = $category->id;
            // }
    
            return new PartiturDetail([
                'name' => $row[1],
                'deskripsi' => $row[8],
                'preview_audio' => $row[6],
                'preview_video' => $row[7],
                'file_type' => $row[3],
                'harga' => $row[4],
                'minimum_order' => $row[5],
                'partitur_id' => $sheetmusicId,
                'category_detail_id' => str_replace(";", ",", $row[2]),
            ]);
        }else{
            return null;
        }
        
    }
}