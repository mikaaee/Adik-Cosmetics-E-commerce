<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Throwable;

class FirebaseAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil token dari session
        $idToken = session('firebase_id_token');

        if (!$idToken) {
            return redirect()->route('login.form')->with('error', 'Sila login dahulu!');
        }

        try {
            $auth = app(FirebaseAuth::class);

            // Verify ID token dari Firebase
            $verifiedIdToken = $auth->verifyIdToken($idToken);

            // Token sah ➔ proceed request
            return $next($request);

        } catch (Throwable $e) {
            // Token expired/invalid ➔ redirect login
            return redirect()->route('login.form')->with('error', 'Sesi tamat, sila login semula!');
        }
    }
}
