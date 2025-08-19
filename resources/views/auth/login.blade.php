<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login</h2>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
            @csrf
            <input type="email" name="email" placeholder="Email" required
                class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="password" name="password" placeholder="Password" required
                class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" 
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Login</button>
        </form>

    </div>

</body>
</html>
