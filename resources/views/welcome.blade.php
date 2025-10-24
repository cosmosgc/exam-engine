<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel Academy') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-gray-900 border-b border-gray-800 shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-emerald-400">Laravel Academy</h1>
            <div class="space-x-6 text-gray-300">
                <a href="#" class="hover:text-emerald-400">Courses</a>
                <a href="#" class="hover:text-emerald-400">Instructors</a>
                <a href="#" class="hover:text-emerald-400">About</a>
                <a href="#" class="hover:text-emerald-400">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="flex-1 flex flex-col justify-center items-center text-center px-6 py-24 bg-gradient-to-b from-gray-900 to-gray-950">
        <h2 class="text-5xl font-extrabold mb-6 text-emerald-400 tracking-tight">
            Learn. Build. Grow.
        </h2>
        <p class="text-gray-400 text-lg max-w-2xl mb-8">
            Master Laravel, PHP, and modern web development with interactive lessons, real projects, and expert mentorship.
        </p>
        <div class="flex gap-4">
            <a href="#" class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition">
                Start Learning
            </a>
            <a href="#" class="border border-gray-600 hover:border-emerald-400 hover:text-emerald-400 px-6 py-3 rounded-xl transition">
                Browse Courses
            </a>
        </div>
    </section>

    <!-- Courses Section -->
    <section class="py-16 bg-gray-900 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h3 class="text-3xl font-bold mb-8 text-white">Popular Courses</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-emerald-600/20 transition">
                    <h4 class="text-xl font-semibold text-emerald-400 mb-2">Laravel for Beginners</h4>
                    <p class="text-gray-400 mb-4">Start from scratch and learn to build modern apps with Laravel.</p>
                    <a href="#" class="text-emerald-400 hover:underline">View Course →</a>
                </div>
                <div class="bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-emerald-600/20 transition">
                    <h4 class="text-xl font-semibold text-emerald-400 mb-2">Advanced Eloquent</h4>
                    <p class="text-gray-400 mb-4">Master Laravel’s ORM with relationships, scopes, and advanced queries.</p>
                    <a href="#" class="text-emerald-400 hover:underline">View Course →</a>
                </div>
                <div class="bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-emerald-600/20 transition">
                    <h4 class="text-xl font-semibold text-emerald-400 mb-2">Fullstack with Inertia & Vue</h4>
                    <p class="text-gray-400 mb-4">Build dynamic apps using Laravel, Vue, and Inertia.js.</p>
                    <a href="#" class="text-emerald-400 hover:underline">View Course →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-950 border-t border-gray-800 py-6 text-center text-gray-500">
        <p>© {{ date('Y') }} Laravel Academy. All rights reserved.</p>
    </footer>

</body>
</html>
