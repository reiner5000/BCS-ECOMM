<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partitur;
use App\Models\PartiturDetail;
use App\Models\Composer;
use App\Models\Collection;
use App\Models\Category;
use App\Models\Merchandise;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories1 = Category::with('details')->where('type','Sheet Music')->get();
        $categories2 = Category::with('details')->where('type','Merchandise')->get();

        $type = $request->query('t');
        $id = $request->query('s');

        return view('publisher.index', compact('categories1','categories2','type','id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $partitur = Partitur::with('details')->where('name',$name)->first();
        $rekomendasi = Partitur::inRandomOrder()->limit(5)->get();
        if($partitur){
            return view('publisher.detail', compact('partitur','rekomendasi'));
        }else{
            return redirect()->back();
        }
    }

    // create showMerchandise function
    public function showMerchandise($name)
    {
        // dd('masuk');
        $merchandise = Merchandise::where('name',$name)->first();
        $rekomendasi = Merchandise::inRandomOrder()->limit(5)->get();
        if($merchandise){
            return view('publisher.merchandise', compact('merchandise','rekomendasi'));
        }else{
            return redirect()->back();
        }
    }

    public function fetchPartitur(Request $request)
    {
        if ($request->ajax()) {
            // Dapatkan array 'details' dari request, jika tidak ada, gunakan array kosong sebagai default
            $details = $request->input('details', []);
        
            // Inisialisasi query
            $query = Partitur::query()->with('details')->orderBy('name');
        
            if($request->type && $request->id){
                if($request->type == "composer"){
                    $query = $query->where("composer_id",$request->id);
                }
                if($request->type == "collection"){
                    $query = $query->where("collection_id",$request->id);
                }
                if($request->type == "sheetmusic"){
                    $query = $query->where("id",$request->id);
                }
            }

            // Periksa apakah array 'undefined' ada dalam 'details'
            if (isset($details['undefined'])) {
                // Filter partitur berdasarkan ID detail dalam array 'undefined'
                $undefinedDetails = array_filter($details['undefined'], function($value) {
                    return $value !== 'on'; // Filter out any unwanted values, jika ada
                });
        
                if (!empty($undefinedDetails)) {
                    $query->whereHas('details', function ($query) use ($undefinedDetails) {
                        $first = true;  

                        foreach ($undefinedDetails as $detail) {
                            if ($first) {
                                $query->where('category_detail_id', 'like', '%' . $detail . '%');
                                $first = false;  // Setelah iterasi pertama, set flag ke false
                            } else {
                                $query->orWhere('category_detail_id', 'like', '%' . $detail . '%');
                            }
                        }
                    });
                }
            }

            $partiturs = $query->paginate(10); 
            $partiturs->getCollection()->transform(function ($partitur) {
            if ($partitur->file_image) {
                $images = explode(',', $partitur->file_image);
                $partitur->file_image_first = asset('public/' . $images[0]);
            } else {
                $partitur->file_image_first = null; 
            }

            return $partitur;
        });

        return response()->json($partiturs);
        }
    }

    // create fetchMerchandise function
    public function fetchMerchandise(Request $request)
    {
        if ($request->ajax()) {
            // Dapatkan array 'details' dari request, jika tidak ada, gunakan array kosong sebagai default
            $details = $request->input('details', []);
        
            // Inisialisasi query
            $query = Merchandise::query()->orderBy('name');
        
            if($request->type && $request->id){
                if($request->type == "merchandise"){
                    $query = $query->where("id",$request->id);
                }
            }

            // Periksa apakah array 'undefined' ada dalam 'details'
            if (isset($details['undefined'])) {
                // Filter partitur berdasarkan ID detail dalam array 'undefined'
                $undefinedDetails = array_filter($details['undefined'], function($value) {
                    return $value !== 'on'; // Filter out any unwanted values, jika ada
                });
        
                if (!empty($undefinedDetails)) {
                    $query->whereHas('details', function ($query) use ($undefinedDetails) {
                        $first = true;
                        foreach ($undefinedDetails as $detail) {
                            if ($first) {
                                // Gunakan 'where' untuk iterasi pertama
                                $query->where('category_detail_id', 'like', '%' . $detail . '%');
                                $first = false; 
                            } else {
                                // Gunakan 'orWhere' untuk iterasi selanjutnya
                                $query->orWhere('category_detail_id', 'like', '%' . $detail . '%');
                            }
                        }
                    });
                }
            }

            $merchandises = $query->paginate(10); 
            $merchandises->getCollection()->transform(function ($merchandise) {
            if ($merchandise->file_image) {
                $images = explode(',', $merchandise->file_image);
                $merchandise->file_image_first = asset('public/' . $images[0]);
            } else {
                $merchandise->file_image_first = null; 
            }

            return $merchandise;
        });

        return response()->json($merchandises);
        }
    }


    public function getFilters()
    {
        $categories1 = Category::where('type','Sheet Music')->get();
        $categories2 = Category::where('type','Merchandise')->get();
        $composers = Composer::where('deleted_at',null)->get();
        $years = Partitur::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year');

        return response()->json([
            'categories1' => $categories1,
            'categories2' => $categories2,
            'composers' => $composers,
            'years' => $years
        ]);
    }

}