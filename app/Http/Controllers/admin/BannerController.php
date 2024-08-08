<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Banner;
use Intervention\Image\Facades\Image;

class BannerController extends Controller
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
        $data = Banner::get();

        // halaman index
        return view('admin.banner.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // halaman tambah
        return view('admin.banner.create');
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
                'cover' => 'required|file|image',
            ]);

            // simpan file ke directory
            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $filename = time() . '.jpg';
                $image = Image::make($file);
                if ($file->getClientOriginalExtension() === 'png') {
                    $image->encode('jpg', 60); 
                } else {
                    $image->save(public_path('uploads/banner/' . $filename), 60);
                }
                $image->save(public_path('uploads/banner/' . $filename));

                $fotoPath = 'uploads/banner/' . $filename;
            }

            // simpan db
            $banner = new Banner();
            $banner->cover = $fotoPath ?? null;
            $banner->link = $request->input('link');
            $banner->created_by = Auth::user()->id;
            $banner->save();

            // jika sukses
            DB::commit();
            return redirect()->back()->with('success', 'Data saved successfully.');
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return redirect()->back()->with('error', 'Data failed to save.'.$e);
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
        $data = Banner::find($id);
        
        // halaman edit
        return view('admin.banner.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Validasi
            $request->validate([
                'cover' => 'nullable|file|image',
            ]);

            // simpan db
            $banner = Banner::find($id);

            // simpan file ke directory
            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $filename = time() . '.jpg';
                $image = Image::make($file);
                if ($file->getClientOriginalExtension() === 'png') {
                    $image->encode('jpg', 60); 
                } else {
                    $image->save(public_path('uploads/banner/' . $filename), 60);
                }
                $image->save(public_path('uploads/banner/' . $filename));

                $fotoPath = 'uploads/banner/' . $filename;
                $banner->cover = $fotoPath ?? null;
            }
            $banner->link = $request->input('link');
            $banner->updated_by = Auth::user()->id;
            $banner->save();

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
            $banner = Banner::find($id);
            $banner->deleted_by = Auth::user()->id;
            $banner->save();
            $banner->delete();

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
