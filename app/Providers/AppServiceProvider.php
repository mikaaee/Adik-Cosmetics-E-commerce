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
        // Ambil kategori dari Firestore menggunakan REST API
        $response = Http::get('https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/categories');

        // Dekod JSON response
        $documents = json_decode($response->body(), true)['documents'] ?? [];

        // Memproses dokumen kategori untuk mengambil ID dan nama kategori
        $categories = [];
        foreach ($documents as $doc) {
            $fields = $doc['fields'] ?? [];
            $categories[] = [
                'id' => basename($doc['name']),  // Ambil ID kategori berdasarkan nama dokumen
                'name' => $fields['category_name']['stringValue'] ?? 'No Name', // Ambil nama kategori
            ];
        }

        // Share kategori ke semua view
        View::share('categories', $categories);
    }

}
