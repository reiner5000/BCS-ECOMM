<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
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
        $data = User::orderBy('name')->get();

        // halaman index
        return view('admin.user.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // get data
        $role = Role::orderBy('name')->get();

        // halaman tambah
        return view('admin.user.create',['role'=>$role]);
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
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->whereNull('deleted_at'),
                ],
                'password' => 'required|string|min:8',
                'role' => 'required|exists:roles,id',
            ]);

            // simpan db
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->role_id = $request->input('role');
            $user->created_by = Auth::user()->id;
            $user->save();

            // jika sukses
            DB::commit();
            return redirect()->back()->with('success', 'Data saved successfully.');
        } catch (\Exception $e) {
            // jika gagal
            DB::rollback();
            return redirect()->back()->with('error', 'Data failed to save'.$e.'.');
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
        $data = User::find($id);
        
        // get role
        $role = Role::orderBy('name')->get();

        // halaman edit
        return view('admin.user.edit',['data'=>$data,'role'=>$role]);
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
                'name' => 'required',
                'email' => [
                    'required',
                    'email',
                ],
                'role' => 'required|exists:roles,id',
            ]);

            // simpan db
            $user = User::find($id);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            if($request->input('password') != ''){
                // jika password dirubah
                $user->password = Hash::make($request->input('password'));
            }
            $user->role_id = $request->input('role');
            $user->updated_by = Auth::user()->id;
            $user->save();

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
            $user = User::find($id);
            $user->deleted_by = Auth::user()->id;
            $user->save();
            $user->delete();

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
