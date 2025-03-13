<?php

use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Contract\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Kreait\Firebase\Auth as FirebaseAuth;

/*
|--------------------------------------------------------------------------
| Firebase Testing (Optional)
|--------------------------------------------------------------------------
*/
Route::get('/firebase-test', function (Auth $auth) {
    $users = $auth->listUsers(); // List semua user Firebase
    return response()->json($users);
});

Route::get('/test-auth', function (FirebaseAuth $auth) {
    $users = [];

    foreach ($auth->listUsers() as $user) {
        $users[] = $user->email; // contoh display email user
    }

    dd($users);
});
Route::get('/check-token', function () {
    return session('firebase_id_token');
});



/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
// Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Forgot Password
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// Logout (Guna GET senang test je dulu. Nanti boleh tukar POST)
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/home', function () {
    return view('dashboard.home');
})->name('home');


Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

// Admin Dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// Other routes examples
Route::get('/admin/categories', function () {
    return 'All Categories Page';
})->name('admin.categories');

Route::get('/admin/products', function () {
    return 'All Products Page';
})->name('admin.products');

Route::get('/admin/add-category', function () {
    return 'Add Categories Page';
})->name('admin.add-category');

Route::get('/admin/add-product', function () {
    return 'Add Products Page';
})->name('admin.add-product');

// Logout Route (existing in your controller)
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('/', function () {
    return view('guest.guest');
})->name('guest.home');

