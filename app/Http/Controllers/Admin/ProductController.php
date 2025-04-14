<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Product;


class ProductController extends Controller
{
    // Tunjuk form add product (sama mcm kau buat untuk category)
    public function create()
    {
        $projectId = 'adikcosmetics-1518b';
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/categories";

        $response = Http::withToken($accessToken)->get($url);

        $categories = [];

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'];

                $categories[] = [
                    'id' => basename($doc['name']),
                    'category_name' => $fields['category_name']['stringValue'] ?? 'N/A',
                ];
            }
        }

        return view('admin.add-product', compact('categories'));
    }

    // Store product GUNA REST API
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            // Optional: 'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // ➡️ Kalau nak upload image, pakai local storage, contohnya simpan dalam public/images
        $imageUrl = '';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products'), $imageName);

            // URL akses image dari public folder
            $imageUrl = asset('images/products/' . $imageName);
        }

        // ➡️ Firestore REST API URL
        $projectId = 'adikcosmetics-1518b';
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/products";

        // ➡️ Payload data ikut structure Firestore REST API
        $data = [
            'fields' => [
                'name' => ['stringValue' => $validated['name']],
                'description' => ['stringValue' => $validated['description']],
                'price' => ['doubleValue' => (float) $validated['price']],
                'category' => ['stringValue' => $validated['category']],
                'image_url' => ['stringValue' => $imageUrl],
                'created_at' => ['timestampValue' => now()->toIso8601String()],
            ]
        ];

        $response = Http::withToken($accessToken)->post($url, $data);

        if ($response->successful()) {
            return redirect()->route('admin.add-product')->with('success', 'Product added successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to add product: ' . $response->body());
        }
    }

    // List all products (untuk All Product page & Sidebar)
    public function index()
    {
        $projectId = 'adikcosmetics-1518b'; // project ID kau
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        // Endpoint REST API Firestore (collection: products)
        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/products";

        // GET data dari Firestore
        $response = \Illuminate\Support\Facades\Http::withToken($accessToken)->get($url);

        $products = [];

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'];

                $products[] = [
                    'id' => basename($doc['name']),
                    'name' => $fields['name']['stringValue'] ?? '',
                    'description' => $fields['description']['stringValue'] ?? '',
                    'price' => $fields['price']['doubleValue'] ?? 0,
                    'category' => $fields['category']['stringValue'] ?? '',
                    'image_url' => $fields['image_url']['stringValue'] ?? '',
                ];
            }
        }

        // Hantar ke blade file
        return view('admin.all-products', compact('products'));
    }
    public function edit($id)
    {
        $projectId = 'adikcosmetics-1518b';
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        // Fetch product detail by ID
        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/products/{$id}";

        $response = \Illuminate\Support\Facades\Http::withToken($accessToken)->get($url);

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Failed to retrieve product.');
        }

        $doc = $response->json();

        // Pastikan data wujud
        if (!isset($doc['fields'])) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Convert Firestore format → array biasa
        $product = [
            'id' => $id,
            'name' => $doc['fields']['name']['stringValue'] ?? '',
            'description' => $doc['fields']['description']['stringValue'] ?? '',
            'price' => $doc['fields']['price']['doubleValue'] ?? 0,
            'category' => $doc['fields']['category']['stringValue'] ?? '',
            'image_url' => $doc['fields']['image_url']['stringValue'] ?? '',
        ];

        // Ambil categories juga kalau nak letak dalam dropdown
        $categories = $this->fetchCategories(); // boleh guna function dari create()

        return view('admin.edit-product', compact('product', 'categories'));
    }

    // Helper untuk ambil categories (boleh asing ke tempat lain)
    private function fetchCategories()
    {
        $projectId = 'adikcosmetics-1518b';
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/categories";

        $response = \Illuminate\Support\Facades\Http::withToken($accessToken)->get($url);

        $categories = [];

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'];

                $categories[] = [
                    'id' => basename($doc['name']),
                    'category_name' => $fields['category_name']['stringValue'] ?? 'N/A',
                ];
            }
        }

        return $categories;
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
        ]);

        $projectId = 'adikcosmetics-1518b';
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        // Fetch current product untuk dapatkan existing image URL
        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/products/{$id}";

        $response = Http::withToken($accessToken)->get($url);

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $currentProduct = $response->json();
        $currentImageUrl = $currentProduct['fields']['image_url']['stringValue'] ?? '';

        // Handle image upload kalau ada gambar baru
        $imageUrl = $currentImageUrl;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products'), $imageName);

            $imageUrl = asset('images/products/' . $imageName);
        }

        // Prepare data untuk update (Firestore pakai PATCH)
        $data = [
            'fields' => [
                'name' => ['stringValue' => $validated['name']],
                'description' => ['stringValue' => $validated['description']],
                'price' => ['doubleValue' => (float) $validated['price']],
                'category' => ['stringValue' => $validated['category']],
                'image_url' => ['stringValue' => $imageUrl],
                'updated_at' => ['timestampValue' => now()->toIso8601String()],
            ]
        ];

        $updateUrl = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/products/{$id}?updateMask.fieldPaths=name&updateMask.fieldPaths=description&updateMask.fieldPaths=price&updateMask.fieldPaths=category&updateMask.fieldPaths=image_url&updateMask.fieldPaths=updated_at";

        $updateResponse = Http::withToken($accessToken)->patch($updateUrl, $data);

        if ($updateResponse->successful()) {
            return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update product.');
        }
    }


    public function destroy($id)
    {
        try {
            $projectId = 'adikcosmetics-1518b';
            $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

            // URL untuk delete specific product
            $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/products/{$id}";

            // DELETE request ke Firestore
            $response = Http::withToken($accessToken)->delete($url);

            if ($response->successful()) {
                return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
            } else {
                return redirect()->route('admin.products')->with('error', 'Failed to delete product!');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.products')->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }
    public function featuredProducts()
    {
        // Firestore project info
        $projectId = 'adikcosmetics-1518b';
        $apiKey = 'AIzaSyD2Y2szwDstqTmVRHJSvVBXb25Ci3KtX6Y';  // Gantikan dengan API key yang betul

        // Endpoint untuk dapatkan produk
        $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/products";

        // Query untuk cari produk dengan is_featured = true
        $response = Http::get($url, [
            'where' => [
                'field' => [
                    'fieldPath' => 'is_featured',
                    'value' => [
                        'booleanValue' => true,
                    ],
                ],
            ],
        ]);
        // Debug respons API Firestore
        dd($response->json());  // Debug respons API Firestore




        // Parse response untuk ambil data produk
        $products = $response->json()['documents'] ?? [];

        // Mapkan response ke dalam format yang sesuai untuk view
        $products = array_map(function ($product) {
            return [
                'id' => $product['name'],
                'name' => $product['fields']['name']['stringValue'] ?? '',
                'price' => $product['fields']['price']['doubleValue'] ?? 0,
                'image_url' => $product['fields']['image_url']['stringValue'] ?? '',
            ];
        }, $products);
        // Debugkan data produk
        dd($products);

        return view('partials.feature-products', compact('products'));
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
    
        // Endpoint Firestore untuk dapatkan semua dokumen dari koleksi 'products'
        $url = 'https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/products';
    
        $response = Http::get($url);
    
        $filteredProducts = [];
    
        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];
    
            foreach ($documents as $doc) {
                $fields = $doc['fields'];
    
                $product = [
                    'name' => $fields['name']['stringValue'] ?? '',
                    'price' => $fields['price']['doubleValue'] ?? 0,
                    'image_url' => $fields['image_url']['stringValue'] ?? ''
                ];
    
                // Cari produk ikut nama (case-insensitive)
                if (stripos($product['name'], $query) !== false) {
                    $filteredProducts[] = $product;
                }
            }
        }
    
        // Return view untuk paparkan hasil carian
        return view('search-results', ['products' => $filteredProducts]);
    }
    
}

