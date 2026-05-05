<?php

namespace App\Services\Evaluation\DTO;

class EvaluationResult
{
    public bool $isCorrect;
    public float $scoreAwarded;

    public function __construct(bool $isCorrect, float $scoreAwarded)
    {
        $this->isCorrect = $isCorrect;
        $this->scoreAwarded = $scoreAwarded;
    }
}
