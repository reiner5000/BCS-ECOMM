<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Composer;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComposerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $composers = Composer::whereNull('deleted_at');

        if($request->sort == 'latest'){
            $composers = $composers->orderBy('created_at','desc');
        }else if($request->sort == 'asc'){
            $composers = $composers->orderBy('name','asc');
        }else if($request->sort == 'desc'){
            $composers = $composers->orderBy('name','desc');
        }else if($request->sort == 'country'){
            $composers = $composers->orderBy('asal_negara','asc');
        }else{
            $composers = $composers->orderBy('name','asc');
        }

        $composers = $composers->paginate(9);

        return view('composer.index', compact('composers'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // get composer
        $composer = Composer::with(['partiturs' => function($query) use ($request) {
            if ($request->sort == 'bestseller') {
                $query->select('partitur.id', 'partitur.name','partitur.deskripsi','partitur.collection_id','partitur.composer_id','partitur.file_image')
                ->leftJoin('partitur_detail', 'partitur.id', '=', 'partitur_detail.partitur_id')
                ->leftJoin('order_item', 'partitur_detail.id', '=', 'order_item.partitur_id')
                ->groupBy('partitur.id', 'partitur.name','partitur.deskripsi','partitur.collection_id','partitur.composer_id','partitur.file_image')
                ->orderByRaw('SUM(order_item.quantity) DESC')
                ->orderBy('partitur.name');
            } else if ($request->sort == 'latest') {
                $query->orderBy('created_at', 'desc');
            } else if ($request->sort == 'asc') {
                $query->orderBy('name', 'asc');
            } else if ($request->sort == 'desc') {
                $query->orderBy('name', 'desc');
            } else {
                $query->orderBy('name', 'asc');
            }

            if ($request->has('filter')) {
                if($request->filter != ''){
                    $filters = explode(',', $request->filter); 
                    $query->whereHas('details', function($q) use ($filters) {
                        $q->whereIn('category_detail_id', $filters);
                    });
                }
            }
        }])->where('name', $id)->first();

        $category = Category::where('type','Sheet Music')->orderBy('name')->get();

        $activeFilters = $request->has('filter') ? explode(',', $request->filter) : [];

        return view('composer.detail', compact('composer','category','activeFilters'));
    }
}