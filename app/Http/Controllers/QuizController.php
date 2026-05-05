<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Topic;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::withCount('questions')->with('topic');

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $quizzes = $query->get();
        $topics = Topic::all();

        return view('quizzes.index', compact('quizzes', 'topics'));
    }

    public function create()
    {
        $topics = Topic::all();
        return view('quizzes.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'topic_id' => 'nullable|exists:topics,id',
            'new_topic_name' => 'nullable|string|max:255',
        ]);

        if (!empty($validated['new_topic_name'])) {
            $topic = Topic::firstOrCreate(['name' => $validated['new_topic_name']]);
            $validated['topic_id'] = $topic->id;
        }

        $quiz = Quiz::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'topic_id' => $validated['topic_id'] ?? null,
        ]);

        return redirect()->route('quizzes.manage', $quiz)->with('success', 'Quiz created successfully. You can now add questions and options.');
    }

    public function manage(Quiz $quiz)
    {
        $quiz->load('questions.options', 'topic');
        return view('quizzes.manage', compact('quiz'));
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('questions.options', 'topic');

        // Randomize questions
        $shuffledQuestions = $quiz->questions->shuffle();

        $quiz->setRelation('questions', $shuffledQuestions);

        return view('quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $topics = Topic::all();
        return view('quizzes.create', compact('quiz', 'topics'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'topic_id' => 'nullable|exists:topics,id',
            'new_topic_name' => 'nullable|string|max:255',
        ]);

        if (!empty($validated['new_topic_name'])) {
            $topic = Topic::firstOrCreate(['name' => $validated['new_topic_name']]);
            $validated['topic_id'] = $topic->id;
        }

        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'topic_id' => $validated['topic_id'] ?? null,
        ]);

        return redirect()->route('quizzes.manage', $quiz)->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('success', 'Quiz deleted successfully.');
    }
}
