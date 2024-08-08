<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Collection;
use Intervention\Image\Facades\Image;

class CollectionController extends Controller
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
        $data = Collection::orderBy('name')->get();

        // halaman index
        return view('admin.collection.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // halaman tambah
        return view('admin.collection.create');
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
                    $image->save(public_path('uploads/collection/' . $filename), 60);
                }
                $image->save(public_path('uploads/collection/' . $filename));

                $fotoPath = 'uploads/collection/' . $filename;
            }

            // simpan db
            $collection = new Collection();
            $collection->name = $request->input('nama');
            $collection->short_description = $request->input('description');
            $collection->cover = $fotoPath ?? null;
            $collection->created_by = Auth::user()->id;
            $collection->save();

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
        $data = Collection::find($id);
        
        // halaman edit
        return view('admin.collection.edit',['data'=>$data]);
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
                'cover' => 'nullable|file|image',
            ]);

            // simpan db
            $collection = Collection::find($id);

            // simpan file ke directory
            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $filename = time() . '.jpg';
                $image = Image::make($file);
                if ($file->getClientOriginalExtension() === 'png') {
                    $image->encode('jpg', 60); 
                } else {
                    $image->save(public_path('uploads/collection/' . $filename), 60);
                }
                $image->save(public_path('uploads/collection/' . $filename));

                $fotoPath = 'uploads/collection/' . $filename;
                $collection->cover = $fotoPath ?? null;
            }
            
            $collection->name = $request->input('nama');
            $collection->short_description = $request->input('description');
            $collection->updated_by = Auth::user()->id;
            $collection->save();

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
            $collection = Collection::find($id);
            $collection->deleted_by = Auth::user()->id;
            $collection->save();
            $collection->delete();

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

        Excel::import(new CollectionImport, $request->file('excel'));

        return back()->with('success', 'Data berhasil diimpor!');
    }
}