<?php

namespace App\Http\Controllers;

use App\Helpers\FirebaseHelper;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Ambil date range dari request
        $range = $request->input('date_range');

        if ($range && str_contains($range, ' to ')) {
            [$startDate, $endDate] = explode(' to ', $range);
        } else {
            $endDate = now()->toDateString();
            $startDate = now()->subDays(6)->toDateString(); // Default: last 7 days
        }

        // Fetch all orders (ikut cara Firestore kau simpan)
        $orders = FirebaseHelper::getOrdersBetween($startDate, $endDate);

        // Proses kiraan
        $totalSales = 0;
        $totalOrders = count($orders);
        $totalCustomers = count(collect($orders)->pluck('user_id')->unique());

        $paidOrders = 0;
        $pendingOrders = 0;
        $ordersPerDay = [];

        foreach ($orders as $order) {
            $status = $order['status'] ?? 'Pending';
            $amount = $order['total'] ?? 0;
            $date = Carbon::parse($order['created_at'])->format('Y-m-d');

            if ($status === 'Paid') {
                $paidOrders++;
                $totalSales += $amount;
            } else {
                $pendingOrders++;
            }

            $ordersPerDay[$date] = ($ordersPerDay[$date] ?? 0) + 1;
        }

        // Generate date labels
        $labels = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        while ($current->lte($end)) {
            $dateStr = $current->format('Y-m-d');
            $labels[] = $dateStr;
            $ordersPerDay[$dateStr] = $ordersPerDay[$dateStr] ?? 0; // fill missing dates
            $current->addDay();
        }

        // Sort date ascending
        ksort($ordersPerDay);

        return view('admin.dashboard', [
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'totalCustomers' => $totalCustomers,
            'paidOrders' => $paidOrders,
            'pendingOrders' => $pendingOrders,
            'ordersPerDay' => array_values($ordersPerDay),
            'last7Days' => array_keys($ordersPerDay),
        ]);
    }

    public function editProfile()
    {
        $user = session('user_data');
        return view('admin.edit-profile', compact('user'));
    }

}
