<?php

use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Contract\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;

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
Route::get('/', function () {
    return session()->has('user_data') ? redirect()->route('home') : redirect()->route('guest.home');
});

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

// Only one route for both guest and logged-in users
Route::get('/category/{categoryId}/products', [UserController::class, 'showProductsByCategory'])->name('category.products');



// Route untuk search
Route::get('/search', [UserController::class, 'search'])->name('search');
//cart route
// Untuk guest - redirect ke halaman login atau register
Route::post('/cart/guest/add/{productId}', [CartController::class, 'addToCart'])->name('cart.guest.add');
Route::post('/cart/add/{id}',    [CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/update/{id}',[CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}',[CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart',              [CartController::class, 'viewCart'])->name('cart.view');

//display all products
Route::get('/products', [UserController::class, 'allProducts'])
      ->name('products.all');
// Route untuk melihat detail produk
Route::get('/products/{id}', [UserController::class, 'show'])->name('products.show');

// Route untuk checkout page (GET)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// Route untuk submit checkout (POST)
Route::post('/checkout', [CheckoutController::class, 'submit'])->name('checkout.submit');

// Route untuk checkout success page
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');













// Admin Routes Group
Route::prefix('admin')->group(function () {

    /*
|-------------------------------------------------------------------------- 
| Admin Dashboard
|-------------------------------------------------------------------------- 
*/
    Route::get('/dashboard', [OrderController::class, 'dashboard'])->name('admin.dashboard');

    /*
    |-------------------------------------------------------------------------- 
    | Category Routes
    |-------------------------------------------------------------------------- 
    */
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create'); // Tukar kepada create
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store'); // Tukar kepada store
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{id}/update', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    /*
    |-------------------------------------------------------------------------- 
    | Product Routes
    |-------------------------------------------------------------------------- 
    */
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products');
    Route::get('/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/store-product', [ProductController::class, 'store'])->name('admin.store-product');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}/update', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    /*
    |-------------------------------------------------------------------------- 
    | Order Routes
    |-------------------------------------------------------------------------- 
    */
    Route::get('/manage-orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::put('/manage-orders/{id}', [OrderController::class, 'update'])->name('admin.orders.update');



    /*
    |-------------------------------------------------------------------------- 
    | Reports Route
    |-------------------------------------------------------------------------- 
    */
    Route::get('/generate-report', [ReportController::class, 'generateReport'])->name('admin.reports.index');

});




/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::get('/profile', [ProfileController::class, 'profile'])->name('user.profile');
Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('user.edit-profile');
Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('user.update-profile');