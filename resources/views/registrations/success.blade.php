<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Submitted — SLTB</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">
    <div class="text-center max-w-sm">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h1 class="text-xl font-semibold text-gray-800">Application submitted</h1>
        <p class="text-gray-500 text-sm mt-2 leading-relaxed">Your application has been received. The depot manager will
            review it and you will be contacted once a decision is made.</p>
        <a href="{{ route('home') }}" class="mt-6 inline-block text-sm text-blue-600 hover:underline">&larr; Return to
            homepage</a>
    </div>
</body>

</html>