<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Statistic Cards -->
            <div class="flex justify-center gap-6">

                <!-- Exams Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Exams</h3>
                        <p class="text-3xl font-semibold text-gray-800 dark:text-gray-100 mt-1">
                            {{ $examCount ?? 0 }}
                        </p>
                    </div>
                    <div class="bg-blue-500/10 p-3 rounded-full">
                        <i class="fa-solid fa-file-circle-check text-blue-500 text-xl"></i>
                    </div>
                </div>

                <!-- Questions Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Questions</h3>
                        <p class="text-3xl font-semibold text-gray-800 dark:text-gray-100 mt-1">
                            {{ $questionCount ?? 0 }}
                        </p>
                    </div>
                    <div class="bg-green-500/10 p-3 rounded-full">
                        <i class="fa-solid fa-circle-question text-green-500 text-xl"></i>
                    </div>
                </div>

                <!-- Students Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Students</h3>
                        <p class="text-3xl font-semibold text-gray-800 dark:text-gray-100 mt-1">
                            {{ $studentCount ?? 0 }}
                        </p>
                    </div>
                    <div class="bg-yellow-500/10 p-3 rounded-full">
                        <i class="fa-solid fa-user-graduate text-yellow-500 text-xl"></i>
                    </div>
                </div>

                <!-- Reports Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Reports</h3>
                        <p class="text-3xl font-semibold text-gray-800 dark:text-gray-100 mt-1">
                            {{ $reportCount ?? 0 }}
                        </p>
                    </div>
                    <div class="bg-purple-500/10 p-3 rounded-full">
                        <i class="fa-solid fa-chart-line text-purple-500 text-xl"></i>
                    </div>
                </div>

            </div>

            <!-- Welcome Box -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
