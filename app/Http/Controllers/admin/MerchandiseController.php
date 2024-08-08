<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Merchandise;
use App\Models\MerchandiseDetail;
use App\Models\CategoryDetails;
use App\Imports\MultiMerchandiseImport;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MerchandiseController extends Controller
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
        $data = Merchandise::orderBy('name')->get();

        // halaman index
        return view('admin.merchandise.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // get category
        $category = CategoryDetails::with('category')->whereHas('category', function($query) {
            $query->where('type', 'Merchandise');
        })->orderBy('name')->get();

        // halaman tambah
        return view('admin.merchandise.create',['category'=>$category]);
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
                'nama' => 'required',
                'description' => 'required',
                'harga' => 'required',
                'stok' => 'required',
                'category' => 'required',
            ]);

            // simpan db
            $merchandise = new Merchandise();
            $merchandise->name = $request->input('nama');
            $merchandise->deskripsi = $request->input('description');
            $merchandise->harga = $request->input('harga');
            $merchandise->stok = $request->input('stok');
            $merchandise->category_detail_id = implode(",", $request->input('category'));
            if ($request->hasFile('file_images')) {
                $fileNames = [];
                foreach ($request->file('file_images') as $file) {
                    // dd($request->file('file_images'));
                    // Menggunakan timestamp dan uniqid untuk membuat nama file unik
                    $filename = uniqid('merchandise-').'-'.time();
                    $extension = $file->getClientOriginalExtension();
                    $fileNameToStore = $filename.'.'.$extension;
                    
                    // Menyimpan file
                    $file->move(public_path('uploads/merchandise'), $fileNameToStore);
                    
                    // Mengumpulkan nama file untuk disimpan di database
                    // Menyimpan path lengkap ke dalam array jika perlu
                    $fileNames[] = 'uploads/merchandise/' . $fileNameToStore;
                }
                
                // Menggabungkan semua nama file yang telah diubah menjadi string dan disimpan di kolom file_image
                $merchandise->photo = implode(',', $fileNames);
            }
            $merchandise->created_by = Auth::user()->id;
            $merchandise->save();

            // cek detail
            if ($request->has('size_detail')) {
                foreach ($request->size_detail as $key => $value) {
                    // simpan db detail
                    if($value != ''){
                        $detail = new MerchandiseDetail();
                        $detail->size = $value;
                        $detail->color = '';
                        $detail->created_by = Auth::user()->id;
                        $detail->merchandise_id = $merchandise->id;
                        $detail->save();
                    }
                }
                foreach ($request->color_detail as $key => $value) {
                    // simpan db detail
                    if($value != ''){
                        $detail = new MerchandiseDetail();
                        $detail->size = '';
                        $detail->color = $value;
                        $detail->created_by = Auth::user()->id;
                        $detail->merchandise_id = $merchandise->id;
                        $detail->save();
                    }
                }
            }

            // jika sukses
            DB::commit();
            return redirect()->back()->with('success', 'Data saved successfully.');
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return redirect()->back()->with('error', 'Data failed to save.'.$e->getMessage());
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
        $data = Merchandise::find($id);

        // get category
        $category = CategoryDetails::with('category')->whereHas('category', function($query) {
            $query->where('type', 'Merchandise');
        })->orderBy('name')->get();

        // halaman edit
        return view('admin.merchandise.edit',['data'=>$data,'category'=>$category]);
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
                'nama' => 'required',
                'description' => 'required',
                'harga' => 'required',
                'stok' => 'required',
                'category' => 'required',
            ]);

            // simpan db
            $merchandise = Merchandise::find($id);
            // simpan file ke directory
            if ($request->hasFile('file_images')) {
                $existingImages = explode(',', $merchandise->photo);
                $fileNames = [];
                foreach ($request->file('file_images') as $file) {
                    $filename = uniqid('merchandise-').'-'.time().'.'.$file->getClientOriginalExtension();
                    $file->move(public_path('uploads/merchandise'), $filename);
                    $fileNames[] = 'uploads/merchandise/' . $filename;
                }
                // Append new images to existing ones or replace them
                // Append example: $allImages = array_merge($existingImages, $fileNames);
                // Replace example: $allImages = $fileNames;
                $allImages = array_merge($existingImages, $fileNames); // Choose based on your requirement
                $merchandise->photo = implode(',', $allImages);
            }

            $merchandise->name = $request->input('nama');
            $merchandise->deskripsi = $request->input('description');
            $merchandise->harga = $request->input('harga');
            $merchandise->stok = $request->input('stok');
            $merchandise->category_detail_id = implode(",", $request->input('category'));
            $merchandise->updated_by = Auth::user()->id;
            $merchandise->save();

            // delete db detail
            $id_delete = explode(',',$request->idDelete);
            for ($i=0; $i < count($id_delete); $i++) { 
                if($id_delete[$i] != ''){
                    $detail = MerchandiseDetail::find($id_delete[$i]);
                    $detail->deleted_by = Auth::user()->id;
                    $detail->save();
                    $detail->delete();
                }
            }

            if ($request->has('imagesToDelete') && $request->imagesToDelete !== null) {
                $imagesToDelete = explode(',', $request->imagesToDelete);
                foreach ($imagesToDelete as $imagePath) {
                    // remote public/ from $imagePath
                    $imagePath = str_replace('public/', '', $imagePath);
                    // dd($imagePath);
                    // Hapus file gambar dari penyimpanan
                    $absolutePath = public_path($imagePath);
                    if (file_exists($absolutePath)) {
                        $merchandise->photo = str_replace($imagePath.',', '', $merchandise->photo);
                        $merchandise->save();
                        unlink($absolutePath);
                        // delete from database
                    }
                }
            }

            // cek detail
            if ($request->has('size_detail')) {
                foreach ($request->size_detail as $key => $value) {
                    if($value != ''){
                        if(isset($request->id_detail[$key])){
                            // edit
                            // simpan db detail
                            $detail = MerchandiseDetail::find($request->id_detail[$key]);
                            $detail->size = $value;
                            $detail->updated_by = Auth::user()->id;
                            $detail->save();
                        }else{
                            // insert
                            // simpan db detail
                            $detail = new MerchandiseDetail();
                            $detail->merchandise_id = $merchandise->id;
                            $detail->size = $value;
                            $detail->color = '';
                            $detail->created_by = Auth::user()->id;
                            $detail->save();
                        }
                    }
                }
            }

            if ($request->has('color_detail')) {
                foreach ($request->color_detail as $key => $value) {
                    if($value != ''){
                        if(isset($request->id_detail2[$key])){
                            // edit
                            // simpan db detail
                            $detail = MerchandiseDetail::find($request->id_detail2[$key]);
                            $detail->color = $value;
                            $detail->updated_by = Auth::user()->id;
                            $detail->save();
                        }else{
                            // insert
                            // simpan db detail
                            $detail = new MerchandiseDetail();
                            $detail->merchandise_id = $merchandise->id;
                            $detail->color = $value;
                            $detail->size = '';
                            $detail->created_by = Auth::user()->id;
                            $detail->save();
                        }
                    }
                }
            }
            
            // jika sukses
            DB::commit();
            return redirect()->back()->with('success', 'Data saved successfully.');
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return redirect()->back()->with('error', 'Data failed to save.'.$e->getMessage());
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
            $merchandise = Merchandise::find($id);
            $merchandise->deleted_by = Auth::user()->id;
            $merchandise->save();
            $merchandise->delete();

            // hapus dari db detail
            $merchandiseDetail = MerchandiseDetail::where('merchandise_id',$id)->get();
            foreach($merchandiseDetail as $d){
                $detail = MerchandiseDetail::find($d->id);
                $detail->deleted_by = Auth::user()->id;
                $detail->save();
                $detail->delete();
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

    public function import(Request $request)
    {
        $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls',
        ]);

        if ($request->hasFile('excel')) {
            $path = $request->file('excel')->store('temp');
            $realPath = Storage::path($path);
            
            Excel::import(new MultiMerchandiseImport(), $realPath);
            
            Storage::delete($path);
            return back()->with('success', 'All good!');
        } else {
            return back()->withError('No file was uploaded.');
        }
    }
}