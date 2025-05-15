<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller; // Make sure to import the Controller class

class SiswaController extends Controller
{
    // Display a listing of the users
    public function index()
    {
        $users = User::all(); // Fetch all users from the database
        return view('users.index', compact('users')); // Return the view with users data
    }

    // Show the form for creating a new user
    public function create()
    {
        return view('users.create'); // Return the user creation view
    }

    // Store a newly created user in the database
    public function store(Request $request)
    {
        $request->validate([ // Validate the incoming data
            'name' => 'required',
            'email' => 'nullable|email|unique:users,email',
            'nis' => 'nullable|unique:users,nis',
            'password' => 'required|min:6',
        ]);

        // Create a new user in the database
        User::create([
            'name' => $request->name,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'role' => $request->role ?? 'siswa', // Default role is 'siswa'
            'tahun_ajaran' => $request->tahun_ajaran,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encrypt the password
        ]);

        // Redirect back to the users index page with a success message
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    // Show the form for editing a user
    public function edit(User $user)
    {
        return view('users.edit', compact('user')); // Return the user edit view with the user's data
    }

    // Update the specified user in the database
    public function update(Request $request, User $user)
    {
        $request->validate([ // Validate the incoming data
            'name' => 'required',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'nis' => 'nullable|unique:users,nis,' . $user->id,
        ]);

        // Update the user's data in the database
        $user->update([
            'name' => $request->name,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'role' => $request->role ?? 'siswa', // Default role is 'siswa'
            'tahun_ajaran' => $request->tahun_ajaran,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password, // Only update password if provided
        ]);

        // Redirect back to the users index page with a success message
        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    // Remove the specified user from the database
    public function destroy(User $user)
    {
        $user->delete(); // Delete the user
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.'); // Redirect back with a success message
    }
}
