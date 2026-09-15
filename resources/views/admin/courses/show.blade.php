<x-layouts.app title="Detalji kursa" :heading="$course->title" subtitle="Upravljajte kratkom listom video lekcija.">
    <x-slot:actions>
        <div class="flex flex-wrap gap-2">
            <a class="btn-secondary" href="{{ route('admin.courses.edit', $course) }}">Uredi kurs</a>
            <a class="btn-primary" href="{{ route('admin.courses.lessons.create', $course) }}"><x-icon name="plus" class="h-4 w-4" />Dodaj video</a>
        </div>
    </x-slot:actions>

    @if($course->description)
        <div class="card mb-6 p-5 sm:p-6">
            <p class="whitespace-pre-line text-sm leading-7 text-slate-600">{{ $course->description }}</p>
        </div>
    @endif

    <section>
        <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="eyebrow">Sadržaj kursa</p>
                <h2 class="mt-1 text-xl font-bold text-slate-950">Video lekcije</h2>
            </div>
            <span class="{{ $course->status === 'active' ? 'badge-active' : 'badge-inactive' }}">{{ $course->status === 'active' ? 'Aktivan' : 'Neaktivan' }}</span>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($course->lessons as $lesson)
                <article class="card group overflow-hidden">
                    <div class="relative aspect-video overflow-hidden bg-slate-950">
                        @if($lesson->vimeo_thumbnail_url)
                            <img class="h-full w-full object-cover" src="{{ $lesson->vimeo_thumbnail_url }}" alt="Thumbnail lekcije {{ $lesson->title }}" loading="lazy">
                        @else
                            <div class="flex h-full items-center justify-center bg-gradient-to-br from-slate-950 to-brand-800 text-brand-100"><x-icon name="play" class="h-10 w-10" /></div>
                        @endif
                        <span class="absolute left-3 top-3 flex h-8 min-w-8 items-center justify-center rounded-full bg-black/75 px-2 text-xs font-bold text-white">{{ $loop->iteration }}</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-slate-950">{{ $lesson->title }}</h3>
                        <p class="mt-1 text-xs text-slate-400">Vimeo ID: {{ $lesson->vimeo_id }}</p>
                        <div class="mt-4 flex items-center gap-4 border-t border-brand-100 pt-3">
                            <a class="text-sm font-semibold text-brand-700" href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}">Uredi</a>
                            <form method="POST" action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}" onsubmit="return confirm('Obrisati ovu lekciju?')">
                                @csrf @method('DELETE')
                                <button class="text-sm font-semibold text-red-600" type="submit">Obriši</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="card col-span-full px-6 py-14 text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-100 text-brand-700"><x-icon name="play" class="h-6 w-6" /></span>
                    <h3 class="mt-4 font-bold text-slate-950">Još nema video lekcija</h3>
                    <p class="mt-1 text-sm text-slate-500">Dodajte prvi Vimeo video i kurs je spreman za učenike.</p>
                    <a class="btn-primary mt-5" href="{{ route('admin.courses.lessons.create', $course) }}">Dodaj prvi video</a>
                </div>
            @endforelse
        </div>
    </section>

    @if($course->students->isNotEmpty())
        <section class="card mt-7 p-6">
            <h2 class="text-lg font-bold text-slate-950">Upisani učenici</h2>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($course->students as $student)
                    <a class="rounded-full bg-brand-100 px-3 py-1.5 text-sm font-medium hover:bg-brand-200" href="{{ route('admin.students.show', $student) }}">{{ $student->full_name }}</a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mt-8 flex flex-wrap gap-3 border-t border-brand-200 pt-6">
        <form method="POST" action="{{ route('admin.courses.status', $course) }}">@csrf @method('PATCH')<button class="btn-secondary" type="submit">{{ $course->status === 'active' ? 'Deaktiviraj kurs' : 'Aktiviraj kurs' }}</button></form>
        <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('Trajno obrisati kurs, lekcije i dodjele?')">@csrf @method('DELETE')<button class="btn-danger" type="submit">Obriši kurs</button></form>
    </section>
</x-layouts.app>
