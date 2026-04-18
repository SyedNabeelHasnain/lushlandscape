<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Super WMS</title>
    @vite(['resources/css/admin.css'])
</head>
<body class="bg-cream min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-forest rounded-2xl mb-4">
                <span class="text-white text-2xl font-bold">L</span>
            </div>
            <h1 class="text-2xl font-bold text-forest-dark">Super WMS</h1>
            <p class="text-text-secondary mt-1">Admin Panel</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-8">
            @if(request()->query('cleared'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
                    <svg class="w-4 h-4 text-green-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <p class="text-green-800 text-sm">All caches cleared successfully. Please sign in again.</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-red-700 text-sm">{{ $errors->first() }}</p>
                </div>
            @endif
            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-text mb-2">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest focus:border-transparent transition">
                </div>
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-text mb-2">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest focus:border-transparent transition">
                </div>
                <div class="mb-6 flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-gray-300 text-forest focus:ring-forest">
                    <label for="remember" class="ml-2 text-sm text-text-secondary">Remember me</label>
                </div>
                <button type="submit" class="w-full bg-forest hover:bg-forest-light text-white font-medium py-3 px-4 rounded-xl transition duration-200">
                    Sign In
                </button>
            </form>
        </div>
    </div>
</body>
</html>
