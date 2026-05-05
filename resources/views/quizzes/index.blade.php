@extends('layouts.app')

@section('content')
<!-- Dynamic Topic Quiz Generator Section -->
<div class="mb-12 bg-gray-800 rounded-xl p-8 shadow-lg border border-gray-700">
    <div class="text-center mb-6">
        <h2 class="text-3xl font-extrabold text-white">Generate Dynamic Quiz</h2>
        <p class="mt-2 text-gray-400">Select a topic to instantly get a random 5-question test!</p>
    </div>
    <div class="max-w-xl mx-auto flex space-x-4">
        <select id="topic_select" class="block w-full pl-3 pr-10 py-3 text-base bg-gray-700 border-gray-600 text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
            <option value="">-- Choose a Topic --</option>
            @foreach($topics as $topic)
                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
            @endforeach
        </select>
        <button onclick="startTopicQuiz()" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors">
            Start Quiz
        </button>
    </div>
</div>

<script>
    function startTopicQuiz() {
        const topicId = document.getElementById('topic_select').value;
        if (!topicId) {
            alert('Please select a topic first.');
            return;
        }
        window.location.href = `/topics/${topicId}/take`;
    }
</script>

<hr class="border-gray-700 mb-12">

<!-- Classic Quizzes Section -->
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
    <div>
        <h1 class="text-3xl font-extrabold text-white">All Quizzes</h1>
        <p class="mt-2 text-sm text-gray-400">Or browse specific pre-made quizzes below.</p>
    </div>
    <div class="mt-4 md:mt-0 flex space-x-4 items-center">
        <form method="GET" action="{{ route('quizzes.index') }}" class="flex flex-col sm:flex-row items-center sm:space-x-2 space-y-2 sm:space-y-0">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search quizzes by title..." class="block w-full pl-3 pr-3 py-2 text-base bg-gray-700 border-gray-600 text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
            <select name="topic_id" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-base bg-gray-700 border-gray-600 text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                <option value="">All Topics</option>
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                        {{ $topic->name }}
                    </option>
                @endforeach
            </select>
            <!-- Hidden submit button so hitting Enter in the text field works seamlessly -->
            <button type="submit" class="hidden">Search</button>
        </form>
        <a href="{{ route('quizzes.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 transition-colors">
            + Create New Quiz
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-900 border border-green-500 text-green-100 px-4 py-3 rounded relative mb-6" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
@if(session('error'))
    <div class="bg-red-900 border border-red-500 text-red-100 px-4 py-3 rounded relative mb-6" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse($quizzes as $quiz)
        <div class="bg-gray-800 overflow-hidden shadow-lg rounded-lg flex flex-col border border-gray-700 hover:border-indigo-500 transition-colors">
            <div class="px-4 py-5 sm:p-6 flex-1">
                <h3 class="text-xl font-bold text-white truncate">{{ $quiz->title }}</h3>
                <p class="mt-1 text-sm text-gray-400 line-clamp-2">
                    {{ $quiz->description ?? 'No description provided.' }}
                </p>
                <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                    <div class="flex flex-col">
                        <span class="text-gray-300">{{ $quiz->questions_count }} Questions</span>
                        @if($quiz->topic)
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-xs font-medium bg-indigo-900 text-indigo-300 border border-indigo-700">
                                {{ $quiz->topic->name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-gray-750 px-4 py-4 sm:px-6 border-t border-gray-700 flex justify-between items-center bg-gray-700/50">
                <a href="{{ route('quizzes.manage', $quiz) }}" class="text-indigo-400 font-semibold hover:text-indigo-300">Manage</a>
                <a href="{{ route('quizzes.show', $quiz) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500">Start Quiz &rarr;</a>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center bg-gray-800 p-12 shadow rounded-lg border border-gray-700 text-gray-400">
            No quizzes found. Click "+ Create New Quiz" to get started.
        </div>
    @endforelse
</div>
@endsection
