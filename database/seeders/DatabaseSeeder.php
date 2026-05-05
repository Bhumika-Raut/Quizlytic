<?php

namespace Database\Seeders;

use App\Models\Topic;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Topics
        $techTopic = Topic::create([
            'name' => 'Technology & Programming',
            'description' => 'Test your knowledge on software engineering and modern technology.'
        ]);

        $scienceTopic = Topic::create([
            'name' => 'Science & Math',
            'description' => 'General science and mathematical logic.'
        ]);

        // 2. Create Quiz
        $quiz = Quiz::create([
            'title' => 'Software Engineering & Laravel Basics',
            'description' => 'A comprehensive test covering Laravel concepts, general programming, and logic.',
            'topic_id' => $techTopic->id,
        ]);

        // 3. Question 1: Binary (True/False)
        $q1 = $quiz->questions()->create([
            'type' => 'binary',
            'text' => 'Laravel uses the MVC (Model-View-Controller) architectural pattern.',
            'marks' => 1,
            'difficulty' => 'easy',
            'explanation' => 'Laravel is fundamentally built around the MVC pattern, separating data (Model), presentation (View), and logic (Controller).'
        ]);
        $q1->options()->createMany([
            ['text' => 'True', 'is_correct' => true],
            ['text' => 'False', 'is_correct' => false],
        ]);

        // 4. Question 2: Single Choice
        $q2 = $quiz->questions()->create([
            'type' => 'single_choice',
            'text' => 'Which database query builder is included in Laravel by default?',
            'marks' => 2,
            'difficulty' => 'easy',
            'explanation' => 'Eloquent ORM is Laravel\'s default Object-Relational Mapper, providing a beautiful, simple ActiveRecord implementation.'
        ]);
        $q2->options()->createMany([
            ['text' => 'Doctrine', 'is_correct' => false],
            ['text' => 'Eloquent', 'is_correct' => true],
            ['text' => 'Hibernate', 'is_correct' => false],
            ['text' => 'Prisma', 'is_correct' => false],
        ]);

        // 5. Question 3: Multiple Choice
        $q3 = $quiz->questions()->create([
            'type' => 'multiple_choice',
            'text' => 'Which of the following are valid Laravel artisan commands?',
            'marks' => 3,
            'difficulty' => 'medium',
            'explanation' => 'make:model and migrate are core Artisan commands. make:frontend and serve:prod do not exist natively.'
        ]);
        $q3->options()->createMany([
            ['text' => 'php artisan make:model', 'is_correct' => true],
            ['text' => 'php artisan migrate', 'is_correct' => true],
            ['text' => 'php artisan make:frontend', 'is_correct' => false],
            ['text' => 'php artisan serve:prod', 'is_correct' => false],
        ]);

        // 6. Question 4: Number Input
        $q4 = $quiz->questions()->create([
            'type' => 'number_input',
            'text' => 'What is the sum of 5 and 7?',
            'marks' => 1,
            'difficulty' => 'easy',
            'explanation' => 'Basic addition: 5 + 7 = 12.'
        ]);
        $q4->options()->create(['text' => '12', 'is_correct' => true]);

        // 7. Question 5: Text Input
        $q5 = $quiz->questions()->create([
            'type' => 'text_input',
            'text' => 'What does HTTP stand for?',
            'marks' => 2,
            'difficulty' => 'hard',
            'explanation' => 'HTTP stands for Hypertext Transfer Protocol, the foundation of data communication for the World Wide Web.'
        ]);
        $q5->options()->create(['text' => 'Hypertext Transfer Protocol', 'is_correct' => true]);
    }
}
