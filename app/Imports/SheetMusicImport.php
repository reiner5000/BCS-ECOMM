<?php
namespace App\Imports;

use App\Models\Partitur;
use App\Models\Composer;
use App\Models\Collection;
use App\Models\Category;
use App\Models\CategoryDetails;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SheetMusicImport implements ToModel
{
    public function model(array $row)
    {
        $sheetmusic = NULL;
        if($row[0] != 'Sheet Music Name' && !is_null($row[0]) && $row[0] !== ''){
            $composer = Composer::where('name',$row[2])->first();
            $collection = Collection::where('name',$row[1])->first();

            // Menangani kategori
            // $categoryNames = explode(';', $row[4]); // Asumsikan data kategori ada di kolom ke-5 (index 4)
            // $categoryIds = [];
            // foreach ($categoryNames as $name) {
            //     $category = CategoryDetails::firstOrCreate(['name' => trim($name)]);
            //     $categoryIds[] = $category->id;
            // }

            if(!$composer){
                $composer = new Composer();
                $composer->name = $row[2];
                $composer->created_by = Auth::user()->id;
                $composer->save();
            }
            $composerId = $composer->id;

            if(!$collection){
                $collection = new Collection();
                $collection->name = $row[1];
                $collection->created_by = Auth::user()->id;
                $collection->save();
            }
            $collectionId = $collection->id;

            $sheetmusic = new Partitur([
                'name' => $row[0],
                'collection_id' => $collectionId,
                'composer_id' => $composerId,
                'deskripsi' => $row[3],
            ]);
            $sheetmusic->save();
        }

        // Opsional: Simpan kategori yang diimport ke dalam cache untuk akses cepat
        Cache::put('imported_sheetmusic', Partitur::pluck('id', 'name')->toArray(), 60);

        return $sheetmusic;
    }
}