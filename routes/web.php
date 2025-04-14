<?php

use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Contract\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Firebase Testing Routes (Optional)
|--------------------------------------------------------------------------
*/
Route::get('/firebase-test', function (Auth $auth) {
    $users = $auth->listUsers();
    return response()->json($users);
});

Route::get('/test-auth', function (FirebaseAuth $auth) {
    $users = [];
    foreach ($auth->listUsers() as $user) {
        $users[] = $user->email;
    }
    dd($users);
});

Route::get('/check-token', function () {
    return session('firebase_id_token');
});
Route::get('/check-session', function () {
    return session('user_data');
});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| User & Guest Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [UserController::class, 'guestHome'])->name('guest.home');
Route::get('/home', [UserController::class, 'userHome'])->name('home');
Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('user.profile.edit');
Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
Route::get('/order-history', [UserController::class, 'orderHistory'])->name('user.orderHistory');
Route::get('/address', [UserController::class, 'address'])->name('user.address');



/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| Admin Routes (Category & Product Management)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {

    // Category Routes
    Route::get('/add-category', [CategoryController::class, 'addCategory'])->name('admin.add-category');
    Route::post('/store-category', [CategoryController::class, 'storeCategory'])->name('admin.store-category');
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{id}/update', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // Product Routes
    Route::get('/add-product', [ProductController::class, 'create'])->name('admin.add-product');
    Route::post('/store-product', [ProductController::class, 'store'])->name('admin.store-product');
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}/update', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/search', [ProductController::class, 'search'])->name('search');


});
// Route untuk featured products
Route::get('/featured-products', [ProductController::class, 'featuredProducts'])->name('products.featured');
/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::get('/profile', [ProfileController::class, 'profile'])->name('user.profile');
Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('user.edit-profile');
Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('user.update-profile');