<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Jumper!') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-gray-900 border-b border-gray-800 shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-emerald-400">
                {{ config('app.name', 'Jumper!') }}
            </h1>

            <div class="space-x-6 text-gray-300 flex items-center">
                <a href="#" class="hover:text-emerald-400">Courses</a>
                <a href="#" class="hover:text-emerald-400">Teachers</a>
                <a href="#" class="hover:text-emerald-400">About Us</a>
                <a href="#" class="hover:text-emerald-400">Contact</a>

                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-4 py-2 rounded-lg transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="border border-emerald-500 hover:bg-emerald-600 hover:text-white text-emerald-400 px-4 py-2 rounded-lg transition">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="flex-1 flex flex-col justify-center items-center text-center px-6 py-24 bg-gradient-to-b from-gray-900 to-gray-950">
        <h2 class="text-5xl font-extrabold mb-6 text-emerald-400 tracking-tight">
            Speak English with Confidence
        </h2>
        <p class="text-gray-400 text-lg max-w-2xl mb-8">
            Join thousands of students improving their fluency through interactive lessons, live teachers, and fun learning experiences.
        </p>
        <div class="flex gap-4">
            <a href="#" class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition">
                Get Started
            </a>
            <a href="#" class="border border-gray-600 hover:border-emerald-400 hover:text-emerald-400 px-6 py-3 rounded-xl transition">
                View Courses
            </a>
        </div>
    </section>

    <!-- Featured Courses -->
    <section class="py-16 bg-gray-900 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h3 class="text-3xl font-bold mb-8 text-white">Popular English Courses</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-emerald-600/20 transition">
                    <h4 class="text-xl font-semibold text-emerald-400 mb-2">Beginner English</h4>
                    <p class="text-gray-400 mb-4">Start from zero. Learn to speak, read, and write basic English confidently.</p>
                    <a href="#" class="text-emerald-400 hover:underline">View Details →</a>
                </div>
                <div class="bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-emerald-600/20 transition">
                    <h4 class="text-xl font-semibold text-emerald-400 mb-2">Business English</h4>
                    <p class="text-gray-400 mb-4">Boost your career with lessons focused on meetings, presentations, and communication.</p>
                    <a href="#" class="text-emerald-400 hover:underline">View Details →</a>
                </div>
                <div class="bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-emerald-600/20 transition">
                    <h4 class="text-xl font-semibold text-emerald-400 mb-2">Exam Preparation (IELTS/TOEFL)</h4>
                    <p class="text-gray-400 mb-4">Ace your tests with grammar, vocabulary, and mock speaking sessions.</p>
                    <a href="#" class="text-emerald-400 hover:underline">View Details →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Teachers Section -->
    <section class="py-16 bg-gray-950 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h3 class="text-3xl font-bold mb-8 text-white">Meet Our Teachers</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- You can dynamically load teacher data later -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-950 border-t border-gray-800 py-6 text-center text-gray-500">
        <p>© {{ date('Y') }} {{ config('app.name', 'Jumper!') }}. All rights reserved.</p>
    </footer>

</body>
</html>
