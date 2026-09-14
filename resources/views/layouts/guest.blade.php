<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a09">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0a0a09]">
    <main class="relative flex min-h-screen items-center justify-center overflow-hidden lg:px-6 lg:py-8">
        <div class="pointer-events-none absolute inset-0 opacity-[0.035]" style="background-image: linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px); background-size: 64px 64px;"></div>
        <div class="pointer-events-none absolute -left-32 -top-40 h-[32rem] w-[32rem] rounded-full bg-brand-500/15 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-48 -right-32 h-[36rem] w-[36rem] rounded-full bg-brand-200/10 blur-3xl"></div>
        {{ $slot }}
    </main>
</body>
</html>
