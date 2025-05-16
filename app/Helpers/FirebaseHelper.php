<?php

namespace App\Helpers;

use Google\Auth\OAuth2;
use Google\Cloud\Core\Timestamp;
use Carbon\Carbon;

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
    public static function getOrdersBetween($start, $end)
    {
        $firestore = (new Factory)->createFirestore()->database();
        $ordersRef = $firestore->collection('orders');

        // Convert Y-m-d to Firestore Timestamp
        $startTs = new Timestamp(Carbon::parse($start)->startOfDay());
        $endTs = new Timestamp(Carbon::parse($end)->endOfDay());

        $query = $ordersRef
            ->where('created_at', '>=', $startTs)
            ->where('created_at', '<=', $endTs)
            ->documents();

        $orders = [];

        foreach ($query as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();
                $data['id'] = $doc->id();

                // Convert created_at timestamp to Y-m-d (for chart)
                if (isset($data['created_at']) && $data['created_at'] instanceof \Google\Cloud\Core\Timestamp) {
                    $data['created_at'] = Carbon::parse($data['created_at']->formatAsString())->toDateString();
                }

                $orders[] = $data;
            }
        }

        return $orders;
    }

}
