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

}
