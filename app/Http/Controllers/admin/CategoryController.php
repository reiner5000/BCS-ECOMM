<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Category;
use App\Models\CategoryDetails;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MultiCategoryImport;
use App\Exports\CategoryTemplateExport;

class CategoryController extends Controller
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
        $data = Category::orderBy('name')->get();

        // halaman index
        return view('admin.category.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // halaman tambah
        return view('admin.category.create');
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
            ]);

            // simpan db
            $category = new Category();
            $category->name = $request->input('nama');
            $category->type = $request->input('type');
            $category->created_by = Auth::user()->id;
            $category->save();

            foreach ($request->nama_detail as $key => $detail) {
                if ($request['nama_detail'][$key] !== null) {
                    // simpan detail
                    $detail = new CategoryDetails();
                    $detail->name = $request['nama_detail'][$key];
                    $detail->category_id = $category->id;
                    $detail->created_by = Auth::user()->id;
                    $detail->save();
                }
            }

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
        $data = Category::find($id);
        
        // get data detail
        $detail = CategoryDetails::where('category_id',$id)->get();

        // halaman edit
        return view('admin.category.edit',['data'=>$data,'detail'=>$detail]);
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
            ]);

            // simpan db
            $category = category::find($id);
            $category->name = $request->input('nama');
            $category->type = $request->input('type');
            $category->updated_by = Auth::user()->id;
            $category->save();

            // Delete detail
            $id_delete = explode(',',$request->id_delete);
            for ($i=0; $i < count($id_delete); $i++) { 
                if($id_delete[$i] != ''){
                    // delete
                    $details = CategoryDetails::find($id_delete[$i]);
                    $details->deleted_by = Auth::user()->id;
                    $details->save();
                    $details->delete();
                }
            }

            foreach ($request->nama_detail as $key => $detail) {
                if ($request['nama_detail'][$key] !== null) {
                    // simpan detail
                    if(isset($request['id_detail'][$key])){
                        $detail = CategoryDetails::find($request['id_detail'][$key]);
                        $detail->name = $request['nama_detail'][$key];
                        $detail->updated_by = Auth::user()->id;
                        $detail->save();
                    }else{
                        $detail = new CategoryDetails();
                        $detail->name = $request['nama_detail'][$key];
                        $detail->category_id = $category->id;
                        $detail->created_by = Auth::user()->id;
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
            $category = Category::find($id);
            $category->deleted_by = Auth::user()->id;
            $category->save();
            $category->delete();

            $detail = CategoryDetails::where('category_id',$id)->get();
            foreach($detail as $d){
                $catdet = CategoryDetails::find($d->id);
                $catdet->deleted_by = Auth::user()->id;
                $catdet->save();
                $catdet->delete();
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
            
            Excel::import(new MultiCategoryImport(), $realPath);
            
            Storage::delete($path);
            return back()->with('success', 'All good!');
        } else {
            return back()->withError('No file was uploaded.');
        }
    }

}