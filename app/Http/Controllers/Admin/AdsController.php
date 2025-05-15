<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Helpers\FirebaseHelper;

class AdsController extends Controller
{
    public function index()
    {
        $accessToken = FirebaseHelper::getAccessToken();
        $url = "https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/ads";

        $response = Http::withToken($accessToken)->get($url);
        $ads = [];

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'] ?? [];
                $ads[] = [
                    'id' => basename($doc['name']),
                    'title' => $fields['title']['stringValue'] ?? '',
                    'image_url' => $fields['image_url']['stringValue'] ?? '',
                ];
            }
        }

        return view('admin.ads.index', compact('ads'));
    }

    public function create()
    {
        return view('admin.ads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_url' => 'nullable|url',
        ]);

        $imageUrl = '';

        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $imageName = time() . '_' . $image->getClientOriginalName();

            $adsFolder = public_path('images/ads');
            if (!file_exists($adsFolder)) {
                mkdir($adsFolder, 0755, true);
            }

            $image->move($adsFolder, $imageName);
            $imageUrl = url('images/ads/' . $imageName);
        } elseif ($request->filled('image_url')) {
            $imageUrl = $request->image_url;
        }

        if (empty($imageUrl)) {
            return back()->with('error', 'Please upload an image or provide an image URL.');
        }

        // Firestore API
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();
        $url = "https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/ads";

        $payload = [
            'fields' => [
                'title' => ['stringValue' => $request->title],
                'image_url' => ['stringValue' => $imageUrl],
            ]
        ];

        $response = Http::withToken($accessToken)->post($url, $payload);

        if ($response->successful()) {
            return redirect()->route('admin.ads.index')->with('success', 'Ad successfully created!');
        } else {
            return back()->with('error', 'Failed to save ad: ' . $response->body());
        }
    }


    public function destroy($id)
    {
        $accessToken = FirebaseHelper::getAccessToken();
        $url = "https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/ads/{$id}";
        Http::withToken($accessToken)->delete($url);

        return back()->with('success', 'Ad deleted!');
    }
}
