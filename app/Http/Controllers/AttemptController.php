<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Quiz;
use App\Services\Quiz\QuizAttemptService;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    protected QuizAttemptService $quizAttemptService;

    public function __construct(QuizAttemptService $quizAttemptService)
    {
        $this->quizAttemptService = $quizAttemptService;
    }

    public function store(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'answers' => 'nullable|array',
        ]);

        $answers = $validated['answers'] ?? [];

        $attempt = $this->quizAttemptService->submitAttempt($quiz, $answers);

        return redirect()->route('attempts.show', $attempt);
    }

    public function storeTopic(Request $request, \App\Models\Topic $topic)
    {
        $validated = $request->validate([
            'answers' => 'nullable|array',
            'question_ids' => 'required|string',
        ]);

        $answers = $validated['answers'] ?? [];
        $questionIds = explode(',', $validated['question_ids']);
        $questions = \App\Models\Question::whereIn('id', $questionIds)->get();

        $attempt = $this->quizAttemptService->submitTopicAttempt($topic, $answers, $questions);

        return redirect()->route('attempts.show', $attempt);
    }

    public function show(Attempt $attempt)
    {
        $attempt->load('answers.question.options', 'quiz');
        return view('attempts.show', compact('attempt'));
    }
}
