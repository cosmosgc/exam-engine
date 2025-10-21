<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create Exam
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow text-gray-900 dark:text-gray-100">
                <form id="examForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Title</label>
                        <input type="text" id="title" class="w-full p-2 border rounded dark:bg-gray-900 dark:border-gray-700" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea id="description" class="w-full p-2 border rounded dark:bg-gray-900 dark:border-gray-700"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
                    <a href="{{ route('exams.index') }}" class="ml-2 text-gray-600 dark:text-gray-300 hover:underline">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('examForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const data = {
                title: document.getElementById('title').value,
                description: document.getElementById('description').value
            };

            await fetch(`{{ route('exams.store') }}` , {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            window.location.href = `{{ route('examPage') }}`;
        });
    </script>
</x-app-layout>
