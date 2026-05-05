<?php

namespace App\Services\Evaluation\Contracts;

use App\Models\Question;
use App\Services\Evaluation\DTO\EvaluationResult;

interface QuestionEvaluatorInterface
{
    /**
     * Evaluate the given user response against the question.
     *
     * @param Question $question
     * @param mixed $userResponse The user's answer (string, numeric, array of IDs)
     * @return EvaluationResult
     */
    public function evaluate(Question $question, mixed $userResponse): EvaluationResult;
}
