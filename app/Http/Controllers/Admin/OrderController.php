<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order; // Pastikan model Order digunakan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    
    public function index()
    {
        $firestoreUrl = 'https://firestore.googleapis.com/v1/projects/YOUR_PROJECT_ID/databases/(default)/documents/orders';

        $response = Http::get($firestoreUrl);
        $orders = [];

        if ($response->ok()) {
            foreach ($response['documents'] as $doc) {
                $fields = $doc['fields'];

                $orders[] = [
                    'id' => basename($doc['name']),
                    'customer_name' => $fields['customer_name']['stringValue'],
                    'status' => $fields['status']['stringValue'],
                    'total_price' => $fields['total_price']['doubleValue'],
                    'created_at' => $fields['created_at']['timestampValue']
                ];
            }
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
        // Firestore URL untuk mengakses data orders dan customers
        $firestoreOrdersUrl = 'https://firestore.googleapis.com/v1/projects/YOUR_PROJECT_ID/databases/(default)/documents/orders';
        $firestoreCustomersUrl = 'https://firestore.googleapis.com/v1/projects/YOUR_PROJECT_ID/databases/(default)/documents/customers';

        // Ambil data orders dari Firestore
        $responseOrders = Http::get($firestoreOrdersUrl);
        $totalSales = 0;
        $totalOrders = 0;

        if ($responseOrders->ok()) {
            foreach ($responseOrders['documents'] as $doc) {
                $fields = $doc['fields'];
                $totalSales += $fields['total_price']['doubleValue'];
                $totalOrders++;
            }
        }

        // Ambil data customers dari Firestore
        $responseCustomers = Http::get($firestoreCustomersUrl);
        $totalCustomers = 0;

        if ($responseCustomers->ok()) {
            $totalCustomers = count($responseCustomers['documents']);
        }

        return view('admin.dashboard', compact('totalSales', 'totalCustomers', 'totalOrders'));
    }


}
