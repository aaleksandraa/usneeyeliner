<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Kontrolna tabla' }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="min-h-screen lg:flex">
    <header class="sticky top-0 z-30 flex items-center justify-between border-b border-slate-800 bg-slate-950/95 px-4 py-3 text-white backdrop-blur lg:hidden">
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center gap-2.5 font-bold">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-200 text-xs font-black text-slate-950 shadow-lg shadow-brand-500/20">PI</span>
            <span class="tracking-wide">PIUS <span class="text-brand-300">ACADEMY</span></span>
        </a>
        <button type="button" data-menu-button aria-expanded="false" aria-controls="main-navigation" aria-label="Otvori navigaciju" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-700 bg-slate-900">
            <x-icon name="menu" class="h-5 w-5" />
        </button>
    </header>

    <div data-menu-overlay class="fixed inset-0 z-40 hidden bg-slate-950/60 backdrop-blur-sm lg:hidden"></div>

    <aside id="main-navigation" data-menu class="fixed inset-y-0 left-0 z-50 hidden w-72 shrink-0 border-r border-white/5 bg-slate-950 px-5 py-6 text-slate-300 shadow-2xl lg:block">
        <div class="mb-9 flex items-center justify-between">
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center gap-3 text-lg font-bold text-white">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-200 text-xs font-black text-slate-950 shadow-lg shadow-brand-500/20">PI</span>
                <span class="tracking-wide">PIUS <span class="text-brand-300">ACADEMY</span></span>
            </a>
            <button data-menu-close type="button" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 hover:bg-white/10 hover:text-white lg:hidden" aria-label="Zatvori navigaciju"><x-icon name="x" class="h-5 w-5" /></button>
        </div>
        <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-600">Navigacija</p>
        <nav class="space-y-2" aria-label="Glavna navigacija">
            @if(auth()->user()->isAdmin())
                <a class="nav-link group {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : '' }}" href="{{ route('admin.dashboard') }}"><span class="nav-icon"><x-icon name="home" class="h-4 w-4" /></span><span>Dashboard</span></a>
                <a class="nav-link group {{ request()->routeIs('admin.students.*') ? 'nav-link-active' : '' }}" href="{{ route('admin.students.index') }}"><span class="nav-icon"><x-icon name="users" class="h-4 w-4" /></span><span>Učenici</span></a>
                <a class="nav-link group {{ request()->routeIs('admin.courses.*') ? 'nav-link-active' : '' }}" href="{{ route('admin.courses.index') }}"><span class="nav-icon"><x-icon name="courses" class="h-4 w-4" /></span><span>Kursevi</span></a>
            @else
                <a class="nav-link group {{ request()->routeIs('dashboard') || request()->routeIs('courses.*') ? 'nav-link-active' : '' }}" href="{{ route('dashboard') }}"><span class="nav-icon"><x-icon name="courses" class="h-4 w-4" /></span><span>Moji kursevi</span></a>
                <a class="nav-link group {{ request()->routeIs('profile.*') ? 'nav-link-active' : '' }}" href="{{ route('profile.edit') }}"><span class="nav-icon"><x-icon name="profile" class="h-4 w-4" /></span><span>Moj profil</span></a>
            @endif
        </nav>

        <div class="absolute inset-x-5 bottom-6 rounded-2xl border border-white/10 bg-white/5 p-3">
            <div class="mb-3 flex items-center gap-3 px-1">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-500/20 text-xs font-bold text-brand-200">{{ mb_strtoupper(mb_substr(auth()->user()->first_name, 0, 1).mb_substr(auth()->user()->last_name, 0, 1)) }}</span>
                <div class="min-w-0 text-sm">
                    <div class="truncate font-semibold text-white">{{ auth()->user()->full_name }}</div>
                    <div class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex w-full items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white" type="submit"><x-icon name="logout" class="h-4 w-4" />Odjava</button>
            </form>
        </div>
    </aside>

    <main class="min-w-0 flex-1 lg:ml-72">
        <div class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-10 lg:py-10">
            <div class="mb-7 flex flex-wrap items-end justify-between gap-4 border-b border-slate-200/80 pb-6">
                <div>
                    <p class="eyebrow mb-2">{{ auth()->user()->isAdmin() ? 'Administracija' : 'Platforma za učenje' }}</p>
                    <h1 class="text-2xl font-bold tracking-[-0.025em] text-slate-950 sm:text-3xl">{{ $heading ?? $title ?? '' }}</h1>
                    @if($subtitle)<p class="mt-1.5 max-w-2xl text-sm leading-6 text-slate-500">{{ $subtitle }}</p>@endif
                </div>
                {{ $actions ?? '' }}
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-xl border border-brand-300 bg-brand-100 px-4 py-3 text-sm font-medium text-brand-800" role="status">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">Provjerite označena polja i pokušajte ponovo.</div>
            @endif

            {{ $slot }}
        </div>
    </main>
</div>

<script>
const menuButton = document.querySelector('[data-menu-button]');
const menu = document.querySelector('[data-menu]');
const menuOverlay = document.querySelector('[data-menu-overlay]');
const menuClose = document.querySelector('[data-menu-close]');
const setMenu = (open) => {
    menu?.classList.toggle('hidden', !open);
    menuOverlay?.classList.toggle('hidden', !open);
    menuButton?.setAttribute('aria-expanded', String(open));
    document.body.classList.toggle('overflow-hidden', open);
};
menuButton?.addEventListener('click', () => setMenu(true));
menuClose?.addEventListener('click', () => setMenu(false));
menuOverlay?.addEventListener('click', () => setMenu(false));
</script>
</body>
</html>
