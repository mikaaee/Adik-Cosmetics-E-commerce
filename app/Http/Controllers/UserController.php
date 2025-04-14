<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    private $projectId = 'adikcosmetics-1518b';
    private $apiKey = 'AIzaSyD2Y2szwDstqTmVRHJSvVBXb25Ci3KtX6Y';

    public function __construct()
    {
        $this->projectId = env('FIREBASE_PROJECT_ID');
        $this->apiKey = env('FIREBASE_API_KEY');
    }

    // Display Home Page for Logged In User
    public function userHome()
    {
        $products = $this->getProductsFromFirestore();

        return view('dashboard.home', compact('products'));
    }

    // Display Home Page for Guest (Not Logged In)
    public function guestHome()
    {
        $products = $this->getProductsFromFirestore();

        return view('guest.guest', compact('products'));
    }

    // Optional Featured Product Page
    public function featuredProducts()
    {
        $products = $this->getProductsFromFirestore();

        return view('guest.feature-products', compact('products'));
    }

    // Fetch Products from Firestore REST API
    private function getProductsFromFirestore()
    {
        $collection = 'products';

        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}?key={$this->apiKey}";

        $response = Http::get($url);

        $products = [];

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'];

                $products[] = [
                    'id' => $doc['name'], // Full path (optional)
                    'name' => $fields['name']['stringValue'] ?? 'No Name',
                    'price' => isset($fields['price']['doubleValue']) ? $fields['price']['doubleValue'] : (isset($fields['price']['integerValue']) ? $fields['price']['integerValue'] : 0),
                    'image_url' => $fields['image_url']['stringValue'] ?? 'https://via.placeholder.com/150',
                    'description' => $fields['description']['stringValue'] ?? '',
                ];
            }
        } else {
            // Debugging
            logger()->error('Failed to fetch products from Firestore', ['response' => $response->body()]);
        }

        return $products;
    }
    public function orderHistory()
    {
        // Nanti kamu boleh ambil data order dari Firestore dan hantar ke view
        return view('order-history');
    }
    public function address()
    {
        // Kamu boleh ambil data alamat pengguna dari Firestore atau database
        return view('address');
    }
    



}
