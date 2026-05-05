<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizlytic</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Quill CSS for Rich Text -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        indigo: {
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom Dark Mode Adjustments for Quill */
        .dark .ql-toolbar { background: #374151; border-color: #4B5563; }
        .dark .ql-container { border-color: #4B5563; color: #F3F4F6; }
        .dark .ql-editor.ql-blank::before { color: #9CA3AF; }
        .dark .ql-stroke { stroke: #D1D5DB !important; }
        .dark .ql-fill { fill: #D1D5DB !important; }
        .dark .ql-picker-label { color: #D1D5DB !important; }
        .dark .ql-picker-options { background-color: #374151 !important; border-color: #4B5563 !important; }
        .dark .ql-toolbar button:hover .ql-stroke, .dark .ql-toolbar button:focus .ql-stroke, .dark .ql-toolbar button.ql-active .ql-stroke { stroke: #818CF8 !important; }
        .dark .ql-toolbar button:hover .ql-fill, .dark .ql-toolbar button:focus .ql-fill, .dark .ql-toolbar button.ql-active .ql-fill { fill: #818CF8 !important; }
        .dark .ql-picker-item { color: #D1D5DB !important; }
        .dark .ql-picker-item:hover, .dark .ql-picker-item.ql-selected { color: #818CF8 !important; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 font-sans antialiased min-h-screen">
    <nav class="bg-gray-800 border-b border-gray-700 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('quizzes.index') }}" class="text-xl font-bold text-indigo-400">
                            Quizlytic
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('quizzes.index') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Home</a>
                    <a href="{{ route('quizzes.create') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium transition-colors">Create Quiz</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
