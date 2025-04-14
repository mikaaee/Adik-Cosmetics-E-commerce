<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function __construct()
    {
        // Middleware auth dikeluarkan
    }

    // Papar Profile (Maklumat peribadi)
    public function profile(Request $request)
    {
        $userData = session('user_data');
        if (!$userData || !isset($userData['uid'])) {
            return redirect()->route('login.form')->with('error', 'Please login first.');
        }

        // Firebase project info
        $projectId = 'adikcosmetics-1518b';

        $uid = $userData['uid'];

        // API endpoint untuk ambil data pengguna
        $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/users/$uid";
        $response = Http::get($url);

        if ($response->failed()) {
            dd("Firestore error:", $response->json());
        }

        $userData = $response->json();

        // Check kalau document memang tak ada
        if (!isset($userData['fields'])) {
            dd("User data tak wujud dalam Firestore:", $userData);
        }

        // Ambil data pengguna yang diperlukan sahaja
        $user = [
            'name' => $userData['fields']['name']['stringValue'] ?? '',
            'email' => $userData['fields']['email']['stringValue'] ?? '',
            'phone' => $userData['fields']['phone']['stringValue'] ?? '',
        ];

        return view('dashboard.profile', compact('user'));
    }

    // Edit Profile
    public function editProfile()
    {
        $userData = session('user_data');

        if (!$userData || !isset($userData['uid'])) {
            return redirect()->route('login.form')->with('error', 'Please login first.');
        }

        $uid = $userData['uid'];
        $projectId = 'adikcosmetics-1518b';

        // API untuk ambil data profile
        $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/users/$uid";
        $response = Http::get($url);

        if ($response->failed()) {
            return back()->with('error', 'Unable to fetch profile.');
        }

        $userData = $response->json();

        $user = [
            'name' => $userData['fields']['name']['stringValue'] ?? '',
            'email' => $userData['fields']['email']['stringValue'] ?? '',
            'phone' => $userData['fields']['phone']['stringValue'] ?? '',
        ];

        return view('dashboard.edit-profile', compact('user'));
    }

    // Kemas kini Profile
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Ambil data pengguna dari session
        $userData = session('user_data');
        if (!$userData || !isset($userData['uid'])) {
            return redirect()->route('login.form')->with('error', 'Please login first.');
        }

        $uid = $userData['uid'];
        $projectId = 'adikcosmetics-1518b';

        // Endpoint untuk kemas kini profile
        $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/users/$uid?updateMask.fieldPaths=name&updateMask.fieldPaths=phone";

        $updateData = [
            'fields' => [
                'name' => ['stringValue' => $request->name],
                'phone' => ['stringValue' => $request->phone],
            ]
        ];

        $response = Http::patch($url, $updateData);

        if ($response->failed()) {
            return back()->with('error', 'Failed to update profile.');
        }

        return redirect()->route('user.profile')->with('success', 'Profile updated!');
    }
}
