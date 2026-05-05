<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Question;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * Start a dynamic topic quiz.
     */
    public function takeQuiz(Topic $topic)
    {
        // Get 5 random questions that belong to quizzes in this topic
        $questions = Question::whereHas('quiz', function($q) use ($topic) {
            $q->where('topic_id', $topic->id);
        })->with('options')->inRandomOrder()->limit(5)->get();

        if ($questions->isEmpty()) {
            return redirect()->route('quizzes.index')
                ->with('error', 'This topic has no questions yet. Please choose another one.');
        }

        // We use the same view as quiz show, but pass topic and questions explicitly
        // Since the view expects a quiz object for some texts, we can pass a dummy quiz or modify the view.
        // It's better to pass topic and questions directly and use a new view or adapt the old one.
        // Let's adapt a new view `topics.attempt`

        return view('topics.attempt', compact('topic', 'questions'));
    }
}
