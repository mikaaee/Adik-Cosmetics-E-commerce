<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{
    public function reports()
    {
        // Return a view for the reports page (make sure the view exists)
        return view('admin.reports.index'); // Create this view later
    }
    public function generateReport(Request $request)
    {
        // Dapatkan tarikh mula dan tarikh akhir
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Ambil data order berdasarkan tarikh yang dipilih
        $firestoreUrl = 'https://firestore.googleapis.com/v1/projects/YOUR_PROJECT_ID/databases/(default)/documents/orders';
        $response = Http::get($firestoreUrl);
        $filteredOrders = [];

        if ($response->ok()) {
            foreach ($response['documents'] as $doc) {
                $fields = $doc['fields'];
                $orderDate = \Carbon\Carbon::parse($fields['created_at']['timestampValue']);

                // Semak jika order berada dalam julat tarikh yang dipilih
                if ($orderDate->between($startDate, $endDate)) {
                    $filteredOrders[] = [
                        'customer_name' => $fields['customer_name']['stringValue'],
                        'product_name' => $fields['product_name']['stringValue'],
                        'total_price' => $fields['total_price']['doubleValue'],
                        'created_at' => $orderDate->format('Y-m-d')
                    ];
                }
            }
        }

        // Hantar data ke view
        return view('admin.report', compact('filteredOrders', 'startDate', 'endDate'));
    }


}
