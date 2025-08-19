<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Welcome Admin!</h1>

    @auth
    <form method="POST" action="{{ route('logout') }}" id="logout-form">
        @csrf
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
            Logout {{ auth()->user()->name ?? '' }}
        </button>
    </form>
    @endauth
</div>

{{-- Success message --}}
@if(session('success'))
<p class="text-green-600 mb-4">{{ session('success') }}</p>
@endif

{{-- Add Item Button --}}
<div class="flex justify-end mb-4 gap-2">
    <button id="add-item-btn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Add Item
    </button>

    {{-- View Sales Report Button --}}
    <a href="{{ route('admin.salesReport') }}" 
       class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
        View Sales Report
    </a>
</div>

{{-- Items Table --}}
<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded shadow overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left">ID</th>
                <th class="py-2 px-4 text-left">Name</th>
                <th class="py-2 px-4 text-left">Price (₱)</th>
                <th class="py-2 px-4 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-2 px-4">{{ $item->id }}</td>
                <td class="py-2 px-4">{{ $item->name }}</td>
                <td class="py-2 px-4">{{ number_format($item->price, 2) }}</td>
                <td class="py-2 px-4 flex gap-2">
                    {{-- Edit button --}}
                    <button type="button" 
                        class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 edit-btn"
                        data-id="{{ $item->id }}"
                        data-name="{{ $item->name }}"
                        data-price="{{ $item->price }}">
                        Edit
                    </button>

                    {{-- Delete button --}}
                    <form method="POST" action="{{ url('/items/'.$item->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Logout & Delete Confirmation Modal -->
<div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow max-w-sm w-full">
        <h2 class="text-xl font-bold mb-4" id="modal-title">Confirm Action</h2>
        <p id="modal-message" class="mb-4">Are you sure?</p>
        <div class="flex justify-end gap-2">
            <button id="modal-cancel" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="modal-confirm" class="px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600">Confirm</button>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow max-w-sm w-full">
        <h2 class="text-xl font-bold mb-4" id="edit-modal-title">Edit Item</h2>
        <form id="edit-form" method="POST">
            @csrf
            @method('PUT')
            <input type="text" name="name" id="edit-name" placeholder="Item Name" required class="border rounded px-3 py-2 w-full mb-2">
            <input type="number" step="0.01" name="price" id="edit-price" placeholder="Price" required class="border rounded px-3 py-2 w-full mb-4">
            <div class="flex justify-end gap-2">
                <button type="button" id="edit-cancel" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Item Modal -->
<div id="add-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow max-w-sm w-full">
        <h2 class="text-xl font-bold mb-4">Add New Item</h2>
        <form id="add-form" method="POST" action="{{ url('/items') }}">
            @csrf
            <input type="text" name="name" placeholder="Item Name" required class="border rounded px-3 py-2 w-full mb-2">
            <input type="number" step="0.01" name="price" placeholder="Price" required class="border rounded px-3 py-2 w-full mb-4">
            <div class="flex justify-end gap-2">
                <button type="button" id="add-cancel" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">Add</button>
            </div>
        </form>
    </div>
</div>
@vite('resources/js/admin.js')
</body>
</html>
