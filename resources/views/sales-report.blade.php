<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sales Report</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-4">Sales Report</h1>

<table class="min-w-full bg-white rounded shadow overflow-hidden">
    <thead class="bg-gray-200">
        <tr>
            <th class="py-2 px-4 text-left">Item</th>
            <th class="py-2 px-4 text-left">Total Sold</th>
            <th class="py-2 px-4 text-left">Total Revenue (₱)</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalSold = 0;
            $totalRevenue = 0;
        @endphp
        @foreach($report as $r)
        @php
            $totalSold += $r->total_sold;
            $totalRevenue += $r->total_revenue;
        @endphp
        <tr class="border-b hover:bg-gray-50">
            <td class="py-2 px-4">{{ $r->item->name }}</td>
            <td class="py-2 px-4">{{ $r->total_sold }}</td>
            <td class="py-2 px-4">{{ number_format($r->total_revenue, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot class="bg-gray-100 font-bold">
        <tr>
            <td class="py-2 px-4 text-right">TOTAL:</td>
            <td class="py-2 px-4">{{ $totalSold }}</td>
            <td class="py-2 px-4">₱{{ number_format($totalRevenue, 2) }}</td>
        </tr>
    </tfoot>
</table>

<a href="{{ url('/admin') }}" class="inline-block mt-4 text-blue-600 hover:underline">Back to Dashboard</a>

</body>
</html>
