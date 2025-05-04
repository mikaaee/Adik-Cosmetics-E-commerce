<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    // Papar senarai semua invoice
    public function index(Request $request)
    {
        $directory = storage_path('app/invoices');
        $files = collect([]);

        // Filter parameter
        $search = $request->input('search');
        $dateFilter = $request->input('date'); // format: yyyy-mm-dd

        if (is_dir($directory)) {
            $files = collect(scandir($directory))
                ->filter(function ($file) {
                    return pathinfo($file, PATHINFO_EXTENSION) === 'pdf';
                })
                ->map(function ($file) use ($directory) {
                    return [
                        'name' => $file,
                        'path' => asset('storage/invoices/' . $file),
                        'date' => date('Y-m-d', filemtime($directory . '/' . $file)),
                    ];
                })
                ->filter(function ($file) use ($search, $dateFilter) {
                    $match = true;
                    if ($search) {
                        $match = str_contains(strtolower($file['name']), strtolower($search));
                    }
                    if ($match && $dateFilter) {
                        $match = $file['date'] === $dateFilter;
                    }
                    return $match;
                });
        }

        return view('admin.invoices.index', compact('files', 'search', 'dateFilter'));
    }


    // Fungsi untuk download file invoice
    public function download($filename)
    {
        $path = storage_path("app/invoices/{$filename}");

        if (!file_exists($path)) {
            abort(404, 'Invoice file not found.');
        }

        return response()->download($path);
    }
}
