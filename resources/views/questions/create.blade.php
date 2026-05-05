@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-white">{{ isset($question) ? 'Edit Question' : 'Add New Question' }}</h1>
            <p class="mt-2 text-sm text-gray-400">For Quiz: <span class="font-semibold text-indigo-400">{{ $quiz->title }}</span></p>
        </div>
        <a href="{{ route('quizzes.manage', $quiz) }}" class="text-indigo-400 hover:text-indigo-300 font-medium">&larr; Back to Manage Quiz</a>
    </div>

    <div class="bg-gray-800 shadow-lg overflow-hidden sm:rounded-xl border border-gray-700">
        <div class="p-6">
            <form action="{{ isset($question) ? route('questions.update', $question) : route('questions.store', $quiz) }}" method="POST" enctype="multipart/form-data" id="question-form">
                @csrf
                @if(isset($question))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- Question Type -->
                    <div class="sm:col-span-2">
                        <label for="type" class="block text-sm font-medium text-gray-300">Question Type</label>
                        <select id="type" name="type" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base bg-gray-700 border-gray-600 text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @php $selectedType = old('type', $question->type ?? 'multiple_choice'); @endphp
                            <option value="binary" {{ $selectedType === 'binary' ? 'selected' : '' }}>Binary (True/False)</option>
                            <option value="single_choice" {{ $selectedType === 'single_choice' ? 'selected' : '' }}>Single Choice</option>
                            <option value="multiple_choice" {{ $selectedType === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="number_input" {{ $selectedType === 'number_input' ? 'selected' : '' }}>Number Input</option>
                            <option value="text_input" {{ $selectedType === 'text_input' ? 'selected' : '' }}>Text Input</option>
                        </select>
                    </div>

                    <!-- Marks -->
                    <div class="sm:col-span-2">
                        <label for="marks" class="block text-sm font-medium text-gray-300">Marks</label>
                        <input type="number" step="0.5" min="0.5" name="marks" id="marks" value="{{ old('marks', $question->marks ?? 1) }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm bg-gray-700 border-gray-600 text-white sm:text-sm rounded-md">
                    </div>

                    <!-- Difficulty -->
                    <div class="sm:col-span-2">
                        <label for="difficulty" class="block text-sm font-medium text-gray-300">Difficulty</label>
                        <select id="difficulty" name="difficulty" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base bg-gray-700 border-gray-600 text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @php $selectedDiff = old('difficulty', $question->difficulty ?? 'medium'); @endphp
                            <option value="easy" {{ $selectedDiff === 'easy' ? 'selected' : '' }}>Easy</option>
                            <option value="medium" {{ $selectedDiff === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="hard" {{ $selectedDiff === 'hard' ? 'selected' : '' }}>Hard</option>
                        </select>
                    </div>

                    <!-- Question Text (Rich Text) -->
                    <div class="sm:col-span-6">
                        <label for="text" class="block text-sm font-medium text-gray-300 mb-2">Question Text (Supports Rich Text/HTML)</label>
                        <input type="hidden" name="text" id="text_hidden" value="{{ old('text', $question->text ?? '') }}">
                        <!-- Fixed height forces Quill to scroll if text is too long -->
                        <div id="editor-container" class="bg-gray-700 text-white rounded-b-md" style="height: 150px;">{!! old('text', $question->text ?? '') !!}</div>
                        @error('text') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="sm:col-span-3">
                        <label for="image_path" class="block text-sm font-medium text-gray-300">Attach Image (Optional)</label>
                        <input type="file" name="image_path" id="image_path" accept="image/*" class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-900 file:text-indigo-300 hover:file:bg-indigo-800 transition-colors">
                        @if(isset($question) && $question->image_path)
                            <p class="mt-2 text-sm text-gray-400">Current: <a href="{{ asset('storage/' . $question->image_path) }}" target="_blank" class="text-indigo-400 underline">View Image</a></p>
                        @endif
                    </div>

                    <!-- Video URL -->
                    <div class="sm:col-span-3">
                        <label for="video_url" class="block text-sm font-medium text-gray-300">YouTube Video URL (Optional)</label>
                        <input type="url" name="video_url" id="video_url" value="{{ old('video_url', $question->video_url ?? '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm bg-gray-700 border-gray-600 text-white sm:text-sm rounded-md" placeholder="https://youtube.com/embed/...">
                    </div>

                    <!-- Explanation -->
                    <div class="sm:col-span-6">
                        <label for="explanation" class="block text-sm font-medium text-gray-300">Explanation (Optional)</label>
                        <p class="text-xs text-gray-500 mb-1">Shown to the user in Review Mode after they complete the quiz.</p>
                        <textarea name="explanation" id="explanation" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm bg-gray-700 border-gray-600 text-white sm:text-sm rounded-md">{{ old('explanation', $question->explanation ?? '') }}</textarea>
                    </div>
                </div>

                <div class="mt-10 border-t border-gray-700 pt-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-white">Options / Answers</h3>
                        <button type="button" id="add-option-btn" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none transition-colors">
                            + Add Option
                        </button>
                    </div>
                    <p class="text-sm text-gray-400 mb-6">
                        Define the possible answers. For Text/Number Input, add ONE option with the correct expected answer. For Multiple Choice, check the boxes for all correct answers.
                    </p>

                    <div id="options-container" class="space-y-4">
                        @php
                            $options = isset($question) ? $question->options : (old('options') ?? []);
                        @endphp
                        
                        @forelse($options as $index => $option)
                            <div class="option-row flex items-start space-x-4 bg-gray-700/50 p-4 rounded-md border border-gray-600 relative">
                                @if(isset($option->id))
                                    <input type="hidden" name="options[{{ $index }}][id]" value="{{ $option->id }}">
                                @endif
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-300">Option Text</label>
                                    <input type="text" name="options[{{ $index }}][text]" value="{{ is_array($option) ? ($option['text'] ?? '') : $option->text }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm bg-gray-800 border-gray-600 text-white sm:text-sm rounded-md">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-300">Option Image (Optional)</label>
                                    <input type="file" name="options[{{ $index }}][image_path]" accept="image/*" class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-900 file:text-indigo-300 hover:file:bg-indigo-800">
                                    @if(!is_array($option) && $option->image_path)
                                        <p class="mt-1 text-xs text-indigo-400"><a href="{{ asset('storage/' . $option->image_path) }}" target="_blank">View current image</a></p>
                                    @endif
                                </div>
                                <div class="pt-6">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="options[{{ $index }}][is_correct]" value="1" {{ (is_array($option) ? ($option['is_correct'] ?? false) : $option->is_correct) ? 'checked' : '' }} class="rounded border-gray-500 bg-gray-800 text-green-500 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-green-400 font-bold">Is Correct?</span>
                                    </label>
                                </div>
                                <button type="button" class="remove-option-btn text-red-400 hover:text-red-300 absolute top-2 right-2">
                                    &times;
                                </button>
                            </div>
                        @empty
                            <!-- Initial empty row if no options exist -->
                            <div class="option-row flex items-start space-x-4 bg-gray-700/50 p-4 rounded-md border border-gray-600 relative">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-300">Option Text</label>
                                    <input type="text" name="options[0][text]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm bg-gray-800 border-gray-600 text-white sm:text-sm rounded-md">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-300">Option Image (Optional)</label>
                                    <input type="file" name="options[0][image_path]" accept="image/*" class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-900 file:text-indigo-300 hover:file:bg-indigo-800">
                                </div>
                                <div class="pt-6">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="options[0][is_correct]" value="1" class="rounded border-gray-500 bg-gray-800 text-green-500 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-green-400 font-bold">Is Correct?</span>
                                    </label>
                                </div>
                                <button type="button" class="remove-option-btn text-red-400 hover:text-red-300 absolute top-2 right-2">
                                    &times;
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        {{ isset($question) ? 'Update Question' : 'Save Question' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quill JS for Rich Text -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Quill
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'color': [] }, { 'background': [] }],
                    ['clean']
                ]
            }
        });

        // Load existing content
        var textInput = document.getElementById('text_hidden');
        if (textInput.value) {
            quill.clipboard.dangerouslyPasteHTML(textInput.value);
        }

        // Sync Quill content to hidden input on form submit
        var form = document.getElementById('question-form');
        form.addEventListener('submit', function(e) {
            textInput.value = quill.root.innerHTML;
            
            // Validation: Ensure at least one option is marked as correct
            var checkboxes = document.querySelectorAll('.option-row input[type="checkbox"][name$="[is_correct]"]');
            var hasCorrect = false;
            checkboxes.forEach(function(cb) {
                if (cb.checked) hasCorrect = true;
            });
            
            if (!hasCorrect && checkboxes.length > 0) {
                e.preventDefault();
                alert('Important details missing: You must mark at least one option as the correct answer!');
            }
        });

        // Dynamic Options
        var optionsContainer = document.getElementById('options-container');
        var addBtn = document.getElementById('add-option-btn');
        var optionCount = document.querySelectorAll('.option-row').length;

        addBtn.addEventListener('click', function() {
            var newRow = document.createElement('div');
            newRow.className = 'option-row flex items-start space-x-4 bg-gray-700/50 p-4 rounded-md border border-gray-600 relative';
            newRow.innerHTML = `
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-300">Option Text</label>
                    <input type="text" name="options[${optionCount}][text]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm bg-gray-800 border-gray-600 text-white sm:text-sm rounded-md">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-300">Option Image (Optional)</label>
                    <input type="file" name="options[${optionCount}][image_path]" accept="image/*" class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-900 file:text-indigo-300 hover:file:bg-indigo-800">
                </div>
                <div class="pt-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="options[${optionCount}][is_correct]" value="1" class="rounded border-gray-500 bg-gray-800 text-green-500 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-green-400 font-bold">Is Correct?</span>
                    </label>
                </div>
                <button type="button" class="remove-option-btn text-red-400 hover:text-red-300 absolute top-2 right-2">&times;</button>
            `;
            optionsContainer.appendChild(newRow);
            optionCount++;
        });

        optionsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-option-btn')) {
                e.target.closest('.option-row').remove();
            }
        });
    });
</script>
@endsection
