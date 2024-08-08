<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Banner;
use App\Models\CategoryDetails;
use App\Models\Collection;
use App\Models\Partitur;
// use App\Models\Collection;


class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Partitur::orderBy('created_at','desc');
        $partiturs = $query->paginate(4); 
        $partiturs->getCollection()->transform(function ($partitur) {
            if ($partitur->file_image) {
                $images = explode(',', $partitur->file_image);
                $partitur->file_image_first = asset('public/' . $images[0]);
            } else {
                $partitur->file_image_first = null; 
            }
            return $partitur;
        });
        
        $banner = Banner::get();
        $category = CategoryDetails::whereHas('category', function($query) {
            $query->where('type', 'sheet music');
        })->orderBy('name')->get();
        $topCollections = Collection::select('collection.id','collection.name','collection.cover', DB::raw('COUNT(order_item.id) as total_orders'))
        ->leftJoin('partitur', 'partitur.collection_id', '=', 'collection.id')
        ->leftJoin('order_item', 'order_item.partitur_id', '=', 'partitur.id')
        ->groupBy('collection.id','collection.name','collection.cover')
        ->orderBy('total_orders', 'DESC')
        ->take(10)
        ->get();

        return view('homepage',['banners'=>$banner,'categorys'=>$category,'topCollection'=>$topCollections,'partitur'=>$partiturs]);
    }
}