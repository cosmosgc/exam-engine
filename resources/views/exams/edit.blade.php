<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Exam
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow text-gray-900 dark:text-gray-100">

                <!-- Exam Edit Form -->
                <form id="examPage" method="put" action="{{ route('exams.update', $exam->id) }}"class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium">Title</label>
                        <input type="text" id="title" value="{{ $exam->title }}" class="w-full p-2 border rounded dark:bg-gray-900 dark:border-gray-700" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <!-- Quill editor -->
                        <div id="examEditor" class="bg-white text-black rounded-md"></div>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Exam</button>
                    <a href="{{ route('examPage') }}" class="ml-2 text-gray-600 dark:text-gray-300 hover:underline">Cancel</a>
                </form>

                <!-- Questions Section -->
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-lg font-semibold">Questions</h3>
                    <button id="addQuestionBtn" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md shadow">
                        + Add Question
                    </button>
                </div>

                <ul id="questionList" class="space-y-2">
                    @foreach ($questions as $q)
                        <li class="border p-3 bg-gray-50 dark:bg-gray-900 dark:border-gray-700 rounded flex items-center justify-between cursor-move" data-id="{{ $q->id }}">
                            <div>
                                <span class="font-medium">{!! $q->text !!}</span>
                            </div>
                            <div class="flex gap-2">
                                <button 
                                    class="text-blue-500 hover:underline edit-question-btn" 
                                    data-id="{{ $q->id }}">
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

    <!-- Question Modal -->
    <div id="questionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl p-6 relative">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200" id="modalTitle">Edit Question</h2>

            <form id="questionForm" class="space-y-4">
                <input type="hidden" id="questionId">

                <div>
                    <label class="block text-sm font-medium mb-1">Question Text</label>
                    <div id="questionEditor" class="bg-white text-black rounded-md"></div>
                </div>

                <div id="optionsContainer" class="space-y-2"></div>

                <button type="button" id="addOptionBtn" class="text-sm text-blue-600 hover:underline">+ Add Option</button>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SortableJS + Quill -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;


        const questionList = document.getElementById('questionList');
        const modal = document.getElementById('questionModal');
        const closeModalBtn = document.getElementById('closeModal');
        const questionForm = document.getElementById('questionForm');
        const optionsContainer = document.getElementById('optionsContainer');
        const addOptionBtn = document.getElementById('addOptionBtn');
        const addQuestionBtn = document.getElementById('addQuestionBtn');

        // ---- Add Question ----
        addQuestionBtn.addEventListener('click', () => {
            document.getElementById('questionId').value = '';
            questionEditor.root.innerHTML = '';
            optionsContainer.innerHTML = '';
            document.getElementById('modalTitle').innerText = 'Add New Question';
            openModal();
        });

        // ---- Modal Control ----
        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            questionForm.reset();
            questionEditor.root.innerHTML = '';
        }
        closeModalBtn.addEventListener('click', closeModal);

        // ---- Sortable Questions ----
        Sortable.create(questionList, {
            animation: 150,
            onEnd: async () => {
                const order = Array.from(questionList.children).map((li, index) => ({
                    id: li.dataset.id,
                    order: index + 1
                }));
                await fetch("{{ route('api.questions.reorder') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ order })
                });
            }
        });

        // ---- Delete Question ----
        async function deleteQuestion(id) {
            if (!confirm('Delete this question?')) return;
            await fetch(`{{ url('api/questions') }}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            location.reload();
        }

        // ---- Edit Question ----
        document.querySelectorAll('.edit-question-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const questionId = btn.dataset.id;
                const url = `{{ route('questions.show', ':id') }}`.replace(':id', questionId);
                openModal();

                const res = await fetch(url);
                const question = await res.json();

                document.getElementById('questionId').value = question.id;
                questionEditor.root.innerHTML = question.text;
                document.getElementById('modalTitle').innerText = 'Edit Question';

                optionsContainer.innerHTML = '';
                question.options.forEach(option => {
                    addOptionInput(option.text, option.text === question.correct_answer, option.id, option.label);
                });
            });
        });

        // ---- Add Option ----
        function addOptionInput(text = '', isCorrect = false, optionId = null, label = '') {
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-2 option-row';
            div.innerHTML = `
                <input type="hidden" class="option-id" value="${optionId || ''}">
                <input type="text" class="option-label p-2 border rounded dark:bg-gray-900 dark:border-gray-700 w-16" value="${label}" placeholder="A">
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

        // ---- Save Question ----
        questionForm.addEventListener('submit', async e => {
            e.preventDefault();

            const questionId = document.getElementById('questionId').value;
            const text = questionEditor.root.innerHTML;
            const options = Array.from(optionsContainer.children).map(opt => ({
                id: opt.querySelector('.option-id').value || null,
                text: opt.querySelector('.option-text').value,
                label: opt.querySelector('.option-label').value || '',
                is_correct: opt.querySelector('.option-correct').checked
            }));
            const correct_answer = options.find(o => o.is_correct)?.text || '';

            const url = questionId
                ? `{{ route('questions.update', ':id') }}`.replace(':id', questionId)
                : `{{ route('questions.store') }}`;

            await fetch(url, {
                method: questionId ? 'PUT' : 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ text, options, correct_answer, exam_id: "{{ $exam->id }}" })
            });

            closeModal();
            location.reload();
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('examPage');
        const titleInput = document.getElementById('title');
        const editor = new Quill('#examEditor', {
            theme: 'snow',
            placeholder: 'Write a short description...',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    ['blockquote', 'code-block'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        // Load the existing content from the backend
        editor.root.innerHTML = @json($exam->description);

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const title = titleInput.value.trim();
            const description = editor.root.innerHTML.trim();

            if (!title) {
                alert('Title cannot be empty.');
                return;
            }

            // Create the request payload
            const payload = {
                title,
                description,
                _method: 'PUT', // Laravel expects this for PUT requests via fetch
                _token: '{{ csrf_token() }}'
            };

            try {
                const response = await fetch(form.action, {
                    method: 'POST', // must be POST because HTML doesn’t support PUT
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(errorText || 'Failed to update exam.');
                }

                const data = await response.json();

                // Success — give feedback or redirect
                // alert('Exam updated successfully!');
                // window.location.href = "{{ route('examPage') }}";
                location.reload();

            } catch (error) {
                console.error('Error updating exam:', error);
                alert('Something went wrong while updating the exam.');
            }
        });
    });
    </script>

</x-app-layout>
