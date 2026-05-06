<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Topic;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Database\Seeder;

class FairDataSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            'Technology & Programming' => Topic::firstOrCreate(['name' => 'Technology & Programming']),
            'Science & Math' => Topic::firstOrCreate(['name' => 'Science & Math']),
            'Geography' => Topic::firstOrCreate(['name' => 'Geography']),
            'Health & Medicine' => Topic::firstOrCreate(['name' => 'Health & Medicine']),
            'Literature & Arts' => Topic::firstOrCreate(['name' => 'Literature & Arts']),
            'Sports' => Topic::firstOrCreate(['name' => 'Sports']),
            'General Knowledge' => Topic::firstOrCreate(['name' => 'General Knowledge']),
        ];

        // 1. Space Exploration (Science & Math)
        $quiz1 = Quiz::create([
            'title' => 'Space Exploration',
            'description' => 'Test your knowledge about the universe, planets, and human spaceflight.',
            'topic_id' => $topics['Science & Math']->id,
        ]);
        $this->addQuestions($quiz1, [
            ['type' => 'binary', 'text' => 'The Sun is a planet.', 'options' => ['True' => false, 'False' => true]],
            ['type' => 'single_choice', 'text' => 'Which planet is known as the Red Planet?', 'options' => ['Venus' => false, 'Mars' => true, 'Jupiter' => false, 'Saturn' => false]],
            ['type' => 'multiple_choice', 'text' => 'Which of these are moons of Jupiter?', 'options' => ['Europa' => true, 'Titan' => false, 'Ganymede' => true, 'Phobos' => false]],
            ['type' => 'number_input', 'text' => 'How many planets are in our solar system (officially)?', 'options' => ['8' => true]],
            ['type' => 'text_input', 'text' => 'What is the name of our galaxy?', 'options' => ['Milky Way' => true]],
        ]);

        // 2. Modern Web Development (Technology & Programming)
        $quiz2 = Quiz::create([
            'title' => 'Modern Web Development',
            'description' => 'A quiz covering modern frontend and backend web development concepts.',
            'topic_id' => $topics['Technology & Programming']->id,
        ]);
        $this->addQuestions($quiz2, [
            ['type' => 'binary', 'text' => 'HTML stands for HyperText Markup Language.', 'options' => ['True' => true, 'False' => false]],
            ['type' => 'single_choice', 'text' => 'What does CSS control?', 'options' => ['Logic' => false, 'Styling' => true, 'Database' => false, 'Server configuration' => false]],
            ['type' => 'multiple_choice', 'text' => 'Which of the following are JavaScript frameworks/libraries?', 'options' => ['React' => true, 'Laravel' => false, 'Vue' => true, 'Django' => false]],
            ['type' => 'number_input', 'text' => 'What HTTP status code is used for "Not Found"?', 'options' => ['404' => true]],
            ['type' => 'text_input', 'text' => 'What is the most popular version control system?', 'options' => ['git' => true]],
        ]);

        // 3. World Capitals (Geography)
        $quiz3 = Quiz::create([
            'title' => 'World Capitals',
            'description' => 'How well do you know the capital cities of the world?',
            'topic_id' => $topics['Geography']->id,
        ]);
        $this->addQuestions($quiz3, [
            ['type' => 'binary', 'text' => 'The capital of Australia is Sydney.', 'options' => ['True' => false, 'False' => true]],
            ['type' => 'single_choice', 'text' => 'What is the capital of Japan?', 'options' => ['Seoul' => false, 'Beijing' => false, 'Tokyo' => true, 'Bangkok' => false]],
            ['type' => 'multiple_choice', 'text' => 'Which of these cities are capitals in Europe?', 'options' => ['Paris' => true, 'New York' => false, 'Berlin' => true, 'Toronto' => false]],
            ['type' => 'number_input', 'text' => 'How many continents are there?', 'options' => ['7' => true]],
            ['type' => 'text_input', 'text' => 'What is the capital of France?', 'options' => ['Paris' => true]],
        ]);

        // 4. Basic First Aid (Health & Medicine)
        $quiz4 = Quiz::create([
            'title' => 'Basic First Aid',
            'description' => 'Essential medical knowledge that everyone should know.',
            'topic_id' => $topics['Health & Medicine']->id,
        ]);
        $this->addQuestions($quiz4, [
            ['type' => 'binary', 'text' => 'You should apply butter to a severe burn.', 'options' => ['True' => false, 'False' => true]],
            ['type' => 'single_choice', 'text' => 'What does CPR stand for?', 'options' => ['Cardio Pulse Rescue' => false, 'Cardiopulmonary Resuscitation' => true, 'Chest Pressure Relief' => false, 'Cardiac Patient Revival' => false]],
            ['type' => 'multiple_choice', 'text' => 'Which of these are signs of a stroke?', 'options' => ['Face drooping' => true, 'Arm weakness' => true, 'Itchy skin' => false, 'Speech difficulty' => true]],
            ['type' => 'number_input', 'text' => 'What is the normal human body temperature in Celsius (approximate)?', 'options' => ['37' => true]],
            ['type' => 'text_input', 'text' => 'What gas do humans need to breathe to survive?', 'options' => ['Oxygen' => true]],
        ]);

        // 5. Classic Literature (Literature & Arts)
        $quiz5 = Quiz::create([
            'title' => 'Classic Literature',
            'description' => 'Questions about famous books and their authors.',
            'topic_id' => $topics['Literature & Arts']->id,
        ]);
        $this->addQuestions($quiz5, [
            ['type' => 'binary', 'text' => 'William Shakespeare wrote "Romeo and Juliet".', 'options' => ['True' => true, 'False' => false]],
            ['type' => 'single_choice', 'text' => 'Who wrote "1984"?', 'options' => ['George Orwell' => true, 'Aldous Huxley' => false, 'Ray Bradbury' => false, 'J.K. Rowling' => false]],
            ['type' => 'multiple_choice', 'text' => 'Which of these novels were written by Jane Austen?', 'options' => ['Pride and Prejudice' => true, 'Wuthering Heights' => false, 'Emma' => true, 'Jane Eyre' => false]],
            ['type' => 'number_input', 'text' => 'In what century was "Frankenstein" published (e.g. type 19 for 19th)?', 'options' => ['19' => true]],
            ['type' => 'text_input', 'text' => 'What is the name of the captain in Moby-Dick?', 'options' => ['Ahab' => true]],
        ]);

        // 6. Olympic Games History (Sports)
        $quiz6 = Quiz::create([
            'title' => 'Olympic Games History',
            'description' => 'Test your knowledge about the greatest sporting event in the world.',
            'topic_id' => $topics['Sports']->id,
        ]);
        $this->addQuestions($quiz6, [
            ['type' => 'binary', 'text' => 'The modern Olympic games started in 1896.', 'options' => ['True' => true, 'False' => false]],
            ['type' => 'single_choice', 'text' => 'Where were the first modern Olympic games held?', 'options' => ['Rome' => false, 'Athens' => true, 'London' => false, 'Paris' => false]],
            ['type' => 'multiple_choice', 'text' => 'Which of these sports are included in the Winter Olympics?', 'options' => ['Ice Hockey' => true, 'Swimming' => false, 'Figure Skating' => true, 'Gymnastics' => false]],
            ['type' => 'number_input', 'text' => 'How many rings are there on the Olympic flag?', 'options' => ['5' => true]],
            ['type' => 'text_input', 'text' => 'What country has won the most gold medals in Olympic history?', 'options' => ['United States' => true]],
        ]);

        // 7. Pop Culture Trivia (General Knowledge)
        $quiz7 = Quiz::create([
            'title' => 'Pop Culture Trivia',
            'description' => 'Fun trivia about movies, music, and entertainment.',
            'topic_id' => $topics['General Knowledge']->id,
        ]);
        $this->addQuestions($quiz7, [
            ['type' => 'binary', 'text' => 'The Oscars are awards given for musical achievements.', 'options' => ['True' => false, 'False' => true]],
            ['type' => 'single_choice', 'text' => 'Who is the "King of Pop"?', 'options' => ['Elvis Presley' => false, 'Prince' => false, 'Michael Jackson' => true, 'Madonna' => false]],
            ['type' => 'multiple_choice', 'text' => 'Which of these are characters from the Star Wars franchise?', 'options' => ['Darth Vader' => true, 'Spock' => false, 'Luke Skywalker' => true, 'Harry Potter' => false]],
            ['type' => 'number_input', 'text' => 'How many movies are in the original Lord of the Rings trilogy?', 'options' => ['3' => true]],
            ['type' => 'text_input', 'text' => 'What is the name of the fictional city where Batman lives?', 'options' => ['Gotham' => true]],
        ]);

        // 8. Cybersecurity Basics (Technology & Programming)
        $quiz8 = Quiz::create([
            'title' => 'Cybersecurity Basics',
            'description' => 'Core concepts of keeping data safe on the internet.',
            'topic_id' => $topics['Technology & Programming']->id,
        ]);
        $this->addQuestions($quiz8, [
            ['type' => 'binary', 'text' => 'Using the same password for all accounts is a good practice.', 'options' => ['True' => false, 'False' => true]],
            ['type' => 'single_choice', 'text' => 'What is Phishing?', 'options' => ['A type of sport' => false, 'Tricking users into giving up credentials' => true, 'A hardware failure' => false, 'Encrypting a database' => false]],
            ['type' => 'multiple_choice', 'text' => 'Which of these make a password stronger?', 'options' => ['Using special characters' => true, 'Using your birthday' => false, 'Making it long' => true, 'Using "password123"' => false]],
            ['type' => 'number_input', 'text' => 'If you enable 2FA, how many factors of authentication are required?', 'options' => ['2' => true]],
            ['type' => 'text_input', 'text' => 'What protocol provides encrypted communication over the internet (replaces HTTP)?', 'options' => ['HTTPS' => true]],
        ]);

        // 9. Music Theory & History (Literature & Arts) - Testing Videos & Explanations
        $quiz9 = Quiz::create([
            'title' => 'Music Theory & History',
            'description' => 'Test your knowledge of musical concepts, accompanied by explanations and video examples.',
            'topic_id' => $topics['Literature & Arts']->id,
        ]);
        $this->addQuestions($quiz9, [
            ['type' => 'single_choice', 'text' => 'Which composer wrote the famous "Symphony No. 5"?', 'options' => ['Mozart' => false, 'Beethoven' => true, 'Bach' => false, 'Chopin' => false], 'video_url' => 'https://www.youtube.com/embed/1-X1vH_J2eY', 'explanation' => 'Beethoven wrote his Symphony No. 5 between 1804 and 1808. It is one of the best-known compositions in classical music.'],
            ['type' => 'binary', 'text' => 'A piano has 88 keys.', 'options' => ['True' => true, 'False' => false], 'explanation' => 'A standard piano has 52 white keys and 36 black keys, totaling 88.'],
            ['type' => 'multiple_choice', 'text' => 'Which of these are woodwind instruments?', 'options' => ['Flute' => true, 'Trumpet' => false, 'Clarinet' => true, 'Violin' => false], 'explanation' => 'Flutes and clarinets produce sound by blowing air across an edge or reed, classifying them as woodwinds.'],
            ['type' => 'number_input', 'text' => 'How many strings does a standard violin have?', 'options' => ['4' => true], 'explanation' => 'A standard violin has four strings tuned to G, D, A, and E.'],
            ['type' => 'text_input', 'text' => 'What musical term indicates playing loudly?', 'options' => ['Forte' => true], 'explanation' => '"Forte" is the Italian term used in music to instruct the performer to play loudly.'],
        ]);

        // 10. Visual Arts & Media (Literature & Arts) - Testing Images & Explanations
        $this->generateDummyImage(); // Generate a placeholder image for testing
        $quiz10 = Quiz::create([
            'title' => 'Visual Arts & Media',
            'description' => 'A quiz exploring art concepts using visual aids.',
            'topic_id' => $topics['Literature & Arts']->id,
        ]);
        $this->addQuestions($quiz10, [
            ['type' => 'single_choice', 'text' => 'Who painted the Mona Lisa?', 'options' => ['Vincent van Gogh' => false, 'Leonardo da Vinci' => true, 'Pablo Picasso' => false, 'Claude Monet' => false], 'image_path' => 'images/dummy_art.png', 'explanation' => 'The Mona Lisa was painted by the Italian Renaissance artist Leonardo da Vinci.'],
            ['type' => 'binary', 'text' => 'Primary colors can be created by mixing other colors.', 'options' => ['True' => false, 'False' => true], 'image_path' => 'images/dummy_art.png', 'explanation' => 'Primary colors (Red, Blue, Yellow) cannot be formed by mixing other colors. All other colors are derived from them.'],
            ['type' => 'multiple_choice', 'text' => 'Which of these are considered primary colors in traditional art?', 'options' => ['Red' => true, 'Green' => false, 'Blue' => true, 'Purple' => false], 'explanation' => 'Red, Blue, and Yellow are the traditional primary colors in painting.'],
            ['type' => 'number_input', 'text' => 'In photography, what is the Rule of ___?', 'options' => ['3' => true], 'explanation' => 'The Rule of Thirds is a composition guideline that places subjects along imaginary grid lines.'],
            ['type' => 'text_input', 'text' => 'What art movement was Salvador Dalí known for?', 'options' => ['Surrealism' => true], 'explanation' => 'Dalí was a prominent Spanish surrealist artist known for his bizarre and striking images.'],
        ]);

        // 11. Advanced General Science (Science & Math) - Testing Mixed Features
        $quiz11 = Quiz::create([
            'title' => 'Advanced General Science',
            'description' => 'A comprehensive science quiz fully utilizing video embeds and detailed explanations.',
            'topic_id' => $topics['Science & Math']->id,
        ]);
        $this->addQuestions($quiz11, [
            ['type' => 'single_choice', 'text' => 'What is the powerhouse of the cell?', 'options' => ['Nucleus' => false, 'Ribosome' => false, 'Mitochondria' => true, 'Endoplasmic Reticulum' => false], 'video_url' => 'https://www.youtube.com/embed/1-X1vH_J2eY', 'explanation' => 'Mitochondria generate most of the chemical energy needed to power the cell\'s biochemical reactions.'],
            ['type' => 'binary', 'text' => 'Water expands when it freezes.', 'options' => ['True' => true, 'False' => false], 'explanation' => 'Unlike most substances, water\'s molecular structure forms a crystalline lattice when frozen, causing it to take up more space.'],
            ['type' => 'multiple_choice', 'text' => 'Which of these are states of matter?', 'options' => ['Solid' => true, 'Liquid' => true, 'Energy' => false, 'Plasma' => true], 'explanation' => 'Solid, liquid, gas, and plasma are the four fundamental states of matter.'],
            ['type' => 'number_input', 'text' => 'What is the atomic number of Carbon?', 'options' => ['6' => true], 'image_path' => 'images/dummy_art.png', 'explanation' => 'Carbon is the sixth element in the periodic table, meaning it has 6 protons.'],
            ['type' => 'text_input', 'text' => 'What force keeps planets in orbit around the sun?', 'options' => ['Gravity' => true], 'video_url' => 'https://www.youtube.com/embed/1-X1vH_J2eY', 'explanation' => 'Gravity is the universal force of attraction acting between all matter.'],
        ]);
    }

    private function generateDummyImage()
    {
        $dir = storage_path('app/public/images');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        // Base64 encoded 1x1 purple pixel to bypass GD extension requirement
        $base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        file_put_contents($dir . '/dummy_art.png', base64_decode($base64));
    }

    private function addQuestions(Quiz $quiz, array $questionsData)
    {
        foreach ($questionsData as $qData) {
            $question = $quiz->questions()->create([
                'type' => $qData['type'],
                'text' => '<p>' . $qData['text'] . '</p>',
                'marks' => 1,
                'difficulty' => 'medium',
                'video_url' => $qData['video_url'] ?? null,
                'explanation' => $qData['explanation'] ?? null,
                'image_path' => $qData['image_path'] ?? null,
            ]);

            foreach ($qData['options'] as $optionText => $isCorrect) {
                $question->options()->create([
                    'text' => $optionText,
                    'is_correct' => $isCorrect,
                ]);
            }
        }
    }
}
