<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    protected $projectId;
    protected $accessToken;

    public function __construct()
    {
        $this->projectId = 'adikcosmetics-1518b';
        $this->accessToken = \App\Helpers\FirebaseHelper::getAccessToken();
    }

    public function create()
    {
        $categories = $this->fetchCategories();

        return view('admin.create-product', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
        ]);

        $imageUrl = '';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products'), $imageName);
            $imageUrl = asset('images/products/' . $imageName);
        }

        $isPromo = $request->has('is_promo'); // checkbox

        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/products";

        $data = [
            'fields' => [
                'name' => ['stringValue' => $validated['name']],
                'description' => ['stringValue' => $validated['description']],
                'price' => ['doubleValue' => (float) $validated['price']],
                'category' => ['stringValue' => $validated['category']],
                'image_url' => ['stringValue' => $imageUrl],
                'created_at' => ['timestampValue' => now()->toIso8601String()],
                'is_promo' => ['booleanValue' => $isPromo],
            ]
        ];

        $response = Http::withToken($this->accessToken)->post($url, $data);

        Cache::forget('all_products'); // refresh cache
        return $response->successful()
            ? redirect()->route('admin.products.index')->with('success', 'Product added successfully!')
            : redirect()->back()->with('error', 'Failed to add product.');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterCategory = $request->input('category');
        $filterPromo = $request->input('promo'); // "true" atau "false"


        $categories = $this->fetchCategories();

        $products = Cache::remember('all_products', now()->addMinutes(5), function () {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/products";
            $response = Http::withToken($this->accessToken)->get($url);

            $items = [];
            if ($response->successful()) {
                foreach ($response->json()['documents'] ?? [] as $doc) {
                    $fields = $doc['fields'];

                    $items[] = [
                        'id' => basename($doc['name']),
                        'name' => $fields['name']['stringValue'] ?? '',
                        'description' => $fields['description']['stringValue'] ?? '',
                        'price' => $fields['price']['doubleValue'] ?? 0,
                        'category' => $fields['category']['stringValue'] ?? '',
                        'image_url' => $fields['image_url']['stringValue'] ?? '',
                        'is_promo' => $fields['is_promo']['booleanValue'] ?? false,
                    ];
                }
            }
            return $items;
        });

        // Filter in PHP side (no extra read)
        $products = array_filter($products, function ($product) use ($search, $filterCategory, $filterPromo) {
            return (!$search || stripos($product['name'], $search) !== false) &&
                   (!$filterCategory || $product['category'] == $filterCategory) &&
                   (
                       !$filterPromo ||
                       ($filterPromo == 'true' && ($product['is_promo'] ?? false)) ||
                       ($filterPromo == 'false' && !($product['is_promo'] ?? false))
                   );
        });
        

        return view('admin.manage-products', compact('products', 'categories'));
    }

    public function edit($id)
    {
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/products/{$id}";
        $response = Http::withToken($this->accessToken)->get($url);

        if (!$response->successful() || !isset($response->json()['fields'])) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $fields = $response->json()['fields'];
        $product = [
            'id' => $id,
            'name' => $fields['name']['stringValue'] ?? '',
            'description' => $fields['description']['stringValue'] ?? '',
            'price' => $fields['price']['doubleValue'] ?? 0,
            'category' => $fields['category']['stringValue'] ?? '',
            'image_url' => $fields['image_url']['stringValue'] ?? '',
            'is_promo' => $fields['is_promo']['booleanValue'] ?? false,
        ];

        $categories = $this->fetchCategories();

        return view('admin.edit-product', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
        ]);

        $getUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/products/{$id}";
        $response = Http::withToken($this->accessToken)->get($getUrl);

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $currentImageUrl = $response->json()['fields']['image_url']['stringValue'] ?? '';
        $imageUrl = $currentImageUrl;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products'), $imageName);
            $imageUrl = asset('images/products/' . $imageName);
        }

        $isPromo = $request->has('is_promo');

        $updateUrl = "{$getUrl}?updateMask.fieldPaths=name"
            . "&updateMask.fieldPaths=description"
            . "&updateMask.fieldPaths=price"
            . "&updateMask.fieldPaths=category"
            . "&updateMask.fieldPaths=image_url"
            . "&updateMask.fieldPaths=updated_at"
            . "&updateMask.fieldPaths=is_promo";

        $data = [
            'fields' => [
                'name' => ['stringValue' => $validated['name']],
                'description' => ['stringValue' => $validated['description']],
                'price' => ['doubleValue' => (float) $validated['price']],
                'category' => ['stringValue' => $validated['category']],
                'image_url' => ['stringValue' => $imageUrl],
                'updated_at' => ['timestampValue' => now()->toIso8601String()],
                'is_promo' => ['booleanValue' => $isPromo],
            ]
        ];

        $updateResponse = Http::withToken($this->accessToken)->patch($updateUrl, $data);

        Cache::forget('all_products');

        return $updateResponse->successful()
            ? redirect()->route('admin.products.index')->with('success', 'Product updated successfully!')
            : redirect()->back()->with('error', 'Failed to update product.');
    }


    public function destroy($id)
    {
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/products/{$id}";
        $response = Http::withToken($this->accessToken)->delete($url);

        Cache::forget('all_products');

        return $response->successful()
            ? redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!')
            : redirect()->route('admin.products.index')->with('error', 'Failed to delete product!');
    }

    private function fetchCategories()
    {
        return Cache::remember('all_categories', now()->addMinutes(10), function () {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/categories";
            $response = Http::withToken($this->accessToken)->get($url);

            $categories = [];
            if ($response->successful()) {
                foreach ($response->json()['documents'] ?? [] as $doc) {
                    $fields = $doc['fields'];
                    $categories[] = [
                        'id' => basename($doc['name']),
                        'category_name' => $fields['category_name']['stringValue'] ?? 'N/A',
                    ];
                }
            }

            return $categories;
        });
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Cache::remember('all_products', now()->addMinutes(5), function () {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/products";
            $response = Http::withToken($this->accessToken)->get($url);

            $items = [];
            if ($response->successful()) {
                foreach ($response->json()['documents'] ?? [] as $doc) {
                    $fields = $doc['fields'];
                    $items[] = [
                        'name' => $fields['name']['stringValue'] ?? '',
                        'price' => $fields['price']['doubleValue'] ?? 0,
                        'image_url' => $fields['image_url']['stringValue'] ?? '',
                    ];
                }
            }
            return $items;
        });

        $filtered = array_filter($products, function ($product) use ($query) {
            return stripos($product['name'], $query) !== false;
        });

        return view('search-results', ['products' => $filtered]);
    }

    public function showByCategory($category)
    {
        $products = Cache::remember("category_{$category}_products", now()->addMinutes(5), function () {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/products";
            $response = Http::withToken($this->accessToken)->get($url);
            return $response->json()['documents'] ?? [];
        });

        $filtered = array_filter($products, function ($doc) use ($category) {
            return ($doc['fields']['category']['stringValue'] ?? '') === $category;
        });

        return view('products.index', ['products' => $filtered]);
    }

    public function show($id)
    {
        return redirect()->route('admin.products.index')->with('info', 'Show product not implemented yet.');
    }
}
