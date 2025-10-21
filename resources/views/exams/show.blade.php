<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Exam Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div id="exam" class="bg-white dark:bg-gray-800 p-6 rounded shadow text-gray-900 dark:text-gray-100">
                Loading exam details...
            </div>
        </div>
    </div>

    <script>
        const examId = window.location.pathname.split('/').pop();

        document.addEventListener('DOMContentLoaded', async () => {
            const res = await fetch(`{{ route('exams.show', $exam->id) }}`);
            const exam = await res.json();

            document.getElementById('exam').innerHTML = `
                <h3 class="text-2xl font-semibold mb-2">${exam.title}</h3>
                <p class="mb-4 text-gray-700 dark:text-gray-300">${exam.description || 'No description'}</p>
                <a href="{{ route('exams.edit', $exam->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Edit</a>
                <a href="{{ route('examPage') }}" class="ml-2 text-gray-600 dark:text-gray-300 hover:underline">Back</a>
            `;
        });
    </script>
</x-app-layout>
