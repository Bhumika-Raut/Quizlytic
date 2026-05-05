@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-white">{{ isset($quiz) ? 'Edit Quiz' : 'Create New Quiz' }}</h1>
    </div>

    <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg border border-gray-700">
        <div class="p-6">
            <form action="{{ isset($quiz) ? route('quizzes.update', $quiz) : route('quizzes.store') }}" method="POST">
                @csrf
                @if(isset($quiz))
                    @method('PUT')
                @endif

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-300">Quiz Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $quiz->title ?? '') }}" required class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('title')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="topic_id" class="block text-sm font-medium text-gray-300">Topic</label>
                        <select id="topic_id" name="topic_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base bg-gray-700 border-gray-600 text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">-- Create New Topic --</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}" {{ (old('topic_id', $quiz->topic_id ?? '') == $topic->id) ? 'selected' : '' }}>
                                    {{ $topic->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('topic_id')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        
                        <div class="mt-3" id="new_topic_container" style="display: none;">
                            <input type="text" name="new_topic_name" id="new_topic_name" value="{{ old('new_topic_name') }}" placeholder="Or type a new topic name here..." class="block w-full rounded-md bg-gray-700 border-gray-600 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('new_topic_name')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $quiz->description ?? '') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('quizzes.index') }}" class="bg-gray-700 py-2 px-4 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-300 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500 mr-3">Cancel</a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500">
                        {{ isset($quiz) ? 'Update Quiz' : 'Save Quiz' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const topicSelect = document.getElementById('topic_id');
        const newTopicContainer = document.getElementById('new_topic_container');

        function toggleNewTopic() {
            if (topicSelect.value === "") {
                newTopicContainer.style.display = 'block';
            } else {
                newTopicContainer.style.display = 'none';
                document.getElementById('new_topic_name').value = '';
            }
        }

        topicSelect.addEventListener('change', toggleNewTopic);
        // Initial check
        toggleNewTopic();
    });
</script>
@endsection
