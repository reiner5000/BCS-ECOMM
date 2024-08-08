<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Customer;
use App\Models\Shipment;
use App\Models\Choir;

class CustomerController extends Controller
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
    public function index()
    {
        // get data
        $data = Customer::orderBy('name')->get();

        // halaman index
        return view('admin.customer.index',['data'=>$data]);
    }

    public function alamat($id)
    {
        // get data
        $data = Shipment::where('customer_id',$id)->orderBy('is_default','desc')->orderBy('nama_penerima')->get();

        // get customer
        $customer = Customer::find($id);

        // halaman alamat customer
        return view('admin.customer.alamat',['data'=>$data,'customer'=>$customer]);
    }

    public function choir($id)
    {
        // get data
        $data = Choir::where('customer_id',$id)->orderBy('is_default','desc')->orderBy('name')->get();

        // get customer
        $customer = Customer::find($id);

        // halaman choir customer
        return view('admin.customer.choir',['data'=>$data,'customer'=>$customer]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // halaman index
        return redirect()->route('customer.index');
    }
}
