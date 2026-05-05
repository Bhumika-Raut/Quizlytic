@extends('layouts.app')

@section('content')
@php
    $maxScore = $attempt->answers->sum(function($answer) {
        return $answer->question->marks;
    });
    $percentage = $maxScore > 0 ? ($attempt->total_score / $maxScore) * 100 : 0;
    
    if ($percentage >= 80) {
        $msgTitle = "🎉 Congratulations!";
        $msgBody = "You did exceptionally well.";
        $msgColor = "text-green-400";
        $bgBox = "bg-green-900 border-green-700 text-green-100";
    } elseif ($percentage >= 50) {
        $msgTitle = "👍 Good job!";
        $msgBody = "You did well, but there is room for improvement.";
        $msgColor = "text-yellow-400";
        $bgBox = "bg-yellow-900 border-yellow-700 text-yellow-100";
    } else {
        $msgTitle = "💪 Better luck next time!";
        $msgBody = "Review the explanations below and try again.";
        $msgColor = "text-red-400";
        $bgBox = "bg-red-900 border-red-700 text-red-100";
    }
@endphp

<div class="mb-8 flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
    <div>
        <h1 class="text-3xl font-extrabold text-white">Quiz Results</h1>
        <p class="mt-2 text-sm text-gray-400">
            @if($attempt->topic_id)
                Topic: <span class="font-bold text-indigo-400">{{ $attempt->topic->name }}</span>
            @elseif($attempt->quiz)
                Quiz: <span class="font-bold text-indigo-400">{{ $attempt->quiz->title }}</span>
            @endif
        </p>
    </div>
    <div class="flex items-center space-x-6">
        <div class="px-6 py-4 rounded-lg shadow-lg border {{ $bgBox }} text-center">
            <span class="block text-sm font-semibold uppercase tracking-wide opacity-80">Total Score</span>
            <span class="block text-4xl font-extrabold">{{ $attempt->total_score }} / {{ $maxScore }}</span>
        </div>
        
        @if($attempt->topic_id)
            <a href="{{ route('topics.take', $attempt->topic_id) }}" class="inline-flex items-center px-6 py-4 border border-transparent text-base font-bold rounded-lg shadow-lg text-white bg-indigo-600 hover:bg-indigo-500 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Retake Topic
            </a>
        @elseif($attempt->quiz)
            <a href="{{ route('quizzes.show', $attempt->quiz) }}" class="inline-flex items-center px-6 py-4 border border-transparent text-base font-bold rounded-lg shadow-lg text-white bg-indigo-600 hover:bg-indigo-500 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Retake Quiz
            </a>
        @endif
    </div>
</div>

<div class="mb-8 p-6 bg-gray-800 rounded-xl shadow-lg border border-gray-700 text-center">
    <h2 class="text-2xl font-bold {{ $msgColor }}">{{ $msgTitle }}</h2>
    <p class="mt-2 text-gray-300">{{ $msgBody }}</p>
</div>

<div class="space-y-6">
    @foreach($attempt->answers as $index => $answer)
        <div class="bg-gray-800 shadow-lg sm:rounded-xl p-6 border border-gray-700 border-l-4 {{ $answer->is_correct ? 'border-l-green-500' : 'border-l-red-500' }}">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h4 class="text-lg font-medium text-white">
                        {{ $index + 1 }}. {!! $answer->question->text !!}
                    </h4>
                    <div class="mt-4 p-4 bg-gray-900 rounded-lg border border-gray-700">
                        <p class="text-sm text-gray-400 mb-2">
                            <span class="font-semibold text-gray-300">Your Answer:</span> 
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @if(is_array($answer->response))
                                @foreach($answer->response as $res)
                                    @php $opt = $answer->question->options->firstWhere('id', $res); @endphp
                                    <span class="inline-block bg-gray-700 border border-gray-600 rounded px-3 py-1 text-sm text-white">{{ $opt ? $opt->text : $res }}</span>
                                @endforeach
                            @elseif($answer->question->type === 'binary' || $answer->question->type === 'single_choice')
                                @php $opt = $answer->question->options->firstWhere('id', $answer->response); @endphp
                                <span class="inline-block bg-gray-700 border border-gray-600 rounded px-3 py-1 text-sm text-white">{{ $opt ? $opt->text : ($answer->response ?? 'No Answer Provided') }}</span>
                            @else
                                <span class="inline-block bg-gray-700 border border-gray-600 rounded px-3 py-1 text-sm text-white">{{ $answer->response ?? 'No Answer Provided' }}</span>
                            @endif
                        </div>
                    </div>
                    
                    @if(!$answer->is_correct)
                        <div class="mt-4 p-4 bg-green-900/30 border border-green-800 rounded-lg">
                            <span class="font-semibold text-green-400 text-sm">Correct Answer(s):</span>
                            <ul class="list-disc pl-5 mt-2 text-green-300 text-sm space-y-1">
                                @foreach($answer->question->options->where('is_correct', true) as $correctOpt)
                                    <li>{{ $correctOpt->text }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($answer->question->explanation)
                        <div class="mt-4 p-4 bg-blue-900/30 border border-blue-800 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-semibold text-blue-300">Explanation</h3>
                                    <div class="mt-1 text-sm text-blue-200">
                                        <p>{{ $answer->question->explanation }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="ml-6 flex-shrink-0 text-right">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border {{ $answer->is_correct ? 'bg-green-900 text-green-300 border-green-700' : 'bg-red-900 text-red-300 border-red-700' }}">
                        {{ $answer->score_awarded }} / {{ $answer->question->marks }} marks
                    </span>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="mt-10 mb-8">
    <a href="{{ route('quizzes.index') }}" class="text-indigo-400 font-bold hover:text-indigo-300">&larr; Back to Dashboard</a>
</div>
@endsection
