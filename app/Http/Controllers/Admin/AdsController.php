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
            'image_url' => 'required|url',
        ]);

        $accessToken = FirebaseHelper::getAccessToken();
        $url = "https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/ads";

        $payload = [
            'fields' => [
                'title' => ['stringValue' => $request->title],
                'image_url' => ['stringValue' => $request->image_url],
            ]
        ];

        Http::withToken($accessToken)->post($url, $payload);

        return redirect()->route('admin.ads.index')->with('success', 'Ad added!');
    }

    public function destroy($id)
    {
        $accessToken = FirebaseHelper::getAccessToken();
        $url = "https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/ads/{$id}";
        Http::withToken($accessToken)->delete($url);

        return back()->with('success', 'Ad deleted!');
    }
}
