<?php

namespace App\Services\Evaluation\Evaluators;

use App\Models\Question;
use App\Services\Evaluation\Contracts\QuestionEvaluatorInterface;
use App\Services\Evaluation\DTO\EvaluationResult;

class MultipleChoiceEvaluator implements QuestionEvaluatorInterface
{
    public function evaluate(Question $question, mixed $userResponse): EvaluationResult
    {
        $selectedOptionIds = is_array($userResponse) ? $userResponse : [];
        
        $correctOptionIds = $question->options()
            ->where('is_correct', true)
            ->pluck('id')
            ->toArray();

        // Check if there's any difference between selected and correct options
        // Array diff both ways must be empty to be exactly correct.
        $missedCorrectOptions = array_diff($correctOptionIds, $selectedOptionIds);
        $selectedIncorrectOptions = array_diff($selectedOptionIds, $correctOptionIds);

        if (empty($missedCorrectOptions) && empty($selectedIncorrectOptions)) {
            return new EvaluationResult(true, (float) $question->marks);
        }

        return new EvaluationResult(false, 0.0);
    }
}
