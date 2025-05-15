<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
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
            $response = Http::withToken($this->accessToken)->get(
                "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/orders"
            );

            if (!$response->successful()) return [];

            $documents = $response->json()['documents'] ?? [];
            $results = [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'] ?? [];

                $results[] = [
                    'id' => basename($doc['name']),
                    'user_id' => $fields['user_id']['stringValue'] ?? '',
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

        // Filter jika perlu
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

        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/orders/{$id}";
        $response = Http::patch($url, ['fields' => $data]);

        return $response->successful()
            ? redirect()->back()->with('success', 'Order updated successfully!')
            : redirect()->back()->with('error', 'Failed to update order.');
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
            if ($status === 'paid') $paidOrders++;
            if ($status === 'pending') $pendingOrders++;
            if (isset($ordersPerDay[$date])) $ordersPerDay[$date]++;
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
            'totalSales', 'totalOrders', 'totalCustomers',
            'paidOrders', 'pendingOrders', 'last7Days', 'ordersPerDay'
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
        $count = Cache::remember('paid_order_count', now()->addMinutes(3), function () {
            $response = Http::withToken($this->accessToken)
                ->get("https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/orders");

            $count = 0;
            foreach ($response->json()['documents'] ?? [] as $doc) {
                $status = $doc['fields']['status']['stringValue'] ?? '';
                if ($status === 'Paid') $count++;
            }
            return $count;
        });

        return response()->json(['count' => $count]);
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
                    $orders[] = [
                        'id' => basename($doc['name']),
                        'status' => $fields['status']['stringValue'] ?? '',
                        'total' => $fields['total']['doubleValue'] ?? '',
                        'date' => $fields['created_at']['timestampValue'] ?? '',
                    ];
                }
            }
            return $orders;
        });

        return view('order-history', compact('orders'));
    }
}
