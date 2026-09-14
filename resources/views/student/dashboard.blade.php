<x-layouts.app title="Moji kursevi" heading="Moji kursevi" subtitle="Kursevi koji su vam trenutno dostupni.">
    @if($courses->isEmpty())
        <div class="card px-6 py-16 text-center">
            <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-50 text-brand-700 ring-1 ring-brand-100"><x-icon name="courses" class="h-7 w-7" /></span>
            <h2 class="mt-4 text-lg font-bold text-slate-900">Trenutno nemate dodijeljenih kurseva</h2>
            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">Kada vam administrator dodijeli pristup, kurs će se automatski pojaviti na ovoj stranici.</p>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($courses as $course)
                <article class="card group overflow-hidden transition duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-[0_18px_40px_rgba(15,23,42,0.10)]">
                    @if($course->thumbnail_url)
                        <div class="overflow-hidden"><img class="aspect-video w-full object-cover transition duration-500 group-hover:scale-[1.03]" src="{{ $course->thumbnail_url }}" alt="Naslovna slika kursa {{ $course->title }}" loading="lazy"></div>
                    @else
                        <div class="relative flex aspect-video items-center justify-center overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-brand-800 text-5xl font-bold text-white">
                            <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-brand-400/20 blur-2xl"></div>
                            <span class="relative">{{ mb_substr($course->title, 0, 1) }}</span>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-brand-700"><x-icon name="play" class="h-4 w-4" />{{ $course->lessons_count }} lekcija</div>
                        <h2 class="mt-2 text-xl font-bold text-slate-950">{{ $course->title }}</h2>
                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-500">{{ $course->description }}</p>
                        <a class="btn-primary mt-6 w-full" href="{{ route('courses.show', $course) }}">Otvori kurs<x-icon name="arrow-right" class="h-4 w-4" /></a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</x-layouts.app>
