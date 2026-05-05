# AI Usage Document

This document details how AI assistance was utilized to architect, develop, and refine the Dynamic Quiz System in Laravel. The AI acted as a pair-programmer, accelerating development while strictly adhering to the assignment's technical requirements and best practices.

## Prompts and Objectives

### 1. Database and Architecture Design
**Objective:** Establish a robust data model capable of supporting highly dynamic quiz attempts and extensible question formats.
**AI Assistance:** We prompted the AI to design a normalized database schema (`quizzes`, `questions`, `options`, `attempts`, `answers`). 
**Refinement:** We corrected initial ideas that required multiple pivot tables, instead instructing the AI to utilize a `JSON` column for the `answers.response` field. This successfully allowed the system to store arrays (for multiple choice) and scalars (for text inputs) seamlessly in a single field.

### 2. Implementing the Strategy Pattern
**Objective:** Fulfill the constraint: "Avoid hardcoded logic for each type in multiple places" and "System should be extensible for future types".
**AI Assistance:** We prompted the AI to structure the evaluation logic using the SOLID principles.
**Result:** The AI generated the `QuestionEvaluatorInterface`, the `EvaluatorFactory`, and specific strategies (`BinaryEvaluator`, `TextInputEvaluator`, etc.). The AI successfully decoupled the scoring mechanism from the HTTP Controllers, resulting in a production-ready Service Layer.

### 3. Frontend & Question Editor
**Objective:** Build a dynamic Question Editor with media support and rich text formatting without relying on massive JS frameworks.
**AI Assistance:** We requested the AI to integrate a Rich Text Editor and handle local file uploads via Blade.
**Result:** The AI successfully integrated `Quill.js` via CDN and provided lightweight Vanilla JavaScript to dynamically spawn new "Option" fields on the form. It also properly set up `php artisan storage:link` interactions to ensure uploaded images were served correctly via local storage.

## Corrections and Iterations
- **Issue:** Initially, the system would fail if a user submitted a Multiple Choice question completely blank (unchecked checkboxes).
- **Correction:** We analyzed the `QuizAttemptService` and recognized that looping through `$userResponses` (the HTTP request array) would skip unanswered questions. We instructed the AI to refactor the loop to iterate over the *actual quiz questions* (`$quiz->questions`), ensuring that missing responses were elegantly saved as `null` and scored as `0`, preventing critical logic failures.
- **Issue:** `server.php` was missing from the project root causing `php artisan serve` issues.
- **Correction:** We utilized the AI to diagnose the missing dependency and provisioned a custom `server.php` alongside running `composer install` to restore stability.

### 4. Phase 1-7 Refactoring (High-Impact Features)
**Objective:** Add robust features like Topics Filtering, Difficulty Metrics, and Attempt Review Mode to elevate the application to a senior level.
**AI Assistance:** The AI was prompted to execute a multi-phase implementation plan. It added migrations for `topics` and modified `questions` to include `difficulty` and `explanation`. It successfully re-engineered the `attempts/show.blade.php` view to implement the **Attempt Review Mode**, mapping correct/incorrect answers dynamically to Tailwind CSS color logic. It also implemented **Randomization** by writing logic in `QuizController` to shuffle questions and options dynamically using Laravel Collections before returning the view.

## Conclusion
The AI was an invaluable asset in architecting the Design Patterns, generating tedious CRUD boilerplate, and implementing complex DOM manipulation scripts for the Blade interface. However, deep human review was continuously required to refine the logic—specifically regarding HTTP validation gaps, unchecked input handling, and architectural decoupled patterns.
