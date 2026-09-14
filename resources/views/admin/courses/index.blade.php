<x-layouts.app title="Kursevi" heading="Kursevi" subtitle="Sadržaj, lekcije i dodijeljeni učenici.">
    <x-slot:actions>
        <a class="btn-primary" href="{{ route('admin.courses.create') }}"><x-icon name="plus" class="h-4 w-4" />Dodaj kurs</a>
    </x-slot:actions>

    <div class="card hidden overflow-hidden md:block">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Naziv</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Lekcije</th>
                        <th class="px-5 py-4">Učenici</th>
                        <th class="px-5 py-4 text-right">Akcije</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($courses as $course)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <a class="font-semibold text-slate-950 hover:text-brand-700" href="{{ route('admin.courses.show', $course) }}">
                                    {{ $course->title }}
                                </a>
                                <div class="mt-0.5 text-xs text-slate-400">/{{ $course->slug }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="{{ $course->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $course->status === 'active' ? 'Aktivan' : 'Neaktivan' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">{{ $course->lessons_count }}</td>
                            <td class="px-5 py-4">{{ $course->students_count }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a class="font-semibold text-brand-700 hover:underline" href="{{ route('admin.courses.edit', $course) }}">Uredi</a>
                                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('Trajno obrisati kurs, lekcije i dodjele?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="font-semibold text-red-600 hover:underline" type="submit">Obriši</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-500">Još nema kurseva.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-3 md:hidden">
        @forelse($courses as $course)
            <article class="card p-5">
                <div class="flex items-start justify-between gap-3">
                    <div><a class="font-bold text-slate-950" href="{{ route('admin.courses.show', $course) }}">{{ $course->title }}</a><p class="mt-1 text-xs text-slate-400">/{{ $course->slug }}</p></div>
                    <span class="{{ $course->status === 'active' ? 'badge-active' : 'badge-inactive' }}">{{ $course->status === 'active' ? 'Aktivan' : 'Neaktivan' }}</span>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-3 text-sm"><div><span class="text-slate-400">Lekcije</span><strong class="ml-1 text-slate-900">{{ $course->lessons_count }}</strong></div><div><span class="text-slate-400">Učenici</span><strong class="ml-1 text-slate-900">{{ $course->students_count }}</strong></div></div>
                <div class="mt-4 flex items-center gap-2">
                    <a class="btn-secondary flex-1" href="{{ route('admin.courses.edit', $course) }}">Uredi</a>
                    <form class="flex-1" method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('Trajno obrisati kurs, lekcije i dodjele?')">@csrf @method('DELETE')<button class="btn-danger w-full" type="submit">Obriši</button></form>
                </div>
            </article>
        @empty
            <div class="card p-10 text-center text-sm text-slate-500">Još nema kurseva.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $courses->links() }}</div>
</x-layouts.app>
