<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FirebaseHelper;
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller
{
    public function create()
    {
        return view('admin.create-category');  // Gantikan 'admin.add-category' dengan 'admin.create-category'
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $projectId = 'adikcosmetics-1518b'; // project id Firestore
        $accessToken = FirebaseHelper::getAccessToken();

        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/categories";

        $response = Http::withToken($accessToken)->post($url, [
            "fields" => [
                "category_name" => [
                    "stringValue" => $validated['category_name'],
                ],
                "created_at" => [
                    "timestampValue" => now()->toIso8601String(),
                ],
            ],
        ]);

        if ($response->successful()) {
            return redirect()->route('admin.categories.index')->with('success', 'Category added successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to add category.');
        }
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $projectId = 'adikcosmetics-1518b';
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/categories";
        $response = \Illuminate\Support\Facades\Http::withToken($accessToken)->get($url);

        $categories = [];

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'];
                $categoryName = $fields['category_name']['stringValue'] ?? '';

                // Filter ikut search jika ada
                if ($search && stripos($categoryName, $search) === false) {
                    continue;
                }

                $categories[] = [
                    'id' => basename($doc['name']),
                    'category_name' => $categoryName,
                    'created_at' => $fields['created_at']['timestampValue'] ?? '',
                ];
            }
        }

        return view('admin.manage-categories', compact('categories'));
    }

    // Show edit form
    public function edit($id)
    {
        $projectId = 'adikcosmetics-1518b';
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/categories/{$id}";

        $response = \Illuminate\Support\Facades\Http::withToken($accessToken)->get($url);

        if ($response->successful()) {
            $doc = $response->json();
            $fields = $doc['fields'];

            $category = [
                'id' => $id,
                'category_name' => $fields['category_name']['stringValue'] ?? '',
            ];

            return view('admin.edit-category', compact('category'));
        }

        return redirect()->route('admin.categories')->with('error', 'Category not found.');
    }

    // Update category
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $projectId = 'adikcosmetics-1518b';
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/categories/{$id}?updateMask.fieldPaths=category_name";

        $response = \Illuminate\Support\Facades\Http::withToken($accessToken)->patch($url, [
            "fields" => [
                "category_name" => [
                    "stringValue" => $request->category_name,
                ],
            ],
        ]);

        if ($response->successful()) {
            return redirect()->route('admin.categories')->with('success', 'Category updated successfully!');
        }

        return redirect()->back()->with('error', 'Failed to update category.');
    }
    public function destroy($id)
    {
        $projectId = 'adikcosmetics-1518b';
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/categories/{$id}";

        $response = \Illuminate\Support\Facades\Http::withToken($accessToken)->delete($url);

        if ($response->successful()) {
            return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
        }

        return redirect()->back()->with('error', 'Failed to delete category.');
    }
    public function show($id)
    {
        return redirect()->route('admin.categories.index')->with('info', 'Show product not implemented yet.');
    }



}
