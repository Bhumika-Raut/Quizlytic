<div align="center">
    <h1>🚀 Quizlytic</h1>
    <p>A scalable, production-ready, dynamic Quiz Application built with Laravel 11.</p>
</div>

---

## ✨ Features Overview

**Quizlytic** is a fully-featured assessment platform engineered with SOLID principles (specifically utilizing the Strategy Pattern for question evaluation) to ensure a highly extensible and maintainable codebase.

### 🧠 Dynamic Question Architecture
Supports 5 distinct question types natively:
*   **Single Choice**: Standard radio-button selection.
*   **Multiple Choice**: Checkbox selection for questions with multiple correct answers.
*   **Binary**: True/False or Yes/No toggles.
*   **Number Input**: Exact numerical validation.
*   **Text Input**: Flexible text matching.

### 🖼️ Rich Media Integration
*   **Auto-Parsing YouTube Embeds**: Paste *any* YouTube link (Standard, Shorts, Share Links) and the application will automatically extract the ID and generate a responsive, distraction-free iframe.
*   **Local Asset Management**: Upload context images directly to questions, managed cleanly via Laravel's local storage disk.

### 📊 Advanced Assessment Tools
*   **Dynamic Progress Tracking**: Live progress indicators update seamlessly as users navigate through quizzes.
*   **Randomization Engine**: Questions and their respective multiple-choice options are randomly shuffled on every new attempt to prevent pattern memorization.
*   **Detailed Post-Attempt Review**: Upon completion, users receive a comprehensive breakdown highlighting correct/incorrect responses, points awarded, and rich **Educational Explanations** for missed questions.
*   **Taxonomy & Difficulty**: Questions are categorized by Topics and scaled by Difficulty (Easy, Medium, Hard).

---

## 🛠️ Technology Stack
*   **Backend**: PHP 8.2+ / Laravel 11
*   **Frontend**: Blade Templating, Tailwind CSS v3, Alpine.js
*   **Database**: SQLite (Configured for persistent, zero-dependency deployment)
*   **Containerization**: Docker (Production-ready image mapping)

---

## 💻 Local Development Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Bhumika-Raut/Quizlytic.git
   cd quizlytic
   ```

2. **Install Dependencies:**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configure Environment:**
   Copy the `.env.example` file to `.env` (or create one) and set your database connection to SQLite:
   ```env
   DB_CONNECTION=sqlite
   # Remove DB_HOST, DB_PORT, DB_DATABASE, etc.
   ```
   Generate your application key:
   ```bash
   php artisan key:generate
   ```

4. **Initialize Database & Seed Data:**
   Run the migrations and populate the application with the comprehensive set of 11 "Fair Data" quizzes:
   ```bash
   php artisan migrate:fresh --seed --class=FairDataSeeder
   ```

5. **Link Storage (Crucial for Images):**
   ```bash
   php artisan storage:link
   ```

6. **Start the Development Server:**
   ```bash
   php artisan serve
   ```
   *Access the app at `http://localhost:8000`*

---

## 🚀 Production Deployment (Render)

Quizlytic is pre-configured to be deployed on zero-configuration ephemeral cloud platforms like **Render.com** using Docker, while brilliantly preserving its SQLite database state via Git tracking.

1. Connect your GitHub repository to a new Render **Web Service**.
2. Set the Environment to **Docker**.
3. Add the following Environment Variables in the Render Dashboard:
   *   `APP_ENV=production`
   *   `APP_DEBUG=false`
   *   `APP_KEY=base64:your_generated_app_key_here`
   *   `DB_CONNECTION=sqlite`
4. Deploy!

### 🔄 Updating Live Content
Because Render instances are ephemeral, the SQLite database acts as the single source of truth mapped directly to your Git repository. 
To add new quizzes or questions to your live site, simply create them locally, then commit the database file:

```bash
git add database/database.sqlite public/storage/images
git commit -m "Add new quiz content"
git push
```
Render will automatically pull the changes and hot-swap the live database without losing data.

Deployed link: https://quizlytic.onrender.com/quizzes
