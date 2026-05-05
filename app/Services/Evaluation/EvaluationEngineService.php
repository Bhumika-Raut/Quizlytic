<?php

namespace App\Services\Evaluation;

use App\Models\Answer;
use App\Models\Attempt;
use Illuminate\Support\Collection;

class EvaluationEngineService
{
    /**
     * Evaluate an entire attempt and calculate the total score.
     *
     * @param Attempt $attempt
     * @param Collection $answers A collection of the user's Answers (unsaved or saved, but with question relationship loaded)
     * @return float The total score for the attempt
     */
    public function evaluateAttempt(Attempt $attempt, Collection $answers): float
    {
        $totalScore = 0.0;

        foreach ($answers as $answer) {
            $question = $answer->question;
            $evaluator = EvaluatorFactory::make($question->type);

            $result = $evaluator->evaluate($question, $answer->response);

            $answer->is_correct = $result->isCorrect;
            $answer->score_awarded = $result->scoreAwarded;
            
            // Save the evaluated answer to the database
            $answer->save();

            $totalScore += $result->scoreAwarded;
        }

        // Update the attempt total score
        $attempt->update(['total_score' => $totalScore]);

        return $totalScore;
    }
}
