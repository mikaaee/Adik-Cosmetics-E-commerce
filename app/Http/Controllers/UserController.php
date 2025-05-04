<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    private $projectId;
    private $apiKey;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id', env('FIREBASE_PROJECT_ID'));
        $this->apiKey = config('services.firebase.api_key', env('FIREBASE_API_KEY'));
    }
    private function getAdsFromFirestore()
    {
        $ads = [];
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/ads?key={$this->apiKey}";

        $response = Http::get($url);

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'];

                $ads[] = [
                    'title' => $fields['title']['stringValue'] ?? '',
                    'image_url' => $fields['image_url']['stringValue'] ?? '',
                ];
            }
        }

        return $ads;
    }
    public function userHome()
    {
        $categories = $this->getCategoriesFromFirestore();
        $ads = $this->getAdsFromFirestore();
        return view('dashboard.home', compact('categories', 'ads'));
    }

    public function guestHome()
    {
        $categories = $this->getCategoriesFromFirestore();
        $ads = $this->getAdsFromFirestore();
        return view('guest.guest', compact('categories', 'ads'));
    }


    private function getCategoriesFromFirestore()
    {
        $collection = 'categories';

        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}?key={$this->apiKey}";

        $response = Http::get($url);

        logger()->info('Firestore Categories Response:', ['response' => $response->json()]);

        $categories = [];

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'] ?? [];

                $categories[] = [
                    'id' => basename($doc['name']),
                    'name' => $fields['category_name']['stringValue'] ?? 'No Name',
                ];
            }
        } else {
            logger()->error('Failed to fetch categories from Firestore', ['response' => $response->body()]);
        }

        return $categories;
    }

    public function showProductsByCategory($categoryId)
    {
        $categories = $this->getCategoriesFromFirestore(); // tambah balik ke view
        $category = collect($categories)->firstWhere('id', $categoryId);
        $products = $this->getProductsByCategory($categoryId);
        //dd($categoryId, $category, $products);
        // Tentukan view yang hendak dipilih berdasarkan status login
        if (session()->has('user_data')) {
            return view('category.products', compact('category', 'products', 'categories'));
        } else {
            return view('category.guest-products', compact('category', 'products', 'categories'));
        }

    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        // Logik untuk cari produk based on query
        $products = $this->getProductsFromFirestore(); // Get all products dulu

        // Filter products ikut search query
        $filteredProducts = array_filter($products, function ($product) use ($query) {
            return stripos($product['name'], $query) !== false;
        });

        return view('search.results', compact('filteredProducts', 'query'));
    }


    private function getProductsFromFirestore()
    {
        $collection = 'products';

        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}?key={$this->apiKey}";

        $response = Http::get($url);

        $products = [];

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'] ?? [];

                $products[] = [
                    'id' => basename($doc['name']),            // document ID
                    'name' => $fields['name']['stringValue'] ?? 'No Name',
                    'price' => $fields['price']['doubleValue']
                        ?? ($fields['price']['integerValue'] ?? 0),
                    'image_url' => $fields['image_url']['stringValue'] ?? 'https://via.placeholder.com/150',
                    'description' => $fields['description']['stringValue'] ?? '',
                    'category' => $fields['category']['stringValue']   // ← ambil nilai "Henna", "Makeup" dll.
                ];

            }
        } else {
            logger()->error('Failed to fetch products from Firestore', ['response' => $response->body()]);
        }

        return $products;
    }

    private function getProductsByCategory($categoryId)
    {
        $categories = $this->getCategoriesFromFirestore(); // Get the categories to get the category name
        $category = collect($categories)->firstWhere('id', $categoryId);
        $categoryName = $category['name']; // Get the name of the category (e.g., "Makeup")

        $collection = 'products';

        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}?key={$this->apiKey}";

        $response = Http::get($url);

        $products = [];

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'] ?? [];

                // Get the category field from the product document
                $productCategory = $fields['category']['stringValue'] ?? null;

                // Debugging - Check category and productCategory
                logger()->info("Checking product: ", ['category' => $categoryName, 'productCategory' => $productCategory]);

                // Match category name with the category passed
                if ($productCategory === $categoryName) {
                    $products[] = [
                        'id' => basename($doc['name']),
                        'name' => $fields['name']['stringValue'] ?? 'No Name',
                        'price' => isset($fields['price']['doubleValue']) ? $fields['price']['doubleValue'] : (isset($fields['price']['integerValue']) ? $fields['price']['integerValue'] : 0),
                        'image_url' => $fields['image_url']['stringValue'] ?? 'https://via.placeholder.com/150',
                        'description' => $fields['description']['stringValue'] ?? '',
                    ];
                }
            }
        } else {
            logger()->error('Failed to fetch products by category', ['response' => $response->body()]);
        }

        return $products;
    }
    public function getProductFromFirestoreById($id)
    {
        $collection = 'products';

        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}/{$id}?key={$this->apiKey}";

        $response = Http::get($url);

        $product = null;

        if ($response->successful()) {
            $doc = $response->json()['fields'] ?? [];

            $product = [
                'id' => $id,
                'name' => $doc['name']['stringValue'] ?? 'No Name',
                'price' => $doc['price']['doubleValue'] ?? ($doc['price']['integerValue'] ?? 0),
                'image_url' => $doc['image_url']['stringValue'] ?? 'https://via.placeholder.com/150',
                'description' => $doc['description']['stringValue'] ?? '',
                'category' => $doc['category']['stringValue'] ?? 'Uncategorized',
            ];
        } else {
            logger()->error('Failed to fetch product from Firestore', ['response' => $response->body()]);
        }

        return $product;
    }

    public function allProducts()
    {
        $products = $this->getProductsFromFirestore();   // ambil semua produk
        $categories = $this->getCategoriesFromFirestore(); // untuk filter sidebar

        return view('products.all', compact('products', 'categories'));
    }
    public function show($id)
    {
        // Ambil produk berdasarkan ID dari Firestore
        $product = $this->getProductFromFirestoreById($id);

        // Pastikan produk ada
        if ($product) {
            // Return view dengan data produk
            return view('products.details', compact('product'));
        } else {
            return redirect()->route('products.all')->with('error', 'Product not found.');
        }
    }





    public function orderHistory()
    {
        return view('order-history');
    }

    public function address()
    {
        return view('address');
    }
}
