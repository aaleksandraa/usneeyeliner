<x-layouts.app :title="$course->title" :heading="$course->title" subtitle="Odaberite lekciju i nastavite sa učenjem.">
    <section class="card overflow-hidden">
        <div class="grid md:grid-cols-[320px_1fr]">
            @if($course->thumbnail_url)
                <img class="h-full min-h-64 w-full object-cover" src="{{ $course->thumbnail_url }}" alt="Naslovna slika kursa {{ $course->title }}">
            @else
                <div class="flex min-h-64 items-center justify-center bg-gradient-to-br from-brand-100 to-slate-100 text-6xl font-bold text-brand-700">{{ mb_substr($course->title, 0, 1) }}</div>
            @endif
            <div class="p-7">
                <p class="whitespace-pre-line leading-7 text-slate-600">{{ $course->description }}</p>
                <div class="mt-5 text-sm font-semibold text-slate-500">{{ $course->lessons->count() }} lekcija</div>
            </div>
        </div>
    </section>

    @if($course->vimeo_embed_url)
        <section class="mt-8">
            <div class="mb-4">
                <p class="eyebrow">Uvodni video</p>
                <h2 class="mt-1 text-xl font-bold text-slate-950">{{ $course->title }}</h2>
            </div>
            <div class="overflow-hidden rounded-2xl bg-black shadow-xl ring-1 ring-slate-900/10">
                <div class="aspect-video">
                    <iframe
                        class="h-full w-full"
                        src="{{ $course->vimeo_embed_url }}"
                        title="Uvodni video: {{ $course->title }}"
                        allow="autoplay; fullscreen; picture-in-picture"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="strict-origin-when-cross-origin"
                    ></iframe>
                </div>
            </div>
        </section>
    @endif

    <section class="mt-8">
        <h2 class="mb-4 text-xl font-bold text-slate-950">Lekcije</h2>
        <div class="card divide-y divide-slate-100 overflow-hidden">
            @forelse($course->lessons as $lesson)
                <a class="group flex items-center gap-4 px-5 py-5 transition hover:bg-brand-50" href="{{ route('courses.lessons.show', [$course, $lesson]) }}">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-100 font-bold text-brand-700">{{ $loop->iteration }}</span>
                    <div class="min-w-0 flex-1">
                        <div class="font-semibold text-slate-900 group-hover:text-brand-700">{{ $lesson->title }}</div>
                        @if($lesson->description)<div class="mt-1 truncate text-sm text-slate-500">{{ $lesson->description }}</div>@endif
                    </div>
                    <span class="text-slate-400">→</span>
                </a>
            @empty
                <p class="p-8 text-center text-sm text-slate-500">Ovaj kurs još nema lekcija.</p>
            @endforelse
        </div>
    </section>
</x-layouts.app>
