<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\FirebaseHelper;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;


class ReportController extends Controller
{
    public function showReportForm(Request $request)
    {
        $orders = $this->fetchOrders($request);
        $total = array_sum(array_column($orders, 'total'));

        return view('admin.report', compact('orders', 'total'));
    }

    public function exportPdf(Request $request)
    {
        $orders = $this->fetchOrders($request);
        $total = array_sum(array_column($orders, 'total'));

        $paidCount = collect($orders)->where('status', 'Paid')->count();
        $pendingCount = collect($orders)->where('status', 'Pending')->count();

        $start = $request->input('start');
        $end = $request->input('end');
        $status = $request->input('status');

        $pdf = Pdf::loadView('admin.report-pdf', compact(
            'orders',
            'total',
            'paidCount',
            'pendingCount',
            'start',
            'end',
            'status'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('sales-report.pdf');
    }
    public function exportCsv(Request $request)
    {
        $orders = $this->fetchOrders($request);

        $filename = 'sales-report.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, ['No.', 'Order ID', 'User ID', 'Status', 'Total (RM)', 'Created At']);

            // Data rows
            foreach ($orders as $index => $order) {
                fputcsv($handle, [
                    $index + 1,
                    $order['id'],
                    $order['user_id'] ?? 'Guest',
                    $order['status'],
                    number_format($order['total'], 2),
                    $order['created_at']
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }



    public function generate(Request $request)
    {
        $orders = $this->fetchOrders($request);
        $start = $request->start_date;
        $end = $request->end_date;
        $status = $request->status;

        $pdf = Pdf::loadView('admin.report-pdf', compact('orders', 'start', 'end', 'status'));
        return $pdf->download('sales_report_' . now()->format('Ymd_His') . '.pdf');
    }

    private function fetchOrders(Request $request)
    {
        $start = $request->start_date ? Carbon::parse($request->start_date)->format('Y-m-d') : 'none';
        $end = $request->end_date ? Carbon::parse($request->end_date)->format('Y-m-d') : 'none';
        $status = $request->status ?? 'all';

        $cacheKey = "orders_{$start}_{$end}_{$status}";
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($request) {
            $projectId = env('FIREBASE_PROJECT_ID', 'adikcosmetics-1518b');
            $accessToken = FirebaseHelper::getAccessToken();

            $res = Http::withToken($accessToken)
                ->get("https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/orders");

            $orders = [];
            if ($res->successful()) {
                foreach ($res['documents'] ?? [] as $doc) {
                    $fields = $doc['fields'];
                    $createdAt = Carbon::parse($fields['created_at']['timestampValue'] ?? now());

                    // Filtering
                    $start = $request->start_date ? Carbon::parse($request->start_date) : null;
                    $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;
                    $statusFilter = $request->status;

                    if ($start && $createdAt->lt($start))
                        continue;
                    if ($end && $createdAt->gt($end))
                        continue;
                    if ($statusFilter && strtolower($fields['status']['stringValue'] ?? '') !== strtolower($statusFilter))
                        continue;

                    $orders[] = [
                        'id' => basename($doc['name']),
                        'user_id' => $fields['user_id']['stringValue'] ?? '',
                        'status' => $fields['status']['stringValue'] ?? '',
                        'total' => (float) ($fields['total']['doubleValue'] ?? 0),
                        'created_at' => $createdAt->format('Y-m-d H:i'),
                    ];
                }
            }

            return $orders;
        });
    }

    public function listInvoices()
    {
        $invoices = Cache::remember('admin_invoice_list', now()->addMinutes(5), function () {
            $accessToken = FirebaseHelper::getAccessToken();
            $response = Http::withToken($accessToken)
                ->get("https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/invoices");

            $results = [];

            if ($response->successful()) {
                foreach ($response->json()['documents'] ?? [] as $doc) {
                    $fields = $doc['fields'];
                    $results[] = [
                        'invoice_no' => $fields['invoice_no']['stringValue'] ?? '',
                        'user_id' => $fields['user_id']['stringValue'] ?? '',
                        'total' => $fields['total']['doubleValue'] ?? 0,
                        'file_path' => $fields['file_path']['stringValue'] ?? '',
                        'created_at' => $fields['created_at']['timestampValue'] ?? '',
                    ];
                }
            }

            return $results;
        });

        return view('admin.invoices.index', compact('invoices'));
    }

    public function downloadInvoice($filename)
    {
        $path = 'invoices/' . $filename;

        if (!Storage::exists($path)) {
            abort(404, 'Invoice file not found.');
        }

        return Storage::download($path);
    }
}
