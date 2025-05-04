<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order; // Pastikan model Order digunakan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $projectId = 'adikcosmetics-1518b';
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();
        $statusFilter = $request->input('status'); // Dapatkan query parameter

        $response = Http::withToken($accessToken)
            ->get("https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/orders");

        $orders = [];

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'];
                $status = $fields['status']['stringValue'] ?? '';

                // ✅ Apply filter kalau ada statusFilter
                if ($statusFilter && strtolower($status) !== strtolower($statusFilter)) {
                    continue;
                }

                $orders[] = [
                    'id' => basename($doc['name']),
                    'user_id' => $fields['user_id']['stringValue'] ?? '',
                    'status' => $status,
                    'shipping' => $fields['shipping']['stringValue'] ?? '',
                    'return_status' => $fields['return_status']['stringValue'] ?? '',
                    'total' => $fields['total']['doubleValue'] ?? 0.00,
                    'created_at' => $fields['created_at']['timestampValue'] ?? '',
                    'product' => $fields['items']['arrayValue']['values'][0]['mapValue']['fields']['name']['stringValue'] ?? 'Multiple Items',
                ];
            }
        }

        return view('admin.manage-orders', compact('orders', 'statusFilter'));
    }


    public function showOrders()
    {
        $response = Http::get("https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/orders");
        $documents = $response->json()['documents'] ?? [];

        $orders = [];

        foreach ($documents as $doc) {
            $fields = $doc['fields'];

            $orders[] = [
                'id' => basename($doc['name']),
                'customer_name' => $fields['customer_name']['stringValue'] ?? '',
                'product_name' => $fields['items']['arrayValue']['values'][0]['mapValue']['fields']['name']['stringValue'] ?? 'Multiple Items',
                'status' => $fields['status']['stringValue'] ?? '',
                'shipping' => $fields['shipping']['stringValue'] ?? '',
                'return_status' => $fields['return_status']['stringValue'] ?? '',
            ];
        }

        return view('admin.manage-orders', compact('orders'));
    }



    public function store(Request $request)
    {
        $projectId = env('FIREBASE_PROJECT_ID');

        $firestoreUrl = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/orders";

        $response = Http::post($firestoreUrl, [
            'fields' => [
                'customer_name' => ['stringValue' => $request->customer_name],
                'product_name' => ['stringValue' => $request->product_name],
                'status' => ['stringValue' => 'Pending'],
                'shipping' => ['stringValue' => 'Pending'],
                'return_status' => ['stringValue' => 'None'],
                'total_price' => ['doubleValue' => (float) $request->total_price],
                'created_at' => ['timestampValue' => now()->toAtomString()],
            ]
        ]);

        if ($response->successful()) {
            return response()->json(['message' => 'Order placed successfully.']);
        } else {
            return response()->json(['error' => 'Failed to place order.'], 500);
        }
    }


    public function updateStatus(Request $request, $id)
    {
        $firestoreUrl = "https://firestore.googleapis.com/v1/projects/YOUR_PROJECT_ID/databases/(default)/documents/orders/$id?updateMask.fieldPaths=status";

        $status = $request->input('status');

        $response = Http::patch($firestoreUrl, [
            'fields' => [
                'status' => ['stringValue' => $status]
            ]
        ]);

        if ($response->ok()) {
            return redirect()->back()->with('success', 'Order status updated.');
        }

        return redirect()->back()->with('error', 'Failed to update order status.');
    }
    public function update(Request $request, $id)
    {
        $data = [];

        if ($request->filled('shipping')) {
            $data['shipping'] = ['stringValue' => $request->shipping];
        }

        if ($request->filled('return_status')) {
            $data['return_status'] = ['stringValue' => $request->return_status];
        }

        if (empty($data)) {
            return redirect()->back()->with('error', 'No updates provided.');
        }

        $projectId = env('FIREBASE_PROJECT_ID');
        $firestoreUrl = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/orders/{$id}";

        $response = Http::patch($firestoreUrl, ['fields' => $data]);

        return $response->successful()
            ? redirect()->back()->with('success', 'Order updated successfully!')
            : redirect()->back()->with('error', 'Failed to update order.');
    }
    public function dashboard()
    {
        $totalSales = 0;
        $totalOrders = 0;
        $totalCustomers = 0;
        $paidOrders = 0;
        $pendingOrders = 0;
        $last7Days = [];
        $ordersPerDay = [];

        $projectId = env('FIREBASE_PROJECT_ID');
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        $resOrders = Http::withToken($accessToken)->get("https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/orders");

        if ($resOrders->successful()) {
            $documents = $resOrders->json()['documents'] ?? [];

            // Init for 7 days chart
            for ($i = 6; $i >= 0; $i--) {
                $day = now()->subDays($i)->format('Y-m-d');
                $last7Days[] = $day;
                $ordersPerDay[$day] = 0;
            }

            foreach ($documents as $doc) {
                $fields = $doc['fields'];
                $total = (float) ($fields['total']['doubleValue'] ?? 0);
                $status = $fields['status']['stringValue'] ?? '';
                $createdAt = $fields['created_at']['timestampValue'] ?? '';

                $totalSales += $total;
                $totalOrders++;

                if (strtolower($status) === 'paid') {
                    $paidOrders++;
                } elseif (strtolower($status) === 'pending') {
                    $pendingOrders++;
                }

                $date = Carbon::parse($createdAt)->format('Y-m-d');
                if (isset($ordersPerDay[$date])) {
                    $ordersPerDay[$date]++;
                }
            }
        }

        // Convert ke array untuk Chart.js
        $ordersPerDay = array_values($ordersPerDay);

        // Customers
        $resUsers = Http::withToken($accessToken)->get("https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/users");
        if ($resUsers->successful()) {
            $totalCustomers = count($resUsers->json()['documents'] ?? []);
        }

        return view('admin.dashboard', [
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'totalCustomers' => $totalCustomers,
            'paidOrders' => $paidOrders,
            'pendingOrders' => $pendingOrders,
            'last7Days' => $last7Days,
            'ordersPerDay' => $ordersPerDay,
        ]);
    }

    public function dashboardStats()
    {
        $totalSales = 0;
        $totalOrders = 0;
        $totalCustomers = 0;

        $projectId = env('FIREBASE_PROJECT_ID');
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        // Orders
        $resOrders = Http::withToken($accessToken)->get("https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/orders");
        if ($resOrders->successful()) {
            foreach ($resOrders['documents'] ?? [] as $doc) {
                $fields = $doc['fields'] ?? [];
                $totalSales += (float) ($fields['total']['doubleValue'] ?? 0);
                $totalOrders++;
            }
        }

        // Users
        $resUsers = Http::withToken($accessToken)->get("https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/users");
        if ($resUsers->successful()) {
            $totalCustomers = count($resUsers['documents'] ?? []);
        }

        return response()->json([
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'totalCustomers' => $totalCustomers,
        ]);
    }

    public function getNewOrderCount()
    {
        $projectId = env('FIREBASE_PROJECT_ID');
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/orders";

        $response = Http::withToken($accessToken)->get($url);

        $count = 0;

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];
            foreach ($documents as $doc) {
                $fields = $doc['fields'] ?? [];
                if (($fields['status']['stringValue'] ?? '') === 'Paid') {
                    $count++;
                }
            }
        }

        return response()->json(['count' => $count]);
    }
    public function generateDummyOrders()
    {
        $projectId = env('FIREBASE_PROJECT_ID');
        $accessToken = \App\Helpers\FirebaseHelper::getAccessToken();

        for ($i = 0; $i < 10; $i++) {
            $daysAgo = rand(0, 6); // tarikh antara hari ini dan 6 hari lepas
            $status = rand(0, 1) ? 'Paid' : 'Pending';

            $payload = [
                'fields' => [
                    'user_id' => ['stringValue' => 'dummy_user_' . rand(1, 5)],
                    'status' => ['stringValue' => $status],
                    'shipping' => ['stringValue' => 'Pending'],
                    'return_status' => ['stringValue' => 'None'],
                    'total' => ['doubleValue' => rand(20, 200)],
                    'created_at' => ['timestampValue' => now()->subDays($daysAgo)->toIso8601String()],
                    'items' => [
                        'arrayValue' => [
                            'values' => [
                                [
                                    'mapValue' => [
                                        'fields' => [
                                            'name' => ['stringValue' => 'Dummy Product ' . rand(1, 5)],
                                            'quantity' => ['integerValue' => rand(1, 3)],
                                            'price' => ['doubleValue' => rand(10, 100)],
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                ]
            ];

            $response = Http::withToken($accessToken)->post(
                "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/orders",
                $payload
            );

            if ($response->failed()) {
                \Log::error('❌ Dummy order gagal:', ['body' => $response->body()]);
            }
        }

        return redirect()->back()->with('success', 'Dummy orders berjaya dijana!');
    }




}
