<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get data
        $data1 = Order::where('payment_id',1)->orderBy('id','desc');
        
        if(isset($request->start)){
            $data1->whereBetween('date',array($request->start,$request->end));
        }else{
            $data1->whereBetween('date',array(date('Y-m-01'),date('Y-m-t')));
        }

        $data = $data1->get();

        // halaman index
        return view('admin.order.index',['data'=>$data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Order::find($id);
        
        return view('admin.order.detail',['data'=>$data]);
    }

    public function saveReceiptNumber(Request $request)
    {
        try {
            $order = Order::findOrFail($request->id);
            $order->no_resi = $request->receiptNumber;
            $order->status = 1;
            $order->updated_by = Auth::user()->id;
            $order->save();

            return Response::json(['message' => 'Receipt number saved successfully!'], 200);
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return Response::json(['message' => 'Failed to save receipt number.'], 500);
        }
    }

    public function saveComplete(Request $request)
    {
        try {
            $order = Order::findOrFail($request->id);
            $order->status = 2;
            $order->updated_by = Auth::user()->id;
            $order->save();

            return Response::json(['message' => 'Order completed successfully!'], 200);
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return Response::json(['message' => 'Failed to complete this order.'], 500);
        }
    }
    
}