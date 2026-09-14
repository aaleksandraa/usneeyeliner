<x-layouts.app title="Detalji kursa" :heading="$course->title" :subtitle="'Slug: /'.$course->slug">
    <x-slot:actions>
        <div class="flex flex-wrap gap-2">
            <a class="btn-secondary" href="{{ route('admin.courses.edit', $course) }}">Uredi kurs</a>
            <a class="btn-primary" href="{{ route('admin.courses.lessons.create', $course) }}">+ Dodaj lekciju</a>
        </div>
    </x-slot:actions>

    <section class="card overflow-hidden">
        <div class="grid md:grid-cols-[260px_1fr]">
            @if($course->thumbnail_url)
                <img class="h-full min-h-52 w-full object-cover" src="{{ $course->thumbnail_url }}" alt="Naslovna slika kursa {{ $course->title }}">
            @else
                <div class="flex min-h-52 items-center justify-center bg-gradient-to-br from-brand-100 to-slate-100 text-5xl font-bold text-brand-700">{{ mb_substr($course->title, 0, 1) }}</div>
            @endif
            <div class="p-6">
                <span class="{{ $course->status === 'active' ? 'badge-active' : 'badge-inactive' }}">{{ $course->status === 'active' ? 'Aktivan' : 'Neaktivan' }}</span>
                <p class="mt-4 whitespace-pre-line leading-7 text-slate-600">{{ $course->description }}</p>
                <div class="mt-5 flex gap-5 text-sm text-slate-500">
                    <span><strong class="text-slate-900">{{ $course->lessons->count() }}</strong> lekcija</span>
                    <span><strong class="text-slate-900">{{ $course->students->count() }}</strong> učenika</span>
                </div>
            </div>
        </div>
    </section>

    @if($course->vimeo_embed_url)
        <section class="mt-6">
            <div class="mb-4 flex items-end justify-between gap-4">
                <div>
                    <p class="eyebrow">Video kursa</p>
                    <h2 class="mt-1 text-xl font-bold text-slate-950">Vimeo pregled</h2>
                </div>
                <span class="hidden text-sm text-slate-500 sm:inline">Vimeo ID: {{ $course->vimeo_id }}</span>
            </div>
            <div class="overflow-hidden rounded-2xl bg-black shadow-xl ring-1 ring-slate-900/10">
                <div class="aspect-video">
                    <iframe
                        class="h-full w-full"
                        src="{{ $course->vimeo_embed_url }}"
                        title="{{ $course->title }}"
                        allow="autoplay; fullscreen; picture-in-picture"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="strict-origin-when-cross-origin"
                    ></iframe>
                </div>
            </div>
        </section>
    @endif

    <section class="card mt-6 overflow-hidden">
        <div class="border-b border-slate-100 p-6"><h2 class="text-lg font-bold text-slate-950">Lekcije</h2></div>
        <div class="divide-y divide-slate-100">
            @forelse($course->lessons as $lesson)
                <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4">
                    <div class="flex items-center gap-4">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-sm font-bold text-slate-600">{{ $lesson->sort_order }}</span>
                        <div><div class="font-semibold text-slate-900">{{ $lesson->title }}</div><div class="text-xs text-slate-400">Vimeo ID: {{ $lesson->vimeo_id }}</div></div>
                    </div>
                    <div class="flex gap-3">
                        <a class="text-sm font-semibold text-brand-700" href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}">Uredi</a>
                        <form method="POST" action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}" onsubmit="return confirm('Obrisati ovu lekciju?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-sm font-semibold text-red-600" type="submit">Obriši</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="p-8 text-center text-sm text-slate-500">Kurs još nema lekcija.</p>
            @endforelse
        </div>
    </section>

    @if($course->students->isNotEmpty())
        <section class="card mt-6 p-6">
            <h2 class="text-lg font-bold text-slate-950">Upisani učenici</h2>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($course->students as $student)
                    <a class="rounded-full bg-slate-100 px-3 py-1.5 text-sm font-medium hover:bg-brand-100" href="{{ route('admin.students.show', $student) }}">{{ $student->full_name }}</a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mt-8 flex flex-wrap gap-3 border-t border-slate-200 pt-6">
        <form method="POST" action="{{ route('admin.courses.status', $course) }}">
            @csrf
            @method('PATCH')
            <button class="btn-secondary" type="submit">{{ $course->status === 'active' ? 'Deaktiviraj kurs' : 'Aktiviraj kurs' }}</button>
        </form>
        <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('Trajno obrisati kurs, lekcije i dodjele?')">
            @csrf
            @method('DELETE')
            <button class="btn-danger" type="submit">Obriši kurs</button>
        </form>
    </section>
</x-layouts.app>
