<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PharIo\Manifest\Email;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function authLogin(Request $request)
    {
        $request->validate([
            "email" => 'required|email:dns',
            'password' => 'required',
        ]);

    $user = $request->only(['email', 'password']);

    if (Auth::attempt($user)) {
        return redirect('/dashboard');
    } else {
        return redirect()->back()->with('failed','login gagal! silahkan coba lagi');
    }
    }

    public function logout()
    {
        //menghapus/menghilangkan data session login
        Auth::logout();
        return redirect()->route('login');
    }

    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::orderBy('name', 'ASC')->simplePaginate(5);
        return view('user.userIndex', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.createUser');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required',
            'role' => 'required',
        ]);

        $emailPrefix = substr($request->email, 0, 3);
        $namePrefix = substr($request->name, 0, 3);

        // Menggabungkan kedua prefix menjadi password
        $generatedPassword = $emailPrefix . $namePrefix;

    // Mengenkripsi password dengan bcrypt
    $hashedPassword = bcrypt($generatedPassword);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'password' => $hashedPassword,
        ]);

        return redirect()->back()->with('succes','Berhasil Menambahkan data!');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $medicine = User::find($id);
        return response()->json($medicine, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);

        return view('user.editUser', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        

        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'role' => 'required',
            'password' => '',
        ]);

        $password = bcrypt($request->password) ;

        User::where('id', $id)->update
        ([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $password,
        ]);

      
        return redirect()->route('user.home')->with('success', 'Berhasil mengubah data user!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->delete();
        return redirect()->back()->with('deleted', 'Berhasil menghapus data!');
    }
}