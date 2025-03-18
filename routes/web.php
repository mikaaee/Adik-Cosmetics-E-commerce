<?php

use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Contract\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;

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


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// 🔒 Semua route dalam "admin" prefix
Route::prefix('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard (Optional)
    |--------------------------------------------------------------------------
    */
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Category Management Routes
    |--------------------------------------------------------------------------
    */

    // ➕ Add Category
    Route::get('/add-category', [CategoryController::class, 'addCategory'])->name('admin.add-category');
    Route::post('/store-category', [CategoryController::class, 'storeCategory'])->name('admin.store-category');

    // 📋 All Categories Page
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');

    // ✏️ Edit & Update Category
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{id}/update', [CategoryController::class, 'update'])->name('admin.categories.update');

    // 🗑️ Delete Category
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    /*
    |--------------------------------------------------------------------------
    | Product Management Routes
    |--------------------------------------------------------------------------
    */

    // ➕ Add Product
    Route::get('/add-product', [ProductController::class, 'create'])->name('admin.add-product');
    Route::post('/store-product', [ProductController::class, 'storeProduct'])->name('admin.store-product');

    // 📋 All Products Page
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products');

    // ✏️ Edit & Update Product
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}/update', [ProductController::class, 'update'])->name('admin.products.update');

    // 🗑️ Delete Product
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
});


// Store product ke Firestore
Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.store-product');
// User Home
Route::get('/home', [ProductController::class, 'userHome'])->name('user.home');

// Guest Page
Route::get('/', [ProductController::class, 'guestHome'])->name('guest.home');



// Admin Edit Profile Page
Route::get('/admin/edit-profile', [AdminController::class, 'editProfile'])->name('admin.edit-profile');

// Untuk handle update form kalau ada
Route::post('/admin/update-profile', [AdminController::class, 'updateProfile'])->name('admin.update-profile');

// Logout Route (existing in your controller)
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('/', function () {
    return view('guest.guest');
})->name('guest.home');

