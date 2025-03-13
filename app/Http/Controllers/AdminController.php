<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Ini untuk paparkan halaman dashboard admin
    public function index()
    {
        return view('admin.dashboard');
    }
}
