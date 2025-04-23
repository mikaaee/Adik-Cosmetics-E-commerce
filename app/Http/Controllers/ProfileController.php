<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    /* ---------- Papar profil ---------- */
    public function profile()
    {
        $user = session('user_data');
        if (!$user || !isset($user['uid'])) {
            return redirect()->route('login.form')->with('error', 'Please login first.');
        }
    
        $uid = $user['uid'];
        $projectId = 'adikcosmetics-1518b';
        $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/users/$uid";
    
        // Hapus token kerana sudah cukup dengan UID untuk Firestore rules
        $response = Http::get($url);
        if ($response->failed()) {
            dd('Firestore error:', $response->json());
        }
    
        $doc = $response->json();
        $data = $doc['fields'] ?? [];
    
        $userProfile = [
            'name'  => $data['name']['stringValue'] ?? '',
            'email' => $data['email']['stringValue'] ?? '',
            'phone' => $data['phone']['stringValue'] ?? '',
        ];
    
        return view('dashboard.profile', compact('userProfile'));
    }
    
    /* ---------- Borang edit ---------- */
    public function editProfile()
    {
        $user = session('user_data');
        if (!$user || !isset($user['uid'])) {
            return redirect()->route('login.form')->with('error', 'Please login first.');
        }

        $uid        = $user['uid'];
        $projectId  = 'adikcosmetics-1518b';
        $url        = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/users/$uid";

        $response = Http::get($url);
        if ($response->failed()) {
            return back()->with('error', 'Unable to fetch profile.');
        }

        $data = $response->json()['fields'] ?? [];

        $userProfile = [
            'name'  => $data['name']['stringValue']  ?? '',
            'email' => $data['email']['stringValue'] ?? '',
            'phone' => $data['phone']['stringValue'] ?? '',
        ];

        return view('dashboard.edit-profile', compact('userProfile'));
    }

    /* ---------- Simpan kemas kini ---------- */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $user = session('user_data');
        if (!$user || !isset($user['uid'])) {
            return redirect()->route('login.form')->with('error', 'Please login first.');
        }

        $uid        = $user['uid'];
        $projectId  = 'adikcosmetics-1518b';
        $url        = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/users/$uid?updateMask.fieldPaths=name&updateMask.fieldPaths=phone";

        $updateData = [
            'fields' => [
                'name'  => ['stringValue' => $request->name],
                'phone' => ['stringValue' => $request->phone],
            ],
        ];

        $response = Http::patch($url, $updateData);
        if ($response->failed()) {
            return back()->with('error', 'Failed to update profile.');
        }

        return redirect()->route('user.profile')->with('success', 'Profile updated!');
    }
}
