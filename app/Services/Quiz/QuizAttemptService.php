<?php

namespace App\Services\Quiz;

use App\Models\Answer;
use App\Models\Attempt;
use App\Models\Quiz;
use App\Services\Evaluation\EvaluationEngineService;
use Illuminate\Support\Facades\DB;

class QuizAttemptService
{
    protected EvaluationEngineService $evaluationEngine;

    public function __construct(EvaluationEngineService $evaluationEngine)
    {
        $this->evaluationEngine = $evaluationEngine;
    }

    /**
     * Submit a quiz attempt and evaluate it.
     *
     * @param Quiz $quiz
     * @param array $userResponses Array mapping question_id to response
     * @return Attempt
     */
    public function submitAttempt(Quiz $quiz, array $userResponses): Attempt
    {
        return DB::transaction(function () use ($quiz, $userResponses) {
            // Create a new attempt
            $attempt = Attempt::create([
                'quiz_id' => $quiz->id,
                'total_score' => 0, // Will be updated by EvaluationEngine
            ]);

            $answers = collect();

            // Prepare answers
            foreach ($quiz->questions as $question) {
                $response = $userResponses[$question->id] ?? null;
                // Determine if we are saving an array or scalar for JSON
                $formattedResponse = is_array($response) ? $response : $response;

                $answer = new Answer([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'response' => $formattedResponse,
                    'is_correct' => false,
                    'score_awarded' => 0,
                ]);

                // We associate the question without saving yet, so EvaluationEngine can access it
                $answer->setRelation('question', $question);
                $answers->push($answer);
            }

            // Evaluate all answers and save them, updating the attempt score
            $this->evaluationEngine->evaluateAttempt($attempt, $answers);

            return $attempt->fresh(['answers.question']);
        });
    }

    /**
     * Submit a topic attempt and evaluate it.
     *
     * @param \App\Models\Topic $topic
     * @param array $userResponses Array mapping question_id to response
     * @param \Illuminate\Database\Eloquent\Collection $questions The questions that were in this attempt
     * @return Attempt
     */
    public function submitTopicAttempt(\App\Models\Topic $topic, array $userResponses, $questions): Attempt
    {
        return DB::transaction(function () use ($topic, $userResponses, $questions) {
            // Create a new attempt
            $attempt = Attempt::create([
                'topic_id' => $topic->id,
                'quiz_id' => null,
                'total_score' => 0, // Will be updated by EvaluationEngine
            ]);

            $answers = collect();

            // Prepare answers
            foreach ($questions as $question) {
                $response = $userResponses[$question->id] ?? null;
                $formattedResponse = is_array($response) ? $response : $response;

                $answer = new Answer([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'response' => $formattedResponse,
                    'is_correct' => false,
                    'score_awarded' => 0,
                ]);

                // We associate the question without saving yet, so EvaluationEngine can access it
                $answer->setRelation('question', $question);
                $answers->push($answer);
            }

            // Evaluate all answers and save them, updating the attempt score
            $this->evaluationEngine->evaluateAttempt($attempt, $answers);

            return $attempt->fresh(['answers.question']);
        });
    }
}
