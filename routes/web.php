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
use App\Http\Controllers\Admin\AdsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Mail;

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

Route::get('/test-email', function () {
    Mail::raw('Email test berjaya!', function ($message) {
        $message->to('nickiesam19@gmail.com')->subject('Test Email');
    });
    return 'Email dihantar!';
});
Route::get('/env-test', function () {
    dd(env('TEST_ENV_VALUE'));
});
Route::get('/debugads', function () {
    return view('debugads');
});



/*
|-------------------------------------------------------------------------- 
| Authentication Routes 
|-------------------------------------------------------------------------- 
*/
Route::get('/', function () {
    return session()->has('user_data') ? redirect()->route('home') : redirect()->route('guest.home');
});

// Authentication routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|-------------------------------------------------------------------------- 
| User & Guest Pages
|-------------------------------------------------------------------------- 
*/
Route::get('/home', [UserController::class, 'userHome'])->name('home'); // Route untuk logged-in user
Route::get('/guest/home', [UserController::class, 'guestHome'])->name('guest.home'); // Route untuk guest
Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('user.profile.edit');
Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
Route::get('/address', [UserController::class, 'address'])->name('user.address');
Route::get('/order-history', [OrderController::class, 'userOrderHistory'])->name('order.history');


// Route untuk paparkan produk mengikut kategori
Route::get('/category/{categoryId}/products', [UserController::class, 'showProductsByCategory'])->name('category.products');

// Route untuk search
Route::get('/search', [UserController::class, 'search'])->name('search');

// Cart Routes
Route::post('/cart/guest/add/{productId}', [CartController::class, 'addToCart'])->name('cart.guest.add');
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');

// Display all products
Route::get('/products', [UserController::class, 'allProducts'])->name('products.all');
// Route untuk melihat detail produk
Route::get('/products/{id}', [UserController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Checkout Routes (Final & Clean)
|--------------------------------------------------------------------------
*/

// Step 1: Papar halaman shipping
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// Step 2: Proses shipping info dan redirect ke payment page
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.submit');

// Step 3: Papar halaman payment
Route::get('/checkout/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');

// Step 4: Proses payment dan simpan order ke Firestore
Route::post('/checkout/payment', [CheckoutController::class, 'processPayment'])->name('checkout.processPayment');

// Step 5: Papar halaman 'Thank You' lepas pembayaran berjaya
Route::get('/checkout/thankyou', [CheckoutController::class, 'thankyou'])->name('checkout.thankyou');

/*
|-------------------------------------------------------------------------- 
| Admin Routes Group
|-------------------------------------------------------------------------- 
*/
Route::get('/admin/api/new-orders', [OrderController::class, 'getNewOrderCount']);
Route::get('/admin/generate-dummy-orders', [OrderController::class, 'generateDummyOrders']);


Route::prefix('admin')->name('admin.')->group(function () {
    // Resource route ni akan hasilkan nama: admin.categories.index, etc.
    Route::resource('/categories', CategoryController::class);
    Route::resource('/products', ProductController::class);
    Route::post('/store-product', [ProductController::class, 'store'])->name('store-product');
    Route::resource('/manage-orders', OrderController::class);
    Route::get('/generate-report', [ReportController::class, 'generateReport'])->name('reports.index');
    Route::get('/dashboard', [OrderController::class, 'dashboard'])->name('dashboard');
});

Route::prefix('admin')->group(function () {
    Route::get('/generate-report', [ReportController::class, 'showReportForm'])->name('admin.reports.index');

    Route::post('/generate-report', [ReportController::class, 'generateReport'])->name('admin.reports.generate');

    // Export PDF
    Route::get('/export-report', [ReportController::class, 'exportPdf'])->name('admin.reports.export');
    Route::get('/export-csv', [ReportController::class, 'exportCsv'])->name('admin.reports.exportCsv');
    Route::get('/invoices', [ReportController::class, 'listInvoices'])->name('admin.invoices');
    Route::get('/invoices/download/{filename}', [ReportController::class, 'downloadInvoice'])->name('admin.invoices.download');

});
Route::prefix('admin')->group(function () {
    Route::get('/ads', [AdsController::class, 'index'])->name('admin.ads.index');
    Route::get('/ads/create', [AdsController::class, 'create'])->name('admin.ads.create');
    Route::post('/ads/store', [AdsController::class, 'store'])->name('admin.ads.store');
    Route::delete('/ads/{id}', [AdsController::class, 'destroy'])->name('admin.ads.destroy');
});

Route::post('/checkout/toyyibpay', [CheckoutController::class, 'toyyibpayRedirect'])->name('checkout.toyyibpayRedirect');
Route::match(['GET', 'POST'], '/checkout/toyyibpay/return', [CheckoutController::class, 'toyyibpayCallback'])->name('checkout.toyyibpayReturn');

Route::get('/promotions', [UserController::class, 'promoPage'])->name('promo.page');

Route::post('/chatbox/ask', [ChatController::class, 'ask'])->name('chatbox.ask');

Route::get('/about', function () {
    return view('about');
})->name('about');



