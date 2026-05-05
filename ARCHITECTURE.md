# Laravel Dynamic Quiz System - Architecture Document

## Overview
This document outlines the architectural decisions and design patterns used to build the Dynamic Quiz System. The primary goal of the architecture was to ensure extreme **extensibility** for future question types and to **avoid hardcoded logic** (such as massive `if-else` or `switch` statements) in the controllers.

## Core Design Patterns

### 1. The Strategy Pattern (Evaluation Engine)
To dynamically evaluate different question types, we implemented the **Strategy Pattern**.
- **Interface (`QuestionEvaluatorInterface`)**: Defines a single method `evaluate(Question $question, mixed $response): EvaluationResult`.
- **Concrete Strategies**: We created isolated evaluator classes for each question type (`BinaryEvaluator`, `SingleChoiceEvaluator`, `MultipleChoiceEvaluator`, `NumberInputEvaluator`, `TextInputEvaluator`).
- **The Factory (`EvaluatorFactory`)**: A factory class that dynamically resolves the correct evaluator strategy based on the question's `type` field at runtime.

**Why this is highly extensible:** 
If a new question type (e.g., `matching` or `file_upload`) needs to be added in the future, developers only need to create a new `MatchingEvaluator` class and register it in the Factory. Zero changes are required to the `AttemptController` or `QuizAttemptService`. This completely eliminates hardcoded logic.

### 2. Service Layer Pattern
We abstracted complex business logic away from HTTP Controllers to ensure controllers remain thin, readable, and focused solely on request/response lifecycles.
- **`EvaluationEngineService`**: Coordinates the entire evaluation process. It loops through a user's answers, asks the `EvaluatorFactory` for the right strategy, grades each answer, and tallies the total score.
- **`QuizAttemptService`**: Handles the transactional safety of database writes when a user submits a quiz. It wraps the entire save-and-evaluate process in a `DB::transaction()` to ensure data integrity.

### 3. Normalized Relational Database Modeling
The database follows a strict, normalized schema suitable for SaaS applications:
- `quizzes`: Stores high-level quiz metadata.
- `topics`: A centralized taxonomy for grouping quizzes. Linked to `quizzes` via a `topic_id` foreign key.
- `questions`: Belongs to a quiz. Includes `type`, `marks`, `text` (HTML), `difficulty` (enum), `explanation` (text), and media URLs.
- `options`: Belongs to a question. Used to store multiple-choice answers OR the correct expected text/number for input questions. Includes `is_correct` boolean.
- `attempts`: Belongs to a user/quiz session. Stores the `total_score`.
- `answers`: Pivot-like table linking an `attempt` to a `question`.
  - **JSON Storage**: The `answers.response` column is defined as `JSON`. This elegantly allows us to store scalar values (strings/numbers) for text inputs, and arrays of IDs for multiple-choice questions, without needing complex polymorphic pivot tables.

### 4. Advanced Evaluation Features
- **Attempt Review Mode**: After submission, the `attempts.show` view dynamically rebuilds the user's answers, comparing them directly against the database's correct options. It highlights correct choices in green and incorrect choices in red. If a question has an `explanation`, it is conditionally rendered to help the user learn.
- **Randomization Mode**: The `QuizController` leverages Laravel Collections (`shuffle()`) to randomize the order of questions AND the order of options for each individual question immediately before passing them to the Blade template. This ensures no two attempts look exactly alike without requiring complex database-level sorting logic.

## Media Handling
Media is handled using Laravel's local `public` disk. The `QuestionController` processes uploaded files and stores them directly into `storage/app/public/questions` or `storage/app/public/options`. The `php artisan storage:link` command ensures they are securely accessible via symlinks.

## Frontend (Blade + Tailwind + Vanilla JS)
- The frontend relies on native Blade templating and TailwindCSS for responsive styling.
- **Question Editor**: Uses **Quill.js** for Rich Text Editing. Dynamic option creation (adding multiple options dynamically before submitting) is handled by lightweight Vanilla JavaScript to avoid unnecessary framework overhead (like Vue/React) per the assignment constraints.
