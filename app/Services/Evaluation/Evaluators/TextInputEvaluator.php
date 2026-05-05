<?php

namespace App\Services\Evaluation\Evaluators;

use App\Models\Question;
use App\Services\Evaluation\Contracts\QuestionEvaluatorInterface;
use App\Services\Evaluation\DTO\EvaluationResult;

class TextInputEvaluator implements QuestionEvaluatorInterface
{
    public function evaluate(Question $question, mixed $userResponse): EvaluationResult
    {
        $textResponse = trim(strtolower((string) $userResponse));
        
        $correctOptions = $question->options()->where('is_correct', true)->get();

        foreach ($correctOptions as $option) {
            $correctText = trim(strtolower((string) $option->text));
            if ($correctText === $textResponse) {
                return new EvaluationResult(true, (float) $question->marks);
            }
        }

        return new EvaluationResult(false, 0.0);
    }
}
