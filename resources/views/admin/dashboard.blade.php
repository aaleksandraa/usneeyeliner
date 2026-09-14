<x-layouts.app title="Dashboard" heading="Dobro došli, {{ auth()->user()->first_name }}" subtitle="Brzi pregled platforme i najvažnijih aktivnosti.">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['label' => 'Ukupno učenika', 'value' => $studentCount, 'icon' => 'users', 'class' => 'bg-slate-950 text-brand-100 ring-slate-900'],
            ['label' => 'Aktivni učenici', 'value' => $activeStudentCount, 'icon' => 'shield', 'class' => 'bg-brand-100 text-brand-800 ring-brand-200'],
            ['label' => 'Ukupno kurseva', 'value' => $courseCount, 'icon' => 'courses', 'class' => 'bg-brand-200 text-slate-950 ring-brand-300'],
            ['label' => 'Prijave danas', 'value' => $todayLoginCount, 'icon' => 'calendar', 'class' => 'bg-white text-brand-700 ring-brand-200'],
        ] as $stat)
            <article class="card relative overflow-hidden p-5 sm:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">{{ number_format($stat['value']) }}</p>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl ring-1 {{ $stat['class'] }}"><x-icon :name="$stat['icon']" class="h-5 w-5" /></span>
                </div>
                <div class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-transparent via-brand-500/30 to-transparent"></div>
            </article>
        @endforeach
    </div>

    <section class="card mt-6 overflow-hidden">
        <div class="grid lg:grid-cols-[1fr_340px]">
            <div class="p-6 sm:p-8">
                <p class="eyebrow">Brze akcije</p>
                <h2 class="mt-2 text-xl font-bold tracking-tight text-slate-950">Upravljajte platformom</h2>
                <p class="mt-2 max-w-xl text-sm leading-6 text-slate-500">Dodajte učenika, kreirajte kurs i dodijelite pristup sadržaju kroz nekoliko jednostavnih koraka.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a class="btn-primary" href="{{ route('admin.students.create') }}"><x-icon name="plus" class="h-4 w-4" />Dodaj učenika</a>
                    <a class="btn-secondary" href="{{ route('admin.courses.create') }}"><x-icon name="courses" class="h-4 w-4" />Novi kurs</a>
                </div>
            </div>
            <div class="relative hidden min-h-56 overflow-hidden bg-slate-950 lg:block">
                <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-brand-500/25 blur-3xl"></div>
                <div class="absolute -bottom-24 -left-10 h-56 w-56 rounded-full bg-brand-200/20 blur-3xl"></div>
                <div class="relative flex h-full items-center justify-center p-8">
                    <div class="grid grid-cols-2 gap-3">
                        <span class="flex h-20 w-20 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-brand-300"><x-icon name="users" class="h-8 w-8" /></span>
                        <span class="mt-5 flex h-20 w-20 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-brand-100"><x-icon name="courses" class="h-8 w-8" /></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
