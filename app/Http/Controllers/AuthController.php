<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use GuzzleHttp\Client;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    private $auth;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(base_path('storage/app/firebase/credentials.json'));
        $this->auth = $factory->createAuth();
    }

    // ======== REGISTER FORM ========
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // ======== REGISTER FUNCTION ========
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email',
            'phone'    => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        try {
            // 1. Create user kat Firebase Auth
            $userProperties = [
                'email'    => $request->email,
                'password' => $request->password,
            ];

            $createdUser = $this->auth->createUser($userProperties);

            // 2. Auto-assign role
            $role = ($request->email === 'admin@adikcosmetics.com') ? 'admin' : 'user';

            // 3. Simpan user ke Firestore
            $client = new Client();
            $projectId = 'adikcosmetics-1518b';
            $uid = $createdUser->uid;

            $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/users?documentId=$uid";

            $response = $client->post($url, [
                'json' => [
                    'fields' => [
                        'name'  => ['stringValue' => $request->name],
                        'email' => ['stringValue' => $request->email],
                        'phone' => ['stringValue' => $request->phone],
                        'role'  => ['stringValue' => $role],
                    ]
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getAccessToken(),
                ]
            ]);

            if ($response->getStatusCode() !== 200) {
                return back()->with('error', 'Failed to save user to Firestore!');
            }

            // 4. Save session
            session([
                'user_data' => [
                    'uid'   => $uid,
                    'name'  => $request->name,
                    'email' => $request->email,
                    'role'  => $role,
                ]
            ]);

            // 5. Redirect ikut role
            return $this->redirectUser($role);

        } catch (\Throwable $e) {
            return back()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    // ======== LOGIN FORM ========
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ======== LOGIN FUNCTION ========
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            // 1. Login Firebase
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);

            // 2. Get user details by email
            $user = $this->auth->getUserByEmail($request->email);
            $uid  = $user->uid;

            // 3. Retrieve data from Firestore
            $client    = new Client();
            $projectId = 'adikcosmetics-1518b';
            $url       = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/users/$uid";

            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getAccessToken(),
                ]
            ]);

            if ($response->getStatusCode() === 404) {
                return back()->with('error', 'User not found in Firestore.');
            }

            $userData = json_decode($response->getBody(), true);
            $fields   = $userData['fields'] ?? [];

            $role = $fields['role']['stringValue'] ?? 'user';

            // 4. Simpan session
            session([
                'user_data' => [
                    'uid'   => $uid,
                    'name'  => $fields['name']['stringValue'] ?? '',
                    'email' => $fields['email']['stringValue'] ?? '',
                    'role'  => $role,
                ]
            ]);

            // 5. Redirect ikut role
            return $this->redirectUser($role);

        } catch (\Throwable $e) {
            return back()->with('error', 'Invalid email or password: ' . $e->getMessage());
        }
    }

    // ======== FORGOT PASSWORD FORM ========
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // ======== SEND RESET LINK ========
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $this->auth->sendPasswordResetEmail($request->email);

            return back()->with('success', 'Password reset link sent!');

        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to send reset link: ' . $e->getMessage());
        }
    }

    // ======== LOGOUT FUNCTION ========
    public function logout()
    {
        session()->flush(); // clear semua session
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }

    // ======== ACCESS TOKEN FOR FIRESTORE ========
    private function getAccessToken()
    {
        $credentials = json_decode(file_get_contents(base_path('storage/app/firebase/credentials.json')), true);

        $clientEmail = $credentials['client_email'];
        $privateKey  = $credentials['private_key'];

        $now     = time();
        $expires = $now + 3600;

        $payload = [
            'iss'   => $clientEmail,
            'sub'   => $clientEmail,
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $expires,
            'scope' => 'https://www.googleapis.com/auth/datastore'
        ];

        $jwt = JWT::encode($payload, $privateKey, 'RS256');

        $client   = new Client();
        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        return $data['access_token'];
    }

    // ======== REDIRECT BASED ON ROLE ========
    private function redirectUser($role)
    {
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
        } else {
            return redirect()->route('home')->with('success', 'Welcome!');
        }
    }
}
