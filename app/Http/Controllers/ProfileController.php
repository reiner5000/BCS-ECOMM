<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Choir;
use App\Models\Shipment;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\Cart;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get choir
        $choir = Choir::where('customer_id',Auth::guard('customer')->user()->id)->where('is_default',1)->get();
        $shipment = Shipment::where('customer_id',Auth::guard('customer')->user()->id)->where('is_default',1)->get();
        $order = Order::where('customer_id',Auth::guard('customer')->user()->id)->orderBy('id','desc')->get();

        return view('profile.index',['choir'=>$choir,'shipment'=>$shipment,'order'=>$order]);
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function address()
    {
        $data = Shipment::where('customer_id',Auth::guard('customer')->user()->id)->orderBy('is_default', 'desc')->get();
        // dd($data);
        return view('profile.address',['shipment'=>$data]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function choir()
    {
        // get data
        $data = Choir::where('customer_id',Auth::guard('customer')->user()->id)->orderBy('is_default','desc')->orderBy('created_at')->get();

        return view('profile.choir',['data'=>$data]);
    }

     /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function order($id)
    {
        // get data
        $data = Order::where('customer_id',Auth::guard('customer')->user()->id)->where('id',$id)->first();

        if($data){
            return view('profile.order',['data'=>$data]);
        }else{
            return redirect()->back();
        }
    }

    public function invoice($id)
    {
        $data = Order::where('customer_id',Auth::guard('customer')->user()->id)->where('id',$id)->first();
        return view('layouts.invoice',['data'=>$data]);
    }


    public function profileSave(Request $request)
    {
        DB::beginTransaction();
          
        try {
            // simpan db
            $cust = Customer::find($request->input('customer-id'));
            $cust->name = $request->input('nama-customer');
            $cust->email = $request->input('email-customer');
            $cust->phone_number = $request->input('phone-customer');
            if($request->input('gender-customer')){
                $cust->gender = $request->input('gender-customer');
            }
            $cust->updated_by = Auth::guard('customer')->user()->id;
            $cust->save();

            // jika sukses
            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return redirect()->back()->with('error', 'Data gagal disimpan.'.$e);
        }
    }

    public function photoave(Request $request)
    {
        DB::beginTransaction();

        try {
            // simpan db
            $cust = Customer::find(Auth::guard('customer')->user()->id);
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '.jpg';
                $image = Image::make($file);
                if ($file->getClientOriginalExtension() === 'png') {
                    $image->encode('jpg', 60); 
                } else {
                    $image->save(public_path('uploads/customer/' . $filename), 60);
                }
                $image->save(public_path('uploads/customer/' . $filename));

                $fotoPath = 'uploads/customer/' . $filename;

                $cust->photo_profile = $fotoPath;
            }
            $cust->save();

            // jika sukses
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return response()->json(['success' => false]);
        }
    }

    public function choirSave(Request $request)
    {
        DB::beginTransaction();
          
        try {
            $count = Choir::where('customer_id',Auth::guard('customer')->user()->id)->count();

            // simpan db
            $choir = new Choir();
            $choir->name = $request->input('nama-choir');
            $choir->address = $request->input('alamat-choir');
            $choir->conductor = $request->input('nama-konduktor');
            if($count == 0){
                $choir->is_default = 1;
            }else{
                $choir->is_default = 0;
            }
            $choir->customer_id = Auth::guard('customer')->user()->id;
            $choir->created_by = Auth::guard('customer')->user()->id;
            $choir->save();

            // jika sukses
            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return redirect()->back()->with('error', 'Data gagal disimpan.'.$e);
        }
    }

    public function choirChange(Request $request, $id){
        DB::beginTransaction();

        try {
            $choir = Choir::where('customer_id',Auth::guard('customer')->user()->id);
            $choir->update(['is_default' => 0]);

            // update db
            $choir = Choir::find($id);
            $choir->is_default = 1;
            $choir->updated_by = Auth::guard('customer')->user()->id;
            $choir->save();

            // update cart
            $cart = Cart::where('customer_id',Auth::guard('customer')->user()->id)->get();
            foreach($cart as $c){
                $detail = Cart::find($c->id);
                $detail->choir_id = $choir->id;
                $detail->save();
            }

            // jika sukses
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function choirDelete(Request $request, $id){
        DB::beginTransaction();

        try {
            // delete db
            $choir = Choir::find($id);
            $choir->deleted_by = Auth::guard('customer')->user()->id;
            $choir->save();
            $choir->delete();

            // jika sukses
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function choirUpdate(Request $request){
        DB::beginTransaction();

        try {
            // update db
            $choir = Choir::find($request->input('choir-id'));
            $choir->name = $request->input('nama-choir');
            $choir->address = $request->input('alamat-choir');
            $choir->conductor = $request->input('nama-konduktor');
            $choir->updated_by = Auth::guard('customer')->user()->id;
            $choir->save();

            // jika sukses
            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return redirect()->back()->with('error', 'Data gagal disimpan.'.$e);
        }
    }

    public function shipmentSave(Request $request)
    {
        DB::beginTransaction();
          
        try {
            $count = Shipment::where('customer_id',Auth::guard('customer')->user()->id)->count();

            // simpan db
            $shipment = new Shipment();
            $shipment->nama_penerima = $request->input('nama-penerima');
            $shipment->phone_number = $request->input('nomor-penerima');
            $shipment->negara = $request->input('negara');
            if($request->input('negara') == 'Indonesia'){
                $shipment->kota = $request->input('kota');
                $shipment->provinsi = $request->input('provinsi');
            }else{
                $shipment->kota = $request->input('kota-input');
                $shipment->provinsi = $request->input('provinsi-input');
            }
            $shipment->kecamatan = $request->input('kecamatan');
            $shipment->kode_pos = $request->input('kode_pos');
            $shipment->informasi_tambahan = $request->input('alamat');
            $shipment->detail_informasi_tambahan = $request->input('informasi-penerima');
            if($request->input('from') == 'checkout'){
                $shipment->is_default = 1;
            }else{
                if($count == 0){
                    $shipment->is_default = 1;
                }else{
                    $shipment->is_default = 0;
                }
            }
            $shipment->customer_id = Auth::guard('customer')->user()->id;
            $shipment->created_by = Auth::guard('customer')->user()->id;
            $shipment->save();

            // jika sukses
            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return redirect()->back()->with('error', 'Data gagal disimpan.'.$e);
        }
    }

    public function shipmentChange(Request $request, $id){
        DB::beginTransaction();

        try {
            $shipment = Shipment::where('customer_id',Auth::guard('customer')->user()->id);
            $shipment->update(['is_default' => 0]);

            // update db
            $shipment = Shipment::find($id);
            $shipment->is_default = 1;
            $shipment->updated_by = Auth::guard('customer')->user()->id;
            $shipment->save();

            // jika sukses
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function shipmentDelete(Request $request, $id){
        DB::beginTransaction();

        try {
            // delete db
            $shipment = Shipment::find($id);
            $shipment->deleted_by = Auth::guard('customer')->user()->id;
            $shipment->save();
            $shipment->delete();

            // jika sukses
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function shipmentUpdate(Request $request){
        // dd($request);
        DB::beginTransaction();

        try {
            // update db
            $shipment = Shipment::find($request->input('alamat-id'));
            $shipment->nama_penerima = $request->input('nama-penerima');
            $shipment->phone_number = $request->input('nomor-penerima');
            $shipment->negara = $request->input('negara');
            if($request->input('negara') == 'Indonesia'){
                $shipment->kota = $request->input('kota');
                $shipment->provinsi = $request->input('provinsi');
            }else{
                $shipment->kota = $request->input('kota-input');
                $shipment->provinsi = $request->input('provinsi-input');
            }
            $shipment->kecamatan = $request->input('kecamatan');
            $shipment->kode_pos = $request->input('kode_pos');
            $shipment->informasi_tambahan = $request->input('alamat');
            $shipment->detail_informasi_tambahan = $request->input('informasi-penerima');
            $shipment->updated_by = Auth::guard('customer')->user()->id;
            $shipment->save();

            // jika sukses
            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return redirect()->back()->with('error', 'Data gagal disimpan.'.$e);
        }
    }

    public function getProvincesByCountry($country_id)
    {
        $country = Country::where('country_name', $country_id)->first();
        $provinces = Province::where('country_id', $country->id)
                ->select('province')
                ->distinct()
                ->orderBy('province', 'asc')
                ->get();

        return response()->json($provinces);
    }

    public function getCitiesByProvinces($provinces_id)
    {
        $provinces = Province::where('province', $provinces_id)->first();
        $city = City::where('province_id', $provinces->id)
                ->select('city_name')
                ->distinct()
                ->orderBy('city_name', 'asc')
                ->get();

        return response()->json($city);
    }
}