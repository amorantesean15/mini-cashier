<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Your Cart</h1>

    @auth
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
            Logout {{ auth()->user()->name ?? '' }}
        </button>
    </form>
    @endauth
</div>

@if(session('success'))
    <p class="text-green-600 mb-4">{{ session('success') }}</p>
@endif

{{-- Desktop Table --}}
<div class="flex flex-col sm:flex-row gap-6">

    <!-- Left: Items Table (8/12) -->
    <div class="sm:w-8/12">
        {{-- Desktop Table --}}
        <div class="hidden sm:block overflow-x-auto mb-4">
            <table class="min-w-full bg-white rounded shadow overflow-hidden">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4 text-left">Item</th>
                        <th class="py-2 px-4 text-left">Price (₱)</th>
                        <th class="py-2 px-4 text-left">Quantity</th>
                        <th class="py-2 px-4 text-left">Subtotal (₱)</th>
                        <th class="py-2 px-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
    @foreach($cart as $c)
    <tr class="border-b hover:bg-gray-50">
        <td class="py-2 px-4">{{ $c->item->name }}</td>
        <td class="py-2 px-4">{{ number_format($c->item->price, 2) }}</td>
        <td class="py-2 px-4">
            <input type="number" name="quantity" value="{{ $c->quantity }}" class="w-16 border rounded px-2 py-1" readonly>
        </td>
        <td class="py-2 px-4">{{ number_format($c->item->price * $c->quantity, 2) }}</td>
        <td class="py-2 px-4 flex gap-2">
            <!-- Update button form -->
            <form method="POST" action="{{ route('cart.update', $c->item->id) }}" class="flex">
                @csrf
                <input type="hidden" name="quantity" value="{{ $c->quantity }}">
                <button type="button"
            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 edit-btn"
            data-id="{{ $c->item->id }}"
            data-quantity="{{ $c->quantity }}">
        Edit
    </button>
            </form>

            <!-- Delete button form -->
            <form method="POST" action="{{ route('cart.delete', $c->item->id) }}"  class="flex">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-btn bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</tbody>

                <tfoot>
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="3" class="py-2 px-4 text-right">Total:</td>
                        <td class="py-2 px-4" id="total-amount">
                            ₱{{ number_format($cart->sum(fn($c) => $c->item->price * $c->quantity), 2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="sm:hidden flex flex-col gap-4 mb-4">
            @foreach($cart as $c)
            <div class="bg-white p-4 rounded shadow flex flex-col gap-2">
                <p><span class="font-semibold">Item:</span> {{ $c->item->name }}</p>
                <p><span class="font-semibold">Price:</span> ₱{{ number_format($c->item->price, 2) }}</p>
                <form method="POST" action="{{ route('cart.update', $c->item->id) }}" class="flex gap-2 items-center">
                    @csrf
                    <label class="font-semibold">Quantity:</label>
                    <input type="number" name="quantity" min="1" value="{{ $c->quantity }}" class="w-16 border rounded px-2 py-1">
                    <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Update</button>
                </form>
                <p><span class="font-semibold">Subtotal:</span> ₱{{ number_format($c->item->price * $c->quantity, 2) }}</p>
                <form method="POST" action="{{ route('cart.delete', $c->item->id) }}" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 w-full">Delete</button>
                </form>
            </div>
            @endforeach
            <div class="bg-white p-4 rounded shadow font-bold text-right">
                Total: ₱{{ number_format($cart->sum(fn($c) => $c->item->price * $c->quantity), 2) }}
            </div>
        </div>
    </div>

    <!-- Right: Payment Form (4/12) -->
    <div class="sm:w-4/12">
        <!-- Checkout Form -->
        <form method="POST" action="/checkout" class="bg-white p-4 rounded shadow"
              id="checkout-form"
              data-total="{{ $cart->sum(fn($c) => $c->item->price * $c->quantity) }}">
            @csrf
            <label class="block mb-2 font-semibold">Payment Method:</label>
            <select name="payment_method" id="payment-method" class="border rounded px-3 py-2 w-full mb-4">
                <option value="cash">Cash</option>
                <option value="qr">QR Payment</option>
            </select>

            <!-- Cash Amount Input -->
            <div id="cash-section" class="mb-4">
                <label class="block mb-2 font-semibold">Payment Received:</label>
                <input type="number" min="0" step="0.01" id="cash-received" name="cash_received"
                       class="border rounded px-3 py-2 w-full mb-2" placeholder="Enter cash amount">
                <p class="font-semibold text-gray-700">Change: ₱<span id="change-amount">0.00</span></p>
            </div>

            <!-- QR Code Section -->
            <div id="qr-section" class="mb-4 hidden text-center">
                <p class="font-semibold mb-2">Scan to Pay:</p>
                <img id="qr-code" src="" alt="QR Code" class="mx-auto w-48 h-48 border p-2 rounded bg-white">
            </div>

            <button type="button" id="checkout-btn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full">
                Checkout
            </button>
        </form>
        <a href="/" class="inline-block mt-4 text-blue-600 hover:underline">Back to Items</a>
    </div>

</div> 
<!-- Confirmation Modal -->
<div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded shadow max-w-sm w-full">
        <h2 class="text-xl font-bold mb-4">Confirm Payment</h2>
        <p>Total: ₱<span id="modal-total"></span></p>
        <p id="modal-cash-label">Payment Received: ₱<span id="modal-cash"></span></p>
        <p>Change: ₱<span id="modal-change"></span></p>
        <div class="mt-4 flex justify-end gap-2">
            <button id="cancel-btn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirm-btn" class="px-4 py-2 rounded bg-green-500 text-white hover:bg-green-600">Confirm</button>
        </div>
    </div>
</div>
<!-- Action Confirmation Modal -->
<div id="action-confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow max-w-sm w-full">
        <h2 class="text-xl font-bold mb-4" id="modal-action-title">Confirm Action</h2>
        <p id="modal-action-message">Are you sure you want to proceed?</p>
        <div class="mt-4 flex justify-end gap-2">
            <button id="modal-cancel-btn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="modal-confirm-btn" class="px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600">Confirm</button>
        </div>
    </div>
</div>
<!-- Edit Quantity Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow max-w-sm w-full">
        <h2 class="text-xl font-bold mb-4">Edit Quantity</h2>
        <form id="edit-form" method="POST">
            @csrf
            <label class="block mb-2 font-semibold">Quantity:</label>
            <input type="number" name="quantity" id="edit-quantity" min="1" required
                   class="border rounded px-3 py-2 w-full mb-4">
            <div class="flex justify-end gap-2">
                <button type="button" id="edit-cancel" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-500 text-white hover:bg-yellow-600">Confirm</button>
            </div>
        </form>
    </div>
</div>

@vite('resources/js/cart.js')
</body>
</html>
