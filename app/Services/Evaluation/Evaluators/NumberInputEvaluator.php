<?php

namespace App\Services\Evaluation\Evaluators;

use App\Models\Question;
use App\Services\Evaluation\Contracts\QuestionEvaluatorInterface;
use App\Services\Evaluation\DTO\EvaluationResult;

class NumberInputEvaluator implements QuestionEvaluatorInterface
{
    public function evaluate(Question $question, mixed $userResponse): EvaluationResult
    {
        $numericResponse = (float) $userResponse;
        
        $correctOptions = $question->options()->where('is_correct', true)->get();

        foreach ($correctOptions as $option) {
            if ((float) $option->text === $numericResponse) {
                return new EvaluationResult(true, (float) $question->marks);
            }
        }

        return new EvaluationResult(false, 0.0);
    }
}
