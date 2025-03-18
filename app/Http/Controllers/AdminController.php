<?php

namespace App\Http\Controllers;

use App\Helpers\FirebaseHelper;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class AdminController extends Controller
{
    // Ini untuk paparkan halaman dashboard admin
    public function index()
    {
        return view('admin.dashboard');
    }
    public function editProfile()
    {
        $user = session('user_data');
        return view('admin.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        // Contoh validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        // Logic update - contoh kalau guna Firestore atau DB
        // Kalau guna Firestore, kita update ikut ID user
        // Firestore::collection('admins')->document($id)->update([...]);

        // Untuk demo, assume berjaya update
        return redirect()->route('admin.edit-profile')->with('success', 'Profile updated!');
    }
   
}
