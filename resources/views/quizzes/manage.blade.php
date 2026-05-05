@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
    <div>
        <h1 class="text-3xl font-extrabold text-white">Manage: {{ $quiz->title }}</h1>
        <p class="mt-2 text-sm text-gray-400">{{ $quiz->description }}</p>
    </div>
    <div class="mt-4 md:mt-0 space-x-3 flex">
        <a href="{{ route('quizzes.edit', $quiz) }}" class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-300 bg-gray-800 hover:bg-gray-700 transition-colors">
            Edit Quiz Details
        </a>
        <a href="{{ route('questions.create', $quiz) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 transition-colors">
            + Add Question
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-900 border border-green-500 text-green-100 px-4 py-3 rounded-lg relative mb-6 shadow-sm" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="space-y-6">
    @forelse($quiz->questions as $index => $question)
        <div class="bg-gray-800 shadow-lg sm:rounded-xl p-6 border border-gray-700 transition-all hover:border-gray-600">
            <div class="flex justify-between items-start flex-col sm:flex-row">
                <div class="flex-1 w-full">
                    <h4 class="text-lg font-medium text-white">
                        <span class="text-indigo-400 mr-1">{{ $index + 1 }}.</span> {!! $question->text !!}
                    </h4>
                    <div class="mt-2 flex flex-wrap gap-2 text-xs text-gray-400">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-gray-700 text-gray-300 border border-gray-600">
                            {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-gray-700 text-gray-300 border border-gray-600">
                            {{ $question->marks }} marks
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium border
                            {{ $question->difficulty === 'easy' ? 'bg-green-900/50 text-green-300 border-green-800' : ($question->difficulty === 'medium' ? 'bg-yellow-900/50 text-yellow-300 border-yellow-800' : 'bg-red-900/50 text-red-300 border-red-800') }}">
                            {{ ucfirst($question->difficulty) }}
                        </span>
                    </div>

                    @if($question->image_path)
                        <img src="{{ asset('storage/' . $question->image_path) }}" class="mt-4 max-w-xs rounded-lg border border-gray-600 shadow-sm" alt="Question Media">
                    @endif

                    @if($question->video_url)
                        <a href="{{ $question->video_url }}" target="_blank" class="text-indigo-400 text-sm font-medium block mt-3 hover:text-indigo-300 transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            View Attached Video
                        </a>
                    @endif

                    <div class="mt-5 bg-gray-900/50 rounded-lg p-4 border border-gray-750">
                        <p class="text-sm font-semibold text-gray-300 mb-3 border-b border-gray-700 pb-2">Options:</p>
                        <ul class="space-y-2 text-sm text-gray-400">
                            @foreach($question->options as $option)
                                <li class="flex items-center p-2 rounded-md {{ $option->is_correct ? 'bg-green-900/20 border border-green-800/50' : '' }}">
                                    @if($option->is_correct)
                                        <svg class="w-4 h-4 text-green-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    @else
                                        <span class="w-4 h-4 mr-2 flex-shrink-0"></span>
                                    @endif
                                    
                                    <span class="{{ $option->is_correct ? 'text-green-300 font-medium' : 'text-gray-300' }}">
                                        {{ $option->text ?? 'No Text' }}
                                    </span>
                                    
                                    @if($option->image_path)
                                        <span class="ml-2 px-2 py-0.5 rounded text-xs bg-gray-700 text-gray-400 border border-gray-600">Image Attached</span>
                                    @endif
                                </li>
                            @endforeach
                            @if($question->options->isEmpty())
                                <li class="text-gray-500 italic px-2">No options defined.</li>
                            @endif
                        </ul>
                    </div>
                </div>
                
                <div class="mt-4 sm:mt-0 sm:ml-6 flex sm:flex-col space-x-3 sm:space-x-0 sm:space-y-3 flex-shrink-0">
                    <a href="{{ route('questions.edit', $question) }}" class="inline-flex justify-center items-center px-4 py-2 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-300 bg-gray-800 hover:bg-gray-700 transition-colors w-full">Edit</a>
                    <form action="{{ route('questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors w-full">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center bg-gray-800 p-12 shadow-lg sm:rounded-xl border border-gray-700 text-gray-400">
            <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="mt-2 text-sm font-medium text-gray-200">No questions</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new question.</p>
            <div class="mt-6">
                <a href="{{ route('questions.create', $quiz) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                    + Add Question
                </a>
            </div>
        </div>
    @endforelse
</div>
@endsection
