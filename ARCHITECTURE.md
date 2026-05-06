# Laravel Dynamic Quiz System - Architecture Document

## Overview
This document outlines the architectural decisions and design patterns used to build the Dynamic Quiz System. The primary goal of the architecture was to ensure extreme **extensibility** for future question types, **avoid hardcoded logic** in the controllers, and provide a **resilient, ephemeral-friendly deployment pipeline**.

## Core Design Patterns

### 1. The Strategy Pattern (Evaluation Engine)
To dynamically evaluate different question types, we implemented the **Strategy Pattern**.
- **Interface (`QuestionEvaluatorInterface`)**: Defines a single method `evaluate(Question $question, mixed $response): EvaluationResult`.
- **Concrete Strategies**: We created isolated evaluator classes for each question type (`BinaryEvaluator`, `SingleChoiceEvaluator`, `MultipleChoiceEvaluator`, `NumberInputEvaluator`, `TextInputEvaluator`).
- **The Factory (`EvaluatorFactory`)**: A factory class that dynamically resolves the correct evaluator strategy based on the question's `type` field at runtime.

**Why this is highly extensible:** 
If a new question type (e.g., `matching` or `file_upload`) needs to be added, developers only need to create a new `MatchingEvaluator` class and register it in the Factory. Zero changes are required to the `AttemptController` or `QuizAttemptService`. This completely eliminates hardcoded logic.

### 2. Service Layer Pattern
We abstracted complex business logic away from HTTP Controllers to ensure controllers remain thin, readable, and focused solely on request/response lifecycles.
- **`EvaluationEngineService`**: Coordinates the evaluation process. It loops through answers, asks the `EvaluatorFactory` for the strategy, grades each answer, and tallies the total score.
- **`QuizAttemptService`**: Handles the transactional safety of database writes when a user submits a quiz. It wraps the entire save-and-evaluate process in a `DB::transaction()` to ensure data integrity.

### 3. Normalized Relational Database Modeling
The database follows a strict, normalized schema:
- `quizzes`: Stores high-level quiz metadata.
- `topics`: A centralized taxonomy for grouping quizzes. Linked to `quizzes` via a `topic_id` foreign key.
- `questions`: Belongs to a quiz. Includes `type`, `marks`, `text` (HTML), `difficulty` (enum), `explanation` (text), and media URLs.
- `options`: Belongs to a question. Used to store multiple-choice answers OR the expected text/number for input questions. Includes `is_correct` boolean.
- `attempts`: Belongs to a user/quiz session. Stores the `total_score`.
- `answers`: Pivot-like table linking an `attempt` to a `question`.
  - **JSON Storage**: The `answers.response` column is defined as `JSON`. This allows us to store scalar values (strings/numbers) for text inputs, and arrays of IDs for multiple-choice questions, without needing complex polymorphic pivot tables.

## Deployment & Persistence Architecture (Docker + SQLite)

A major architectural challenge was deploying to an ephemeral cloud host (Render) without external database dependencies (like AWS RDS or Neon).
- **SQLite as the Single Source of Truth**: Instead of MySQL/PostgreSQL, we utilized SQLite tracked via Git. By overriding the default Laravel `.gitignore` to track `database/database.sqlite`, the repository *itself* becomes the database host.
- **Docker Containerization**: The application is containerized using a custom `Dockerfile`. The startup command specifically uses `php artisan migrate --force` instead of `--seed` to ensure it boots using the Git-provided database without wiping user data on container restarts.
- **Zero-Downtime Data Swaps**: To update production data, administrators locally modify the SQLite database (via the UI or Seeders) and execute a standard Git push. Render pulls the new SQLite file and hot-swaps it in production.

## Media & Rich Content Architecture

- **YouTube Auto-Parser**: Instead of relying on users to provide perfect iframe strings, the application uses an intelligent backend Regex engine in the Blade views to sanitize and rebuild YouTube links. Whether a user inputs a `youtu.be` share link, a standard `watch?v=` URL, or a `youtube.com/shorts/` link, the engine extracts the 11-character video ID and dynamically generates a secure `youtube.com/embed/` iframe.
- **Local Assets**: Uploaded media (images) are managed using Laravel's local `public` disk via `storage:link`. In production, a custom `dummy_art.png` placeholder is pre-populated in the repository to ensure UI integrity during ephemeral container boots.
