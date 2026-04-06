<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $users = User::when($search, function ($users) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $users = $users->where('name', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom name
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('users.index', compact('users'))
            ->with('i', ($page - 1) * $entries); // mengirim $users ke view users.index, dan i sebagai nomor urut

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string',
            'password_confirmations' => 'required|same:password',
            'roles' => 'required|string',
        ]);

        try {
            // untuk insert user ke database
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'roles' => $request->roles,
                'password' => Hash::make($request->password),
            ]);

            $user->save();
            // mengalihkan ke halaman users.index dengan pesan sukses
            return redirect()->route('users.index')
                ->with('success', 'User ' . $user->name . ' has been added successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'nullable|string',
            'email' => 'required|string|unique:users,email,' . $id,
            'password_confirmations' => 'nullable|same:password',
            'roles' => 'required',
        ]);

        try {
            // untuk insert user ke database
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->roles = $request->roles;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }


            $user->save();
            // mengalihkan ke halaman users.index dengan pesan sukses
            return redirect()->route('users.index')
                ->with('success', 'User ' . $user->name . ' has been added successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user) {
            $user->delete();
            return redirect()->route('users.index')
                ->with('succsess', 'User ' . $user->name . ' has been deleted succsesfully!');
        } else {
            return back()->with('error', 'user not found!');
        }
    }
}
