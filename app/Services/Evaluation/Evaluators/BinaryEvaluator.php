<?php

namespace App\Services\Evaluation\Evaluators;

use App\Models\Question;
use App\Services\Evaluation\Contracts\QuestionEvaluatorInterface;
use App\Services\Evaluation\DTO\EvaluationResult;

class BinaryEvaluator implements QuestionEvaluatorInterface
{
    public function evaluate(Question $question, mixed $userResponse): EvaluationResult
    {
        // For binary questions, the response is typically the ID of the selected option
        $optionId = (int) $userResponse;
        
        $correctOption = $question->options()->where('is_correct', true)->first();

        if ($correctOption && $correctOption->id === $optionId) {
            return new EvaluationResult(true, (float) $question->marks);
        }

        return new EvaluationResult(false, 0.0);
    }
}
