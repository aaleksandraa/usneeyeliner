<x-layouts.app title="Učenici" heading="Učenici" subtitle="Upravljanje pristupom i kursevima učenika.">
    <x-slot:actions>
        <a class="btn-primary" href="{{ route('admin.students.create') }}"><x-icon name="plus" class="h-4 w-4" />Dodaj učenika</a>
    </x-slot:actions>

    <form class="relative mb-5 max-w-lg" method="GET">
        <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
        <input class="pl-10 pr-24" aria-label="Pretraga učenika" name="search" value="{{ $search }}" placeholder="Pretraži po imenu ili emailu">
        <button class="absolute right-1.5 top-1/2 -translate-y-1/2 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-700" type="submit">Pretraži</button>
    </form>

    <div class="card hidden overflow-hidden md:block">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr><th class="px-5 py-4">Ime i prezime</th><th class="px-5 py-4">Email</th><th class="px-5 py-4">Status</th><th class="px-5 py-4">Kursevi</th><th class="px-5 py-4">Posljednja prijava</th><th class="px-5 py-4 text-right">Akcije</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $student->full_name }}</td>
                            <td class="px-5 py-4">{{ $student->email }}</td>
                            <td class="px-5 py-4"><span class="{{ $student->status === 'active' ? 'badge-active' : 'badge-inactive' }}">{{ $student->status === 'active' ? 'Aktivan' : 'Neaktivan' }}</span></td>
                            <td class="px-5 py-4">{{ $student->courses_count }}</td>
                            <td class="whitespace-nowrap px-5 py-4">{{ $student->last_login_at?->format('d.m.Y H:i') ?? 'Nikada' }}</td>
                            <td class="px-5 py-4 text-right"><a class="font-semibold text-brand-700 hover:underline" href="{{ route('admin.students.show', $student) }}">Detalji</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-slate-500">Nema pronađenih učenika.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-3 md:hidden">
        @forelse($students as $student)
            <a class="card block p-5 transition hover:border-brand-200" href="{{ route('admin.students.show', $student) }}">
                <div class="flex items-start gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-50 text-xs font-bold text-brand-700">{{ mb_strtoupper(mb_substr($student->first_name, 0, 1).mb_substr($student->last_name, 0, 1)) }}</span>
                    <div class="min-w-0 flex-1"><div class="font-bold text-slate-950">{{ $student->full_name }}</div><div class="truncate text-sm text-slate-500">{{ $student->email }}</div></div>
                    <span class="{{ $student->status === 'active' ? 'badge-active' : 'badge-inactive' }}">{{ $student->status === 'active' ? 'Aktivan' : 'Neaktivan' }}</span>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs text-slate-500"><span>{{ $student->courses_count }} dodijeljenih kurseva</span><span>{{ $student->last_login_at?->format('d.m.Y H:i') ?? 'Nije prijavljen' }}</span></div>
            </a>
        @empty
            <div class="card p-10 text-center text-sm text-slate-500">Nema pronađenih učenika.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $students->links() }}</div>
</x-layouts.app>
