<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderController extends Controller
{
    protected $projectId;
    protected $accessToken;

    public function __construct()
    {
        $this->projectId = env('FIREBASE_PROJECT_ID', 'adikcosmetics-1518b');
        $this->accessToken = \App\Helpers\FirebaseHelper::getAccessToken();
    }

    public function index(Request $request)
    {
        $statusFilter = $request->input('status');

        $orders = Cache::remember("orders_all", now()->addMinutes(5), function () {
            // STEP 1: Get orders
            $orderResponse = Http::withToken($this->accessToken)->get(
                "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/orders"
            );

            if (!$orderResponse->successful())
                return [];

            $documents = $orderResponse->json()['documents'] ?? [];

            // STEP 2: Collect unique user IDs
            $userIds = [];
            foreach ($documents as $doc) {
                $fields = $doc['fields'] ?? [];
                $userId = $fields['user_id']['stringValue'] ?? null;
                if ($userId) {
                    $userIds[$userId] = true;
                }
            }

            // STEP 3: Get all users
            $userMap = [];
            $userResponse = Http::withToken($this->accessToken)->get(
                "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/users"
            );

            if ($userResponse->successful()) {
                foreach ($userResponse->json()['documents'] ?? [] as $userDoc) {
                    $uid = basename($userDoc['name']);
                    if (isset($userIds[$uid])) {
                        $userFields = $userDoc['fields'] ?? [];
                        $firstName = $userFields['first_name']['stringValue'] ?? 'Unknown';
                        $userMap[$uid] = $firstName;
                    }
                }
            }

            // STEP 4: Reconstruct orders with name
            $results = [];
            foreach ($documents as $doc) {
                $fields = $doc['fields'] ?? [];
                $userId = $fields['user_id']['stringValue'] ?? '';
                $firstName = $userMap[$userId] ?? 'Unknown';

                $results[] = [
                    'id' => basename($doc['name']),
                    'user_id' => $userId,
                    'user_name' => $firstName,
                    'status' => $fields['status']['stringValue'] ?? '',
                    'shipping' => $fields['shipping']['stringValue'] ?? '',
                    'return_status' => $fields['return_status']['stringValue'] ?? '',
                    'total' => $fields['total']['doubleValue'] ?? 0.00,
                    'created_at' => $fields['created_at']['timestampValue'] ?? '',
                    'product' => $fields['items']['arrayValue']['values'][0]['mapValue']['fields']['name']['stringValue'] ?? 'Multiple Items',
                ];
            }

            return $results;
        });

        // Filter status
        if ($statusFilter) {
            $orders = array_filter($orders, function ($order) use ($statusFilter) {
                return strtolower($order['status']) === strtolower($statusFilter);
            });
        }

        return view('admin.manage-orders', compact('orders', 'statusFilter'));
    }

    public function store(Request $request)
    {
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/orders";

        $response = Http::post($url, [
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

        return $response->successful()
            ? response()->json(['message' => 'Order placed successfully.'])
            : response()->json(['error' => 'Failed to place order.'], 500);
    }

    public function updateStatus(Request $request, $id)
    {
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/orders/{$id}?updateMask.fieldPaths=status";

        $status = $request->input('status');

        $response = Http::patch($url, [
            'fields' => ['status' => ['stringValue' => $status]]
        ]);

        return $response->ok()
            ? redirect()->back()->with('success', 'Order status updated.')
            : redirect()->back()->with('error', 'Failed to update order status.');
    }
    public function update(Request $request, $id)
    {
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/orders/{$id}";

        // Step 1: Get existing fields from Firestore
        $existing = Http::withToken($this->accessToken)->get($url);

        if (!$existing->successful()) {
            return redirect()->back()->with('error', 'Failed to retrieve existing order data.');
        }

        $existingFields = $existing->json()['fields'] ?? [];
        $userId = $existingFields['user_id']['stringValue'] ?? null;

        // Step 2: Merge updated fields
        $fields = $existingFields; // clone semua data asal dulu

        if ($request->filled('status')) {
            $fields['status'] = ['stringValue' => $request->status];
        }

        if ($request->filled('shipping')) {
            $fields['shipping'] = ['stringValue' => $request->shipping];
        }

        if ($request->filled('return_status')) {
            $fields['return_status'] = ['stringValue' => $request->return_status];
        }

        // Step 3: Send PATCH to Firestore
        $response = Http::withToken($this->accessToken)->patch($url, [
            'fields' => $fields
        ]);

        // Step 4: Clear relevant caches
        if ($response->successful()) {
            Cache::forget('orders_all');
            if ($userId) {
                Cache::forget("orders_user_{$userId}");
            }

            return redirect()->back()->with('success', 'Order updated successfully!');
        } else {
            \Log::error('❌ Firestore Order Update Failed:', [
                'id' => $id,
                'payload' => $fields,
                'response' => $response->json()
            ]);
            return redirect()->back()->with('error', 'Failed to update order.');
        }
    }
    public function destroy($id)
    {
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/orders/{$id}";

        $response = Http::withToken($this->accessToken)->delete($url);

        if ($response->successful()) {
            Cache::forget("orders_all"); // clear cache kalau ada
            return redirect()->back()->with('success', 'Order deleted successfully!');
        } else {
            \Log::error('❌ Firestore Order Delete Failed:', [
                'id' => $id,
                'response' => $response->json()
            ]);
            return redirect()->back()->with('error', 'Failed to delete order.');
        }
    }
    public function dashboard()
    {
        $res = Cache::remember('orders_dashboard', now()->addMinutes(3), function () {
            return Http::withToken($this->accessToken)
                ->get("https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/orders")
                ->json();
        });

        $orders = $res['documents'] ?? [];
        $totalSales = 0;
        $totalOrders = 0;
        $paidOrders = 0;
        $pendingOrders = 0;
        $ordersPerDay = [];

        $last7Days = collect(range(0, 6))->map(function ($i) {
            return now()->subDays($i)->format('Y-m-d');
        })->reverse()->values()->toArray();

        foreach ($last7Days as $day) {
            $ordersPerDay[$day] = 0;
        }

        foreach ($orders as $doc) {
            $fields = $doc['fields'] ?? [];
            $total = (float) ($fields['total']['doubleValue'] ?? 0);
            $status = strtolower($fields['status']['stringValue'] ?? '');
            $createdAt = $fields['created_at']['timestampValue'] ?? '';
            $date = Carbon::parse($createdAt)->format('Y-m-d');

            $totalSales += $total;
            $totalOrders++;
            if ($status === 'paid')
                $paidOrders++;
            if ($status === 'pending')
                $pendingOrders++;
            if (isset($ordersPerDay[$date]))
                $ordersPerDay[$date]++;
        }

        // Users
        $resUsers = Cache::remember('users_dashboard', now()->addMinutes(3), function () {
            return Http::withToken($this->accessToken)
                ->get("https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/users")
                ->json();
        });

        $totalCustomers = count($resUsers['documents'] ?? []);
        $ordersPerDay = array_values($ordersPerDay);

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalCustomers',
            'paidOrders',
            'pendingOrders',
            'last7Days',
            'ordersPerDay'
        ));
    }

    public function dashboardStats()
    {
        $stats = Cache::remember('dashboard_stats', now()->addMinutes(3), function () {
            $resOrders = Http::withToken($this->accessToken)
                ->get("https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/orders");

            $resUsers = Http::withToken($this->accessToken)
                ->get("https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/users");

            $totalSales = 0;
            $totalOrders = 0;

            foreach ($resOrders['documents'] ?? [] as $doc) {
                $fields = $doc['fields'] ?? [];
                $totalSales += (float) ($fields['total']['doubleValue'] ?? 0);
                $totalOrders++;
            }

            $totalCustomers = count($resUsers['documents'] ?? []);

            return compact('totalSales', 'totalOrders', 'totalCustomers');
        });

        return response()->json($stats);
    }

    public function getNewOrderCount()
    {
        $response = Http::withToken($this->accessToken)->get(
            "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/orders"
        );

        if (!$response->successful()) {
            return response()->json(['count' => 0, 'orders' => []]);
        }

        $documents = $response->json()['documents'] ?? [];
        $newOrders = [];

        foreach ($documents as $doc) {
            $fields = $doc['fields'] ?? [];
            if (($fields['status']['stringValue'] ?? '') === 'Paid') {
                $orderId = basename($doc['name']);
                $userId = $fields['user_id']['stringValue'] ?? null;

                $name = 'Unknown';
                if ($userId) {
                    $userResponse = Http::withToken($this->accessToken)->get(
                        "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/users/{$userId}"
                    );

                    if ($userResponse->successful()) {
                        $userFields = $userResponse->json()['fields'] ?? [];
                        $name = $userFields['first_name']['stringValue'] ?? 'Unknown';
                    }
                }

                $newOrders[] = [
                    'id' => $orderId,
                    'name' => $name,
                ];
            }
        }

        return response()->json([
            'count' => count($newOrders),
            'orders' => $newOrders
        ]);
    }

    public function userOrderHistory()
    {
        $userId = session('user_data')['uid'] ?? null;
        if (!$userId) {
            return redirect()->route('login.form')->with('error', 'Please login first.');
        }

        $orders = Cache::remember("orders_user_$userId", now()->addMinutes(5), function () use ($userId) {
            $response = Http::withToken($this->accessToken)
                ->get("https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/orders");

            $orders = [];
            foreach ($response->json()['documents'] ?? [] as $doc) {
                $fields = $doc['fields'];
                if (($fields['user_id']['stringValue'] ?? '') === $userId) {

                    // Loop through items
                    $items = [];
                    if (isset($fields['items']['arrayValue']['values'])) {
                        foreach ($fields['items']['arrayValue']['values'] as $item) {
                            $productName = $item['mapValue']['fields']['name']['stringValue'] ?? '-';
                            $quantity = $item['mapValue']['fields']['quantity']['integerValue'] ?? '1';
                            $items[] = [
                                'name' => $productName,
                                'quantity' => $quantity
                            ];
                        }
                    }

                    $orders[] = [
                        'id' => basename($doc['name']),
                        'status' => $fields['status']['stringValue'] ?? '',
                        'total' => $fields['total']['doubleValue'] ?? '',
                        'date' => $fields['created_at']['timestampValue'] ?? '',
                        'shipping' => $fields['shipping']['stringValue'] ?? 'Pending',
                        'return_status' => $fields['return_status']['stringValue'] ?? 'None',
                        'products' => $items,
                    ];
                }
            }
            return $orders;
        });

        return view('order-history', compact('orders'));
    }
    public function markAsReceived($id)
    {
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/orders/{$id}";

        // Step 1: Get ALL original fields (not just user_id!)
        $getDoc = Http::withToken($this->accessToken)->get($url);
        $fields = $getDoc['fields'] ?? [];

        if (empty($fields)) {
            \Log::error("❌ Failed to get existing fields for order ID: $id");
            return redirect()->back()->with('error', 'Unable to find order to update.');
        }

        // Step 2: Safely update shipping + status only
        $fields['shipping'] = ['stringValue' => 'Completed'];
        $fields['status'] = ['stringValue' => 'Delivered'];

        // Step 3: PATCH with full fields so nothing is lost
        $patch = Http::withToken($this->accessToken)->patch($url, [
            'fields' => $fields
        ]);

        if ($patch->successful()) {
            $userId = $fields['user_id']['stringValue'] ?? null;
            if ($userId) {
                Cache::forget("orders_user_{$userId}");
            }
            Cache::forget("orders_all");

            return redirect()->back()->with('success', 'You have confirmed receiving the order.');
        } else {
            \Log::error('❌ PATCH order update failed:', [
                'id' => $id,
                'payload' => $fields,
                'response' => $patch->json()
            ]);

            return redirect()->back()->with('error', 'Failed to update shipping status.');
        }
    }
}