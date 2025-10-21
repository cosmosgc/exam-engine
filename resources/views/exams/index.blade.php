<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Exams
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold dark:text-gray-100">Exam List</h3>
                <a href="{{ route('exams.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ New Exam</a>
            </div>

            <div id="exam-list" class="bg-white dark:bg-gray-800 p-6 rounded shadow text-gray-900 dark:text-gray-100">
                Loading exams...
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const list = document.getElementById('exam-list');
            try {
                const res = await fetch('api/exams');
                const exams = await res.json();

                if (exams.length === 0) {
                    list.innerHTML = '<p>No exams found.</p>';
                    return;
                }

                list.innerHTML = `
                    <ul>
                        ${exams.map(exam => `
                            <li class="py-2 border-b border-gray-300 dark:border-gray-700 flex justify-between">
                                <span>${exam.title}</span>
                                <span class="flex gap-2">
                                    <a href="exams/${exam.id}" class="text-blue-500 hover:underline">View</a>
                                    <a href="exams/${exam.id}/edit" class="text-yellow-500 hover:underline">Edit</a>
                                    <button class="text-red-500 hover:underline" onclick="deleteExam(${exam.id})">Delete</button>
                                </span>
                            </li>
                        `).join('')}
                    </ul>
                `;
            } catch (e) {
                list.innerHTML = `<p class="text-red-500">Failed to load exams.</p>`;
            }
        });

        async function deleteExam(id) {
            if (!confirm('Are you sure you want to delete this exam?')) return;

            await fetch(`api/exams/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            location.reload();
        }
    </script>
</x-app-layout>
