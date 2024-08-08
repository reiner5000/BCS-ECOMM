<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Voucher;

class VoucherController extends Controller
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
        $data = Voucher::orderBy('name')->get();

        // halaman index
        return view('admin.voucher.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // halaman tambah
        return view('admin.voucher.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validasi
            $request->validate([
                'name' => 'required',
                'potongan' => 'required',
            ]);

            // simpan db
            $voucher = new Voucher();
            $voucher->name = $request->input('name');
            $voucher->potongan = $request->input('potongan');
            $voucher->created_by = Auth::user()->id;
            $voucher->save();

            // jika sukses
            DB::commit();
            return redirect()->back()->with('success', 'Data saved successfully.');
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return redirect()->back()->with('error', 'Data failed to save.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // get data
        $data = voucher::find($id);

        // halaman edit
        return view('admin.voucher.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Validasi
            $request->validate([
                'name' => 'required',
                'potongan' => 'required',
            ]);

            // simpan db
            $voucher = Voucher::find($id);
            $voucher->name = $request->input('name');
            $voucher->potongan = $request->input('potongan');
            $voucher->updated_by = Auth::user()->id;
            $voucher->save();

            // jika sukses
            DB::commit();
            return redirect()->back()->with('success', 'Data saved successfully.');
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return redirect()->back()->with('error', 'Data failed to save.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            // hapus dari db
            $voucher = Voucher::find($id);
            $voucher->deleted_by = Auth::user()->id;
            $voucher->save();
            $voucher->delete();

            // jika sukses
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
