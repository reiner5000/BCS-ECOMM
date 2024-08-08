<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Composer;
use App\Models\Country;
use Intervention\Image\Facades\Image;
class ComposerController extends Controller
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
        $data = Composer::orderBy('name')->get();

        // halaman index
        return view('admin.composer.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // get data
        $country = Country::orderBy('country_name')->get();

        // halaman tambah
        return view('admin.composer.create',['country'=>$country]);
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
                'negara' => 'required',
                'foto' => 'required|file|image',
            ]);

            // simpan file ke directory
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '.jpg';
                $image = Image::make($file);
                if ($file->getClientOriginalExtension() === 'png') {
                    $image->encode('jpg', 60); 
                } else {
                    $image->save(public_path('uploads/composer/' . $filename), 60);
                }
                $image->save(public_path('uploads/composer/' . $filename));

                $fotoPath = 'uploads/composer/' . $filename;
            }

            // simpan db
            $composer = new Composer();
            $composer->name = $request->input('nama');
            $composer->profile_desc = $request->input('description');
            $composer->instagram = $request->input('instagram');
            $composer->twitter = $request->input('twitter');
            $composer->facebook = $request->input('facebook');
            $composer->asal_negara = $request->input('negara');
            $composer->photo_profile = $fotoPath ?? null;
            $composer->created_by = Auth::user()->id;
            $composer->save();

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
        $data = Composer::find($id);

        // get data
        $country = Country::orderBy('country_name')->get();

        // halaman edit
        return view('admin.composer.edit',['data'=>$data,'country'=>$country]);
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
                'negara' => 'required',
                'foto' => 'nullable|file|image',
            ]);

            // simpan db
            $composer = Composer::find($id);

            // simpan file ke directory
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '.jpg';
                $image = Image::make($file);
                if ($file->getClientOriginalExtension() === 'png') {
                    $image->encode('jpg', 60); 
                } else {
                    $image->save(public_path('uploads/composer/' . $filename), 60);
                }
                $image->save(public_path('uploads/composer/' . $filename));

                $fotoPath = 'uploads/composer/' . $filename;
                $composer->photo_profile = $fotoPath ?? null;
            }
            
            $composer->name = $request->input('nama');
            $composer->profile_desc = $request->input('description');
            $composer->instagram = $request->input('instagram');
            $composer->twitter = $request->input('twitter');
            $composer->facebook = $request->input('facebook');
            $composer->asal_negara = $request->input('negara');
            $composer->updated_by = Auth::user()->id;
            $composer->save();

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
            $composer = Composer::find($id);
            $composer->deleted_by = Auth::user()->id;
            $composer->save();
            $composer->delete();

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

        Excel::import(new ComposersImport, $request->file('excel'));

        return back()->with('success', 'Data berhasil diimpor!');
    }
}