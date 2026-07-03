<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ config('app.name', 'EduOS') }}</title>
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app/main.tsx'])
    </head>
    <body class="min-h-screen bg-[var(--color-bg)] text-[var(--color-text)] antialiased">
        <div id="app"></div>
    </body>
</html>
