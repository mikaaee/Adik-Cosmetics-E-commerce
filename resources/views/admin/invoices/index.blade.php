@extends('layouts.admin')

@section('title', 'Invoices')

@section('content')
    <div class="orders-page">
        <h1 style="text-align: center; margin-bottom: 30px;">Invoices List</h1>

        <form method="GET" action="{{ route('admin.invoices.index') }}" class="filter-form" style="margin-bottom: 30px; text-align: center;">
            <input type="text" name="search" placeholder="Search invoice..." value="{{ request('search') }}" style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
            <input type="date" name="date" value="{{ request('date') }}" style="padding: 8px; border-radius: 5px; border: 1px solid #ccc; margin-left: 10px;">
            <button type="submit" class="btn btn-primary btn-sm" style="margin-left: 10px;">Filter</button>
        </form>

        @if ($files->count())
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Invoice Name</th>
                        <th>Date</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($files as $file)
                        <tr>
                            <td>{{ $file['name'] }}</td>
                            <td>{{ $file['date'] }}</td>
                            <td>
                                <a href="{{ $file['path'] }}" class="btn btn-sm btn-success" download>
                                    <i class="fa fa-download"></i> PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align:center;">No invoices found for the selected filter.</p>
        @endif
    </div>

    <style>
        .orders-page .custom-table {
            width: 100%;
            max-width: 960px;
            margin: 20px auto;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .orders-page .custom-table th,
        .orders-page .custom-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .orders-page .custom-table thead {
            background-color: #343a40;
            color: #ffffff;
        }

        .orders-page .custom-table tbody tr {
            background-color: #f8f9fa;
            transition: background-color 0.3s ease;
        }

        .orders-page .custom-table tbody tr:nth-child(even) {
            background-color: #e9ecef;
        }

        .orders-page .custom-table tbody tr:hover {
            background-color: #e2e6ea;
        }

        .orders-page {
            padding: 0 20px;
            max-width: 1000px;
            margin: auto;
        }
    </style>
@endsection
