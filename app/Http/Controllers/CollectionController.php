<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\Category;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $collection = Collection::whereNull('deleted_at');

        if($request->sort == 'latest'){
            $collection = $collection->orderBy('created_at','desc');
        }else if($request->sort == 'asc'){
            $collection = $collection->orderBy('name','asc');
        }else if($request->sort == 'desc'){
            $collection = $collection->orderBy('name','desc');
        }else{
            $collection = $collection->orderBy('name','asc');
        }

        $collection = $collection->paginate(10);

        return view('collection.index', ['collection' => $collection, 'sortText' => '']);
    }

    public function indexSort($sort)
    {
        if($sort == 'newest'){
            $collection = Collection::
            where('deleted_at', null)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            $sortText = 'Newest';
        } else if($sort == 'a-z'){
            $collection = Collection::
            where('deleted_at', null)
            ->orderBy('name', 'asc')
            ->paginate(10);

            $sortText = ' A-Z';
        } else if($sort == 'z-a'){
            $collection = Collection::
            where('deleted_at', null)
            ->orderBy('name', 'desc')
            ->paginate(10);

            $sortText = 'Z-A';
        } else {
            $collection = Collection::
            where('deleted_at', null)
            ->paginate(10);
        }
        
        return view('collection.index', ['collection' => $collection, 'sortText' => $sortText]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // get collection
        $collection = Collection::with(['partiturs' => function($query) use ($request) {
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

        return view('collection.detail', compact('collection','category','activeFilters'));
    }


}