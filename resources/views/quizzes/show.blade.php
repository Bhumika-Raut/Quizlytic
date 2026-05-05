@extends('layouts.app')

@section('content')
<div class="bg-gray-800 shadow-lg overflow-hidden sm:rounded-xl mb-8 relative border border-gray-700">
    <!-- Progress Bar UI -->
    <div class="absolute top-0 left-0 h-1 bg-indigo-500 transition-all duration-300 ease-in-out" id="progress-bar" style="width: 0%"></div>
    
    <div class="px-4 py-5 sm:px-6 flex justify-between items-start">
        <div>
            <h3 class="text-xl leading-6 font-bold text-white">
                {{ $quiz->title }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-400">
                {{ $quiz->description }}
            </p>
        </div>
        <div class="text-right">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-900 text-indigo-300 border border-indigo-700" id="progress-text">
                0 / {{ $quiz->questions->count() }} Answered
            </span>
        </div>
    </div>
</div>

<form action="{{ route('attempts.store', $quiz) }}" method="POST">
    @csrf
    <div class="space-y-6">
        @foreach($quiz->questions as $index => $question)
            <div class="bg-gray-800 shadow-lg sm:rounded-xl p-6 border border-gray-700">
                <div class="mb-4 pb-4 border-b border-gray-700">
                    <h4 class="text-lg font-medium text-white">
                        <span class="text-indigo-400 mr-2">Q{{ $index + 1 }}.</span> {!! $question->text !!}
                        <span class="text-sm text-gray-500 ml-2">({{ $question->marks }} marks)</span>
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                            {{ $question->difficulty === 'easy' ? 'bg-green-900 text-green-300 border-green-700' : ($question->difficulty === 'medium' ? 'bg-yellow-900 text-yellow-300 border-yellow-700' : 'bg-red-900 text-red-300 border-red-700') }} border">
                            {{ ucfirst($question->difficulty) }}
                        </span>
                    </h4>
                    
                    @if($question->image_path)
                        <img src="{{ asset('storage/' . $question->image_path) }}" class="mt-4 max-w-sm rounded-lg shadow-md border border-gray-600" alt="Question Media">
                    @endif

                    @if($question->video_url)
                        <div class="mt-4 aspect-w-16 aspect-h-9 max-w-xl">
                            <iframe src="{{ str_replace('watch?v=', 'embed/', $question->video_url) }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="rounded-lg shadow-md border border-gray-600"></iframe>
                        </div>
                    @endif
                </div>

                <div class="mt-4 space-y-4">
                    @if($question->type === 'binary' || $question->type === 'single_choice')
                        @foreach($question->options as $option)
                            <label class="flex items-center p-3 border border-gray-700 rounded-lg hover:bg-gray-750 cursor-pointer transition-colors bg-gray-900/50">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" class="h-4 w-4 text-indigo-500 bg-gray-800 border-gray-600 focus:ring-indigo-500 focus:ring-offset-gray-800">
                                <span class="ml-3 text-gray-300">{{ $option->text }}</span>
                                @if($option->image_path)
                                    <img src="{{ asset('storage/' . $option->image_path) }}" class="ml-4 h-12 rounded shadow-sm border border-gray-600" alt="Option Image">
                                @endif
                            </label>
                        @endforeach
                    @elseif($question->type === 'multiple_choice')
                        @foreach($question->options as $option)
                            <label class="flex items-center p-3 border border-gray-700 rounded-lg hover:bg-gray-750 cursor-pointer transition-colors bg-gray-900/50">
                                <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option->id }}" class="h-4 w-4 text-indigo-500 bg-gray-800 border-gray-600 focus:ring-indigo-500 focus:ring-offset-gray-800 rounded">
                                <span class="ml-3 text-gray-300">{{ $option->text }}</span>
                                @if($option->image_path)
                                    <img src="{{ asset('storage/' . $option->image_path) }}" class="ml-4 h-12 rounded shadow-sm border border-gray-600" alt="Option Image">
                                @endif
                            </label>
                        @endforeach
                    @elseif($question->type === 'number_input')
                        <input type="number" step="any" name="answers[{{ $question->id }}]" class="mt-1 block w-full max-w-xs rounded-md bg-gray-700 border-gray-600 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Enter a number">
                    @elseif($question->type === 'text_input')
                        <input type="text" name="answers[{{ $question->id }}]" class="mt-1 block w-full max-w-md rounded-md bg-gray-700 border-gray-600 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Type your answer here">
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 flex justify-end">
        @if($quiz->questions->isEmpty())
            <div class="text-gray-400 italic p-6 bg-gray-800 border border-gray-700 rounded-xl w-full text-center shadow-lg">
                This quiz currently has no questions. Check back later!
            </div>
        @else
            <button id="submit-btn" type="submit" class="inline-flex items-center px-8 py-3 border border-transparent text-lg font-bold rounded-xl shadow-lg text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-indigo-500 transition-colors duration-200">
                Submit Quiz
            </button>
        @endif
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalQuestions = {{ $quiz->questions->count() }};
        const questionInputs = document.querySelectorAll('input, select, textarea');
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        const form = document.querySelector('form');
        const submitBtn = document.getElementById('submit-btn');

        function updateProgress() {
            let answered = new Set();
            
            questionInputs.forEach(input => {
                if (input.name.startsWith('answers[')) {
                    const match = input.name.match(/answers\[(\d+)\]/);
                    if (match) {
                        const questionId = match[1];
                        if (input.type === 'radio' || input.type === 'checkbox') {
                            if (input.checked) answered.add(questionId);
                        } else if (input.value.trim() !== '') {
                            answered.add(questionId);
                        }
                    }
                }
            });

            const answeredCount = answered.size;
            const percentage = totalQuestions > 0 ? (answeredCount / totalQuestions) * 100 : 0;
            
            progressBar.style.width = percentage + '%';
            progressText.innerText = `${answeredCount} / ${totalQuestions} Answered`;
        }

        questionInputs.forEach(input => {
            input.addEventListener('change', updateProgress);
            input.addEventListener('keyup', updateProgress);
        });

        // Prevent duplicate submissions
        if(form && submitBtn) {
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Submitting...';
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            });
        }
    });
</script>
@endsection
