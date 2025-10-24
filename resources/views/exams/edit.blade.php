<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Exam
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow text-gray-900 dark:text-gray-100">

                <form id="examForm" class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium">Title</label>
                        <input type="text" id="title" value="{{ $exam->title }}" class="w-full p-2 border rounded dark:bg-gray-900 dark:border-gray-700" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea id="description" class="w-full p-2 border rounded dark:bg-gray-900 dark:border-gray-700">{{ $exam->description }}</textarea>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Exam</button>
                    <a href="{{ route('examPage') }}" class="ml-2 text-gray-600 dark:text-gray-300 hover:underline">Cancel</a>
                </form>

                <h3 class="text-lg font-semibold mb-3">Questions</h3>
                <ul id="questionList" class="space-y-2">
                    @foreach ($questions as $q)
                        <li class="border p-3 bg-gray-50 dark:bg-gray-900 dark:border-gray-700 rounded flex items-center justify-between cursor-move" data-id="{{ $q->id }}">
                            <div>
                                <span class="font-medium">{{ $q->text }}</span>
                            </div>
                            <div class="flex gap-2">
                                <button 
                                    class="text-blue-500 hover:underline edit-question-btn" 
                                    data-id="{{ $q->id }}" 
                                    data-text="{{ $q->text }}">
                                    Edit
                                </button>
                                <button class="text-red-500 hover:underline" onclick="deleteQuestion({{ $q->id }})">Delete</button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <!-- Question Edit Modal -->
    <div id="questionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-lg p-6 relative">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Edit Question</h2>

            <form id="questionForm" class="space-y-4">
                <input type="hidden" id="questionId">

                <div>
                    <label class="block text-sm font-medium">Question Text</label>
                    <textarea id="questionText" class="w-full p-2 border rounded dark:bg-gray-900 dark:border-gray-700" required></textarea>
                </div>

                <div id="optionsContainer" class="space-y-2">
                    <!-- Options will be injected here -->
                </div>

                <button type="button" id="addOptionBtn" class="text-sm text-blue-600 hover:underline">+ Add Option</button>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        // Fetch base URLs from Laravel
        const updateExamUrl = "{{ route('exams.update', $exam->id) }}";
        const reorderUrl = "{{ route('exams.show', $exam->id) }}/reorder";

        // Update exam details
        document.getElementById('examForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const data = {
                title: document.getElementById('title').value,
                description: document.getElementById('description').value,
            };

            await fetch(updateExamUrl, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            alert('Exam updated successfully!');
        });

        // Make question list sortable
        const questionList = document.getElementById('questionList');
        Sortable.create(questionList, {
            animation: 150,
            onEnd: async () => {
                const order = Array.from(questionList.children).map((li, index) => ({
                    id: li.dataset.id,
                    order: index + 1
                }));

                await fetch("{{ route('api.questions.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ order })
                });
            }
        });

        async function deleteQuestion(id) {
            if (!confirm('Delete this question?')) return;
            await fetch(`{{ url('api/questions') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            location.reload();
        }
    </script>
    <script>
        const modal = document.getElementById('questionModal');
        const closeModalBtn = document.getElementById('closeModal');
        const questionForm = document.getElementById('questionForm');
        const optionsContainer = document.getElementById('optionsContainer');
        const addOptionBtn = document.getElementById('addOptionBtn');

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // 🟦 Function to open the modal
        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // 🟥 Function to close the modal
        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            optionsContainer.innerHTML = '';
            questionForm.reset();
        }

        closeModalBtn.addEventListener('click', closeModal);

        // 🟧 Load question and its options from API
        document.querySelectorAll('.edit-question-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const questionId = btn.dataset.id;
                const baseEditUrl = `{{ route('questions.show', ':id') }}`;
                const url = baseEditUrl.replace(':id', questionId);
                openModal();

                const res = await fetch(url);
                const question = await res.json();

                document.getElementById('questionId').value = question.id;
                document.getElementById('questionText').value = question.text;

                // Populate options
                optionsContainer.innerHTML = '';
                question.options.forEach(option => {
                    // Determine if this option should be checked
                    const isChecked = option.text === question.correct_answer;
                    addOptionInput(option.text, isChecked, option.id, option.label);
                });
            });

        });

        // 🟩 Helper: Add option input row
        function addOptionInput(text = '', isCorrect = false, optionId = null, label = '') {
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-2 option-row';
            div.innerHTML = `
                <input type="hidden" class="option-id" value="${optionId || ''}">
                <input type="text" class="option-label p-2 border rounded dark:bg-gray-900 dark:border-gray-700" value="${label}">

                <input type="text" class="option-text w-full p-2 border rounded dark:bg-gray-900 dark:border-gray-700" value="${text}" placeholder="Option text">
                <label class="flex items-center space-x-1">
                    <input type="checkbox" class="option-correct" ${isCorrect ? 'checked' : ''}>
                    <span class="text-sm">Correct</span>
                </label>
                <button type="button" class="remove-option text-red-500">✕</button>
            `;
            div.querySelector('.remove-option').addEventListener('click', () => div.remove());
            optionsContainer.appendChild(div);
        }

        addOptionBtn.addEventListener('click', () => addOptionInput());

        // 🟨 Save question
        questionForm.addEventListener('submit', async e => {
            e.preventDefault();

            const questionId = document.getElementById('questionId').value;
            const text = document.getElementById('questionText').value;
            const options = Array.from(optionsContainer.children).map(opt => ({
                id: opt.querySelector('.option-id').value || null,
                text: opt.querySelector('.option-text').value,
                label: opt.querySelector('.option-label')?.value || '', // include label
                is_correct: opt.querySelector('.option-correct').checked
            }));

            // Extract correct_answer
            const correct_answer = options.find(o => o.is_correct)?.text || '';

            const baseEditUrl = `{{ route('questions.update', ':id') }}`;
            const url = baseEditUrl.replace(':id', questionId);

            await fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ text, options, correct_answer })
            });

            closeModal();
        });
    </script>

</x-app-layout>
