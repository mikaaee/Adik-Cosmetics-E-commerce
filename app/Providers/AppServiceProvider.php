<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FirebaseAuth::class, function ($app) {
            return app('firebase.auth');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('layouts.header-home', function ($view) {
            // Ambil kategori dari Firestore menggunakan REST API
            $response = Http::get('https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/categories');

            // Dekod JSON response
            $categories = json_decode($response->body(), true)['documents'] ?? [];

            // Pass data kategori ke view
            $view->with('categories', $categories);
        });

    }
}
