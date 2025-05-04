@extends('layouts.admin')

@section('title', 'Invoice List')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<div class="container">
    <h2>Invoice List</h2>

    @if (count($invoices) > 0)
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Invoice No</th>
                    <th>User ID</th>
                    <th>Total (RM)</th>
                    <th>Created At</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $index => $invoice)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $invoice['invoice_no'] }}</td>
                        <td>{{ $invoice['user_id'] }}</td>
                        <td>RM {{ number_format($invoice['total'], 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice['created_at'])->format('d M Y, h:i A') }}</td>
                        <td>
                            <a href="{{ route('admin.invoices.download', basename($invoice['file_path'])) }}"
                               class="btn-download">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align:center; margin-top: 20px;">No invoices found.</p>
    @endif
</div>

<style>
    .container {
        max-width: 1100px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .container h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #7c3d4f;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-table th, .invoice-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #eee;
        text-align: center;
    }

    .invoice-table thead {
        background-color: #9e5866;
        color: white;
    }

    .btn-download {
        background-color: #dc3545;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-download:hover {
        background-color: #bb2d3b;
    }
</style>
@endsection
