<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function create(Quiz $quiz)
    {
        return view('questions.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'type' => 'required|in:binary,single_choice,multiple_choice,number_input,text_input',
            'text' => 'required|string',
            'image_path' => 'nullable|image|max:2048',
            'video_url' => 'nullable|url',
            'marks' => 'required|numeric|min:0.5',
            'difficulty' => 'required|in:easy,medium,hard',
            'explanation' => 'nullable|string',
            'options' => 'nullable|array',
            'options.*.text' => 'nullable|string',
            'options.*.image_path' => 'nullable|image|max:2048',
            'options.*.is_correct' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('questions', 'public');
        }

        $question = $quiz->questions()->create([
            'type' => $validated['type'],
            'text' => $validated['text'],
            'image_path' => $imagePath,
            'video_url' => $validated['video_url'] ?? null,
            'marks' => $validated['marks'],
            'difficulty' => $validated['difficulty'],
            'explanation' => $validated['explanation'] ?? null,
        ]);

        if (isset($validated['options']) && is_array($validated['options'])) {
            foreach ($validated['options'] as $index => $opt) {
                // Determine if we have a text or file for the option
                $optImagePath = null;
                if ($request->hasFile("options.{$index}.image_path")) {
                    $optImagePath = $request->file("options.{$index}.image_path")->store('options', 'public');
                }

                $isCorrect = isset($opt['is_correct']) && $opt['is_correct'] ? true : false;

                // Only create option if it has text or an image
                if (!empty($opt['text']) || $optImagePath) {
                    $question->options()->create([
                        'text' => $opt['text'] ?? null,
                        'image_path' => $optImagePath,
                        'is_correct' => $isCorrect,
                    ]);
                }
            }
        }

        return redirect()->route('quizzes.manage', $quiz)->with('success', 'Question added successfully.');
    }

    public function edit(Question $question)
    {
        $quiz = $question->quiz;
        $question->load('options');
        return view('questions.create', compact('quiz', 'question'));
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'type' => 'required|in:binary,single_choice,multiple_choice,number_input,text_input',
            'text' => 'required|string',
            'image_path' => 'nullable|image|max:2048',
            'video_url' => 'nullable|url',
            'marks' => 'required|numeric|min:0.5',
            'difficulty' => 'required|in:easy,medium,hard',
            'explanation' => 'nullable|string',
            'options' => 'nullable|array',
            'options.*.id' => 'nullable|exists:options,id',
            'options.*.text' => 'nullable|string',
            'options.*.image_path' => 'nullable|image|max:2048',
            'options.*.is_correct' => 'nullable|boolean',
        ]);

        $imagePath = $question->image_path;
        if ($request->hasFile('image_path')) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = $request->file('image_path')->store('questions', 'public');
        }

        $question->update([
            'type' => $validated['type'],
            'text' => $validated['text'],
            'image_path' => $imagePath,
            'video_url' => $validated['video_url'] ?? null,
            'marks' => $validated['marks'],
            'difficulty' => $validated['difficulty'],
            'explanation' => $validated['explanation'] ?? null,
        ]);

        // Simple sync for options: delete existing and recreate, or update existing.
        // For simplicity, we just delete all and recreate since it's an assignment.
        // Wait, if we delete all, we lose images. Let's update existing.
        
        $existingOptionIds = [];

        if (isset($validated['options']) && is_array($validated['options'])) {
            foreach ($validated['options'] as $index => $opt) {
                $isCorrect = isset($opt['is_correct']) && $opt['is_correct'] ? true : false;
                
                $option = null;
                if (!empty($opt['id'])) {
                    $option = Option::find($opt['id']);
                }

                $optImagePath = $option ? $option->image_path : null;
                if ($request->hasFile("options.{$index}.image_path")) {
                    if ($optImagePath) Storage::disk('public')->delete($optImagePath);
                    $optImagePath = $request->file("options.{$index}.image_path")->store('options', 'public');
                }

                if (!empty($opt['text']) || $optImagePath) {
                    if ($option) {
                        $option->update([
                            'text' => $opt['text'] ?? null,
                            'image_path' => $optImagePath,
                            'is_correct' => $isCorrect,
                        ]);
                        $existingOptionIds[] = $option->id;
                    } else {
                        $newOpt = $question->options()->create([
                            'text' => $opt['text'] ?? null,
                            'image_path' => $optImagePath,
                            'is_correct' => $isCorrect,
                        ]);
                        $existingOptionIds[] = $newOpt->id;
                    }
                }
            }
        }

        // Delete options that were removed
        $optionsToDelete = $question->options()->whereNotIn('id', $existingOptionIds)->get();
        foreach ($optionsToDelete as $optToDelete) {
            if ($optToDelete->image_path) Storage::disk('public')->delete($optToDelete->image_path);
            $optToDelete->delete();
        }

        return redirect()->route('quizzes.manage', $question->quiz_id)->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $quizId = $question->quiz_id;
        if ($question->image_path) Storage::disk('public')->delete($question->image_path);
        foreach ($question->options as $option) {
            if ($option->image_path) Storage::disk('public')->delete($option->image_path);
        }
        $question->delete();
        return redirect()->route('quizzes.manage', $quizId)->with('success', 'Question deleted successfully.');
    }
}
