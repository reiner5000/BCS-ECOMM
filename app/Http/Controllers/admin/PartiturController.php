<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Partitur;
use App\Models\PartiturDetail;
use App\Models\Composer;
use App\Models\Collection;
use App\Models\CategoryDetails;
use App\Imports\MultiSheetMusicImport;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PartiturController extends Controller
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
        $data = Partitur::orderBy('name')->get();

        // halaman index
        return view('admin.partitur.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // get composer
        $composers = Composer::get(); 

        // get collection
        $collections = Collection::get(); 

        // get category
        $category = CategoryDetails::with('category')->whereHas('category', function($query) {
            $query->where('type', 'Sheet Music');
        })->orderBy('name')->get();

        // halaman tambah
        return view('admin.partitur.create', compact('composers', 'collections', 'category'));
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
                'nama' => 'required|string|max:255',
                'description' => 'required|string',
                'composer' => 'required',
                'collection' => 'required',
                'file_images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // simpan db master
            $partitur = new Partitur();
            $partitur->name = $request->nama;
            $partitur->deskripsi = $request->description;
            $partitur->composer_id = $request->composer;
            $partitur->collection_id = $request->collection;
            // $partitur->file_image = $request->file_image;
            if ($request->hasFile('file_images')) {
                $fileNames = [];
                foreach ($request->file('file_images') as $file) {
                    // dd($request->file('file_images'));
                    // Menggunakan timestamp dan uniqid untuk membuat nama file unik
                    $filename = uniqid('partitur-').'-'.time();
                    $extension = $file->getClientOriginalExtension();
                    $fileNameToStore = $filename.'.'.$extension;
                    
                    // Menyimpan file
                    $file->move(public_path('uploads/partitur/images'), $fileNameToStore);
                    
                    // Mengumpulkan nama file untuk disimpan di database
                    // Menyimpan path lengkap ke dalam array jika perlu
                    $fileNames[] = 'uploads/partitur/images/' . $fileNameToStore;
                }
                
                // Menggabungkan semua nama file yang telah diubah menjadi string dan disimpan di kolom file_image
                $partitur->file_image = implode(',', $fileNames);
            }
            $partitur->created_by = Auth::user()->id;
            $partitur->save();

            // cek detail
            if ($request->has('nama_detail')) {
                foreach ($request->nama_detail as $key => $value) {
                    // simpan db detail
                    $detail = new PartiturDetail();
                    $detail->name = $value;
                    $detail->file_type = $request->file_type[$key];
                    $detail->harga = $request->harga[$key];
                    $detail->minimum_order = $request->minimum_order[$key];
                    $detail->deskripsi = $request->deskripsidet[$key];
                    $detail->partitur_id = $partitur->id;
                    $detail->created_by = Auth::user()->id;
                    $detail->category_detail_id = implode(",", $request->category);
                    $detail->preview_audio = $request->preview_audio[$key];
                    $detail->preview_video = $request->preview_video[$key];

                    // upload preview audio
                    // if ($request->hasFile('preview_audio.'.$key)) {
                    //     $file = $request->file('preview_audio')[$key];
                    //     $filename = time() . '.' . $file->getClientOriginalExtension();
                    //     $folderPath = 'uploads/partitur/audio/';
                    //     $file->move(public_path($folderPath), $filename);
                    //     $filePath = $folderPath . $filename;
                    //     $detail->preview_audio = $filePath;
                    // }

                    // // upload preview video
                    // if ($request->hasFile('preview_video.'.$key)) {
                    //     $file = $request->file('preview_video')[$key];
                    //     $filename = time() . '.' . $file->getClientOriginalExtension();
                    //     $folderPath = 'uploads/partitur/video/';
                    //     $file->move(public_path($folderPath), $filename);
                    //     $filePath = $folderPath . $filename;
                    //     $detail->preview_video = $filePath;
                    // }

                    // upload preview partitur
                    if ($request->hasFile('preview_partitur.'.$key)) {
                        $file = $request->file('preview_partitur')[$key];
                        $filename = time() . '.jpg';
                        $image = Image::make($file);
                        if ($file->getClientOriginalExtension() === 'png') {
                            $image->encode('jpg', 60); 
                        } else {
                            $image->save(public_path('uploads/partitur/image/' . $filename), 60);
                        }
                        $image->save(public_path('uploads/partitur/image/' . $filename));
        
                        $fotoPath = 'uploads/partitur/image/' . $filename;

                        $detail->preview_partitur = $fotoPath;
                    }

                    // upload partitur ori
                    if($request->hasFile('sheet_music.'.$key)){
                        $file = $request->file('sheet_music')[$key];
                        $filename = $file->getClientOriginalName();
                        $folderPath = 'uploads/partitur/ori/';
                        $file->move(public_path($folderPath), $filename);
                        $filePath = $folderPath . $filename;
                        $detail->partitur_ori = $filePath;
                    }

                    $detail->save();
                }
            }

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
        $data = Partitur::with('details')->find($id);

        // get composer
        $composers = Composer::get(); 

        // get collection
        $collections = Collection::get(); 

        // get category
        $category = CategoryDetails::with('category')->whereHas('category', function($query) {
            $query->where('type', 'Sheet Music');
        })->orderBy('name')->get();

        // halaman edit
        return view('admin.partitur.edit',['data'=>$data,'composers'=>$composers,'collections'=>$collections, 'category'=>$category]);
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
                'nama' => 'required|string|max:255',
                'description' => 'required|string',
                'composer' => 'required',
                'collection' => 'required',
                'file_images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // simpan db master
            $partitur = Partitur::find($id);
            $partitur->name = $request->nama;
            $partitur->deskripsi = $request->description;
            $partitur->composer_id = $request->composer;
            $partitur->collection_id = $request->collection;
            // Update file images
                if ($request->hasFile('file_images')) {
                    $existingImages = explode(',', $partitur->file_image);
                    $fileNames = [];
                    foreach ($request->file('file_images') as $file) {
                        $filename = uniqid('partitur-').'-'.time().'.'.$file->getClientOriginalExtension();
                        $file->move(public_path('uploads/partitur/images'), $filename);
                        $fileNames[] = 'uploads/partitur/images/' . $filename;
                    }
                    // Append new images to existing ones or replace them
                    // Append example: $allImages = array_merge($existingImages, $fileNames);
                    // Replace example: $allImages = $fileNames;
                    $allImages = array_merge($existingImages, $fileNames); // Choose based on your requirement
                    $partitur->file_image = implode(',', $allImages);
                }

            $partitur->updated_by = Auth::user()->id;
            $partitur->save();

            // delete db detail
            $id_delete = explode(',',$request->idDelete);
            for ($i=0; $i < count($id_delete); $i++) { 
                if($id_delete[$i] != ''){
                    $detail = PartiturDetail::find($id_delete[$i]);
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
                        $partitur->file_image = str_replace($imagePath.',', '', $partitur->file_image);
                        $partitur->save();
                        unlink($absolutePath);
                        // delete from database
                    }
                }
            }

            // cek detail
            if ($request->has('nama_detail')) {
                foreach ($request->nama_detail as $key => $value) {
                    if(isset($request->id_detail[$key])){
                        // edit
                        // simpan db detail
                        $detail = PartiturDetail::find($request->id_detail[$key]);
                        $detail->updated_by = Auth::user()->id;
                        
                        // jika audio kosong
                        // if($request->preview_audio_old[$key] == null){
                        //     $detail->preview_audio = null;
                        // }

                        // // jika video kosong
                        // if($request->preview_video_old[$key] == null){
                        //     $detail->preview_video = null;
                        // }

                        // jika partitur kosong
                        if($request->preview_partitur_old[$key] == null){
                            $detail->preview_partitur = null;
                        }

                        // jika partitur kosong
                        if($request->sheet_music_old[$key] == null){
                            $detail->partitur_ori = null;
                        }
                    }else{
                        // insert
                        // simpan db detail
                        $detail = new PartiturDetail();
                        $detail->partitur_id = $partitur->id;
                        $detail->created_by = Auth::user()->id;
                    }

                    $detail->name = $value;
                    $detail->file_type = $request->file_type[$key];
                    $detail->harga = $request->harga[$key];
                    $detail->deskripsi = $request->deskripsidet[$key];
                    $detail->minimum_order = $request->minimum_order[$key];
                    $detail->category_detail_id = implode(",", $request->category);
                    $detail->preview_audio = $request->preview_audio[$key];
                    $detail->preview_video = $request->preview_video[$key];

                    // // upload preview audio
                    // if ($request->hasFile('preview_audio.'.$key)) {
                    //     $file = $request->file('preview_audio')[$key];
                    //     $filename = time() . '.' . $file->getClientOriginalExtension();
                    //     $folderPath = 'uploads/partitur/audio/';
                    //     $file->move(public_path($folderPath), $filename);
                    //     $filePath = $folderPath . $filename;
                    //     $detail->preview_audio = $filePath;
                    // }

                    // // upload preview video
                    // if ($request->hasFile('preview_video.'.$key)) {
                    //     $file = $request->file('preview_video')[$key];
                    //     $filename = time() . '.' . $file->getClientOriginalExtension();
                    //     $folderPath = 'uploads/partitur/video/';
                    //     $file->move(public_path($folderPath), $filename);
                    //     $filePath = $folderPath . $filename;
                    //     $detail->preview_video = $filePath;
                    // }

                    // upload preview partitur
                    if ($request->hasFile('preview_partitur.'.$key)) {
                        $file = $request->file('preview_partitur')[$key];
                        $filename = time() . '.jpg';
                        $image = Image::make($file);
                        if ($file->getClientOriginalExtension() === 'png') {
                            $image->encode('jpg', 60); 
                        } else {
                            $image->save(public_path('uploads/partitur/image/' . $filename), 60);
                        }
                        $image->save(public_path('uploads/partitur/image/' . $filename));

                        $fotoPath = 'uploads/partitur/image/' . $filename;

                        $detail->preview_partitur = $fotoPath;
                    }

                    // upload partitur ori
                    if($request->hasFile('sheet_music.'.$key)){
                        $file = $request->file('sheet_music')[$key];
                        $filename = $file->getClientOriginalName();
                        $folderPath = 'uploads/partitur/ori/';
                        $file->move(public_path($folderPath), $filename);
                        $filePath = $folderPath . $filename;
                        $detail->partitur_ori = $filePath;
                    }

                    $detail->save();
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
            // hapus dari db master
            $partitur = Partitur::find($id);
            $partitur->deleted_by = Auth::user()->id;
            $partitur->save();
            $partitur->delete();

            // hapus dari db detail
            $partiturDetail = PartiturDetail::where('partitur_id',$id)->get();
            foreach($partiturDetail as $d){
                $detail = PartiturDetail::find($d->id);
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
            
            Excel::import(new MultiSheetMusicImport(), $realPath);
            
            Storage::delete($path);
            return back()->with('success', 'All good!');
        } else {
            return back()->withError('No file was uploaded.');
        }
    }
}