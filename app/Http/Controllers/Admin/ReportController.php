<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\FirebaseHelper;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{

    public function exportPdf(Request $request)
    {
        $orders = $this->fetchOrders($request);
        $total = array_sum(array_column($orders, 'total'));

        $pdf = Pdf::loadView('admin.report-pdf', compact('orders', 'total'))->setPaper('a4', 'landscape');
        return $pdf->download('sales-report.pdf');
    }

    private function fetchOrders(Request $request)
    {
        $projectId = env('FIREBASE_PROJECT_ID', 'adikcosmetics-1518b');
        $accessToken = FirebaseHelper::getAccessToken();

        $res = Http::withToken($accessToken)
            ->get("https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/orders");

        $orders = [];
        if ($res->successful()) {
            foreach ($res['documents'] ?? [] as $doc) {
                $fields = $doc['fields'];
                $createdAt = Carbon::parse($fields['created_at']['timestampValue'] ?? now());

                // Filter
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
    }
    public function showReportForm(Request $request)
    {
        $orders = $this->fetchOrders($request);
        $total = array_sum(array_column($orders, 'total'));
        return view('admin.report', compact('orders', 'total'));
    }


    public function generate(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $status = $request->status;

        $accessToken = FirebaseHelper::getAccessToken();
        $projectId = env('FIREBASE_PROJECT_ID');
        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/orders";

        $response = Http::withToken($accessToken)->get($url);
        $orders = [];

        if ($response->successful()) {
            foreach ($response['documents'] as $doc) {
                $fields = $doc['fields'];
                $created_at = $fields['created_at']['timestampValue'] ?? '';

                if ($created_at && Carbon::parse($created_at)->between($start, $end)) {
                    if (!$status || $fields['status']['stringValue'] == $status) {
                        $orders[] = [
                            'id' => basename($doc['name']),
                            'total' => $fields['total']['doubleValue'] ?? 0,
                            'status' => $fields['status']['stringValue'] ?? '',
                            'date' => Carbon::parse($created_at)->format('Y-m-d'),
                        ];
                    }
                }
            }
        }

        $pdf = Pdf::loadView('admin.report-pdf', compact('orders', 'start', 'end', 'status'));

        return $pdf->download('sales_report_' . now()->format('Ymd_His') . '.pdf');
    }
    public function listInvoices()
    {
        $accessToken = FirebaseHelper::getAccessToken();
        $response = Http::withToken($accessToken)
            ->get("https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/invoices");

        $invoices = [];

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'];
                $invoices[] = [
                    'invoice_no' => $fields['invoice_no']['stringValue'] ?? '',
                    'user_id' => $fields['user_id']['stringValue'] ?? '',
                    'total' => $fields['total']['doubleValue'] ?? 0,
                    'file_path' => $fields['file_path']['stringValue'] ?? '',
                    'created_at' => $fields['created_at']['timestampValue'] ?? '',
                ];
            }
        }

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
