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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
            'address' => 'required|string',
            'city' => 'required',
            'postcode' => 'required',
            'country' => 'required',
        ]);

        try {
            // 1. Create user kat Firebase Auth
            $userProperties = [
                'email' => $request->email,
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
                        'first_name' => ['stringValue' => $request->first_name],
                        'last_name' => ['stringValue' => $request->last_name],
                        'email' => ['stringValue' => $request->email],
                        'phone' => ['stringValue' => $request->phone],
                        'role' => ['stringValue' => $role],
                        'address' => ['stringValue' => $request->address],
                        'city' => ['stringValue' => $request->city],
                        'postcode' => ['stringValue' => $request->postcode],
                        'country' => ['stringValue' => $request->country],
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

            // 4. Save session to Firestore
            $this->storeSessionInFirestore([
                'uid' => $uid,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => $role,
                'address' => $request->address,
                'city' => $request->city,
                'postcode' => $request->postcode,
                'country' => $request->country,

            ]);


            // 5. Redirect ikut role
            session()->flash('success', 'Register Successful! Please login.');
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
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            // 1. Login Firebase
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);

            // 2. Get user details by email
            $user = $this->auth->getUserByEmail($request->email);
            $uid = $user->uid;

            // 3. Retrieve data from Firestore
            $client = new Client();
            $projectId = 'adikcosmetics-1518b';
            $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/users/$uid";

            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getAccessToken(),
                ]
            ]);

            if ($response->getStatusCode() === 404) {
                return back()->with('error', 'User not found in Firestore.');
            }

            $userData = json_decode($response->getBody(), true);
            $fields = $userData['fields'] ?? [];

            $role = $fields['role']['stringValue'] ?? 'user';

            // 4. Simpan session
            // 4. Simpan session ke Firestore
            $this->storeSessionInFirestore([
                'uid' => $uid,
                'first_name' => $fields['first_name']['stringValue'] ?? '',
                'last_name' => $fields['last_name']['stringValue'] ?? '',
                'email' => $fields['email']['stringValue'] ?? '',
                'phone' => $fields['phone']['stringValue'] ?? '',
                'role' => $role,
                'address' => $fields['address']['stringValue'] ?? '',
                'city' => $fields['city']['stringValue'] ?? '',
                'postcode' => $fields['postcode']['stringValue'] ?? '',
                'country' => $fields['country']['stringValue'] ?? '', // Tambah dalam session data
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

        $email = $request->input('email');

        try {
            $this->auth->sendPasswordResetLink($email); // ✅ GUNA METHOD YANG BETUL

            return back()->with('success', 'Reset email has been sent. Please check your inbox.');
        } catch (\Exception $e) {
            \Log::error('Reset link error: ' . $e->getMessage());
            return back()->with('error', 'Failed to send reset email.');
        }
    }


    public function showResetPasswordForm(Request $request)
    {
        $oobCode = $request->query('oobCode');
        return view('auth.reset-password', compact('oobCode'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'oobCode' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $this->auth->confirmPasswordReset($request->oobCode, $request->new_password);
            return redirect()->route('login')->with('success', 'Password updated successfully!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to reset password: ' . $e->getMessage());
        }
    }

    private function storeSessionInFirestore($userData)
    {
        $projectId = 'adikcosmetics-1518b';
        $uid = $userData['uid'];
        $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/sessions/$uid";

        $sessionData = [
            'fields' => [
                'first_name' => ['stringValue' => $userData['first_name']],
                'last_name' => ['stringValue' => $userData['last_name']],
                'email' => ['stringValue' => $userData['email']],
                'phone' => ['stringValue' => $userData['phone']],
                'address' => ['stringValue' => $userData['address']],
                'city' => ['stringValue' => $userData['city']],
                'postcode' => ['stringValue' => $userData['postcode']],
                'country' => ['stringValue' => $userData['country']], // Tambah dalam fields
                'last_activity' => [
                    'timestampValue' => now()->setTimezone('Asia/Kuala_Lumpur')->toISOString()
                ],
            ]
        ];

        $response = \Illuminate\Support\Facades\Http::withToken($this->getAccessToken())
            ->patch($url, $sessionData); // use PATCH instead of POST

        if ($response->failed()) {
            // optional: log error or handle fallback
            \Log::error('Failed to update session in Firestore', ['response' => $response->body()]);
        }

        // Simpan juga dalam session Laravel
        session(['user_data' => $userData]);
    }


    // ======== LOGOUT FUNCTION ========
    public function logout()
    {
        // padam nilai penting sahaja supaya token CSRF kekal
        session()->forget(['user_data', 'cart']);

        // jika mahu betul‑betul bersih, boleh gunakan flush()
        // session()->flush();

        // optional: regenerate session ID supaya lebih selamat
        session()->regenerate();

        return redirect()->route('guest.home')
            ->with('success', 'Logged out successfully!');
    }


    // ======== ACCESS TOKEN FOR FIRESTORE ========
    private function getAccessToken()
    {
        $credentials = json_decode(file_get_contents(base_path('storage/app/firebase/credentials.json')), true);

        $clientEmail = $credentials['client_email'];
        $privateKey = $credentials['private_key'];

        $now = time();
        $expires = $now + 3600;

        $payload = [
            'iss' => $clientEmail,
            'sub' => $clientEmail,
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $expires,
            'scope' => 'https://www.googleapis.com/auth/datastore'
        ];

        $jwt = JWT::encode($payload, $privateKey, 'RS256');

        $client = new Client();
        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
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