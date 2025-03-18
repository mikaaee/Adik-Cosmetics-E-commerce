<?php

namespace App\Helpers;

use Google\Auth\OAuth2;

class FirebaseHelper
{
    public static function getAccessToken()
    {
        $serviceAccountPath = storage_path('app/firebase/credentials.json');

        if (!file_exists($serviceAccountPath)) {
            throw new \Exception('Credentials file not found at: ' . $serviceAccountPath);
        }

        $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);

        if (!$serviceAccount || !isset($serviceAccount['private_key'])) {
            throw new \Exception('Invalid credentials file.');
        }

        $oauth = new OAuth2([
            'audience' => 'https://oauth2.googleapis.com/token', // fixed: auth endpoint
            'issuer' => $serviceAccount['client_email'],
            'signingAlgorithm' => 'RS256',
            'signingKey' => $serviceAccount['private_key'],
            'tokenCredentialUri' => 'https://oauth2.googleapis.com/token', // fixed: token credential URI
            'scope' => 'https://www.googleapis.com/auth/datastore', // access scope
        ]);

        // Fetch the token
        $accessTokenArray = $oauth->fetchAuthToken();

        if (!isset($accessTokenArray['access_token'])) {
            throw new \Exception('Failed to retrieve access token.');
        }

        return $accessTokenArray['access_token'];
    }
}
