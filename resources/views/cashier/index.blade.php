<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Cashier</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Available Items</h1>

        @auth
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                Logout {{ auth()->user()->name ?? '' }}
            </button>
        </form>
        @endauth
    </div>

    @if(session('success'))
        <p class="text-green-600 mb-4">{{ session('success') }}</p>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow overflow-hidden">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 text-left">ID</th>
                    <th class="py-2 px-4 text-left">Name</th>
                    <th class="py-2 px-4 text-left">Price (₱)</th>
                    <th class="py-2 px-4 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4">{{ $item->id }}</td>
                    <td class="py-2 px-4">{{ $item->name }}</td>
                    <td class="py-2 px-4">{{ number_format($item->price, 2) }}</td>
                    <td class="py-2 px-4">
                        <button 
                            class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 open-modal-btn" 
                            data-id="{{ $item->id }}" 
                            data-name="{{ $item->name }}"
                            data-action="/cart/{{ $item->id }}">
                            Add to Cart
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="/cart" class="inline-block mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Go to Cart</a>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow max-w-sm w-full">
            <h2 class="text-xl font-bold mb-4" id="modal-title">Add to Cart</h2>
            
            <form method="POST" id="modal-form">
                @csrf
                <p class="mb-2">Item: <span id="modal-item-name" class="font-semibold"></span></p>
                <label class="block mb-2">Quantity:</label>
                <input type="number" name="quantity" min="1" value="1" class="w-full border rounded px-2 py-1 mb-4" required>
                
                <div class="flex justify-end gap-2">
                    <button type="button" id="modal-cancel" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-green-500 text-white hover:bg-green-600">Add</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById("modal");
        const modalForm = document.getElementById("modal-form");
        const modalItemName = document.getElementById("modal-item-name");
        const cancelBtn = document.getElementById("modal-cancel");

        document.querySelectorAll(".open-modal-btn").forEach(button => {
            button.addEventListener("click", () => {
                const itemId = button.dataset.id;
                const itemName = button.dataset.name;
                const actionUrl = button.dataset.action;

                modalItemName.textContent = itemName;
                modalForm.action = actionUrl;
                modal.classList.remove("hidden");
                modal.classList.add("flex");
            });
        });

        cancelBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        });
    </script>
</body>
</html>
