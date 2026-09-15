@php($featuredPosition = $featuredLesson ? $course->lessons->search(fn ($lesson) => $lesson->is($featuredLesson)) + 1 : null)

<x-layouts.app :title="$course->title" :heading="$course->title" :subtitle="$course->lessons->count().' video lekcija'">
    @if($featuredLesson)
        <section>
            <div class="overflow-hidden rounded-2xl bg-black shadow-xl ring-1 ring-black/10">
                <div class="aspect-video">
                    <iframe
                        class="h-full w-full"
                        src="{{ $featuredLesson->vimeo_embed_url }}"
                        title="{{ $featuredLesson->title }}"
                        allow="autoplay; fullscreen; picture-in-picture"
                        allowfullscreen
                        referrerpolicy="strict-origin-when-cross-origin"
                    ></iframe>
                </div>
            </div>
            <div class="mt-4">
                <p class="eyebrow">Lekcija {{ $featuredPosition }}</p>
                <h2 class="mt-1 text-xl font-bold text-slate-950">{{ $featuredLesson->title }}</h2>
            </div>
        </section>

        <section class="mt-8 border-t border-brand-200 pt-7">
            <div class="mb-4 flex items-end justify-between gap-4">
                <div>
                    <p class="eyebrow">Sadržaj kursa</p>
                    <h2 class="mt-1 text-xl font-bold text-slate-950">Video lekcije</h2>
                </div>
                <span class="text-sm text-slate-500">{{ $course->lessons->count() }} ukupno</span>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($course->lessons as $lesson)
                    <a class="card group overflow-hidden transition duration-300 hover:-translate-y-1 hover:border-brand-300 hover:shadow-lg {{ $lesson->is($featuredLesson) ? 'ring-2 ring-slate-950 ring-offset-2 ring-offset-brand-50' : '' }}" href="{{ route('courses.lessons.show', [$course, $lesson]) }}" aria-current="{{ $lesson->is($featuredLesson) ? 'true' : 'false' }}">
                        <div class="relative aspect-video overflow-hidden bg-slate-950">
                            @if($lesson->vimeo_thumbnail_url)
                                <img class="h-full w-full object-cover transition duration-500 group-hover:scale-105" src="{{ $lesson->vimeo_thumbnail_url }}" alt="Thumbnail lekcije {{ $lesson->title }}" loading="lazy">
                            @else
                                <div class="flex h-full items-center justify-center bg-gradient-to-br from-slate-950 to-brand-800 text-brand-100"><x-icon name="play" class="h-10 w-10" /></div>
                            @endif
                            <span class="absolute left-3 top-3 flex h-8 min-w-8 items-center justify-center rounded-full bg-black/75 px-2 text-xs font-bold text-white backdrop-blur">{{ $loop->iteration }}</span>
                            <span class="absolute inset-0 flex items-center justify-center bg-black/0 transition group-hover:bg-black/20"><span class="flex h-12 w-12 scale-90 items-center justify-center rounded-full bg-brand-100/95 text-slate-950 opacity-0 shadow-lg transition group-hover:scale-100 group-hover:opacity-100"><x-icon name="play" class="h-5 w-5" /></span></span>
                        </div>
                        <div class="p-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-brand-700">Lekcija {{ $loop->iteration }}</p>
                            <h3 class="mt-1.5 font-bold leading-6 text-slate-950 group-hover:text-brand-700">{{ $lesson->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @else
        <div class="card px-6 py-16 text-center">
            <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-100 text-brand-700 ring-1 ring-brand-200"><x-icon name="play" class="h-7 w-7" /></span>
            <h2 class="mt-4 text-lg font-bold text-slate-950">Lekcije uskoro stižu</h2>
            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">Administrator još nije dodao video lekcije za ovaj kurs.</p>
        </div>
    @endif
</x-layouts.app>
