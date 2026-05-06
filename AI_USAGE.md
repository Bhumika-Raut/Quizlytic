# AI Usage Document

This document details how AI assistance was utilized to architect, develop, deploy, and debug the Dynamic Quiz System in Laravel. The AI acted as a pair-programmer, accelerating development while strictly adhering to the assignment's technical requirements and best practices.

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
**Result:** The AI successfully integrated `Quill.js` via CDN and provided lightweight Vanilla JavaScript to dynamically spawn new "Option" fields on the form. It also properly set up `php artisan storage:link` interactions.

## Advanced Iterations & Debugging

### 4. Media Embedding & Regex Bugfixes
**Objective:** Support external media (specifically YouTube videos) seamlessly inside quiz questions without relying on the user to format iframe tags manually.
**AI Assistance:** We prompted the AI to render YouTube links. Initially, the AI used a simple `str_replace` to swap `/watch?v=` with `/embed/`.
**Correction & Iteration:** When testing with YouTube Shorts URLs (`/shorts/`), the simple string replacement failed, resulting in a "Refused to Connect" browser error due to `X-Frame-Options: SAMEORIGIN`. The AI identified the issue and completely rewrote the extraction logic using a highly robust Regular Expression (`preg_match`). This auto-parser now flawlessly detects standard URLs, short links (`youtu.be`), and YouTube Shorts, extracting the 11-character Video ID to construct a guaranteed working embed frame.

### 5. Dockerization & Deployment
**Objective:** Deploy the application to Render.com using a completely free, ephemeral cloud setup.
**AI Assistance:** We instructed the AI to prepare the application for Render using Docker.
**Result:** The AI wrote a custom `Dockerfile` mapped to a specific PHP Apache environment. During testing, we encountered an issue where Render deployed a completely blank database, missing our heavily seeded "Fair Data".
**Correction:** The AI performed deep terminal analysis via `git status` and discovered that Laravel's default `database/.gitignore` contained a `*.sqlite*` rule, which was silently blocking our local database from being uploaded to GitHub. The AI modified the `.gitignore` rules, allowing the pre-populated SQLite database to be version-controlled, resulting in a flawless deployment that preserves data across Render restarts.

## Conclusion
The AI was an invaluable asset in architecting the Design Patterns, generating tedious CRUD boilerplate, containerizing the application, and debugging complex deployment pipelines. However, deep human review was continuously required to refine the logic—specifically regarding HTTP validation gaps, unchecked input handling, and architectural decoupled patterns.
