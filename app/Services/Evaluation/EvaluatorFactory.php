<?php

namespace App\Services\Evaluation;

use App\Services\Evaluation\Contracts\QuestionEvaluatorInterface;
use App\Services\Evaluation\Evaluators\BinaryEvaluator;
use App\Services\Evaluation\Evaluators\MultipleChoiceEvaluator;
use App\Services\Evaluation\Evaluators\NumberInputEvaluator;
use App\Services\Evaluation\Evaluators\SingleChoiceEvaluator;
use App\Services\Evaluation\Evaluators\TextInputEvaluator;
use InvalidArgumentException;

class EvaluatorFactory
{
    /**
     * Get the correct evaluator strategy based on the question type.
     *
     * @param string $type
     * @return QuestionEvaluatorInterface
     */
    public static function make(string $type): QuestionEvaluatorInterface
    {
        return match ($type) {
            'binary' => new BinaryEvaluator(),
            'single_choice' => new SingleChoiceEvaluator(),
            'multiple_choice' => new MultipleChoiceEvaluator(),
            'number_input' => new NumberInputEvaluator(),
            'text_input' => new TextInputEvaluator(),
            default => throw new InvalidArgumentException("Unknown question type: {$type}"),
        };
    }
}
