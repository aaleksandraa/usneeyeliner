<x-layouts.app :title="$lesson->title" :heading="$lesson->title" :subtitle="$course->title">
    <div class="grid gap-6 xl:grid-cols-[1fr_320px]">
        <div>
            <div class="overflow-hidden rounded-2xl bg-black shadow-xl">
                <div class="aspect-video">
                    <iframe
                        class="h-full w-full"
                        src="{{ $lesson->vimeo_embed_url }}"
                        title="{{ $lesson->title }}"
                        allow="autoplay; fullscreen; picture-in-picture"
                        allowfullscreen
                        referrerpolicy="strict-origin-when-cross-origin"
                    ></iframe>
                </div>
            </div>

            @if($lesson->description)
                <div class="card mt-6 p-6">
                    <p class="whitespace-pre-line leading-7 text-slate-600">{{ $lesson->description }}</p>
                </div>
            @endif
        </div>

        <aside class="card self-start overflow-hidden">
            <div class="border-b border-slate-100 p-5">
                <h2 class="font-bold text-slate-950">Sadržaj kursa</h2>
            </div>
            <nav class="divide-y divide-slate-100">
                @foreach($course->lessons as $item)
                    <a
                        class="flex gap-3 px-5 py-4 text-sm {{ $item->is($lesson) ? 'bg-brand-50 font-semibold text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}"
                        href="{{ route('courses.lessons.show', [$course, $item]) }}"
                    >
                        <span>{{ $loop->iteration }}.</span>
                        <span>{{ $item->title }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>
    </div>

    <a class="btn-secondary mt-6" href="{{ route('courses.show', $course) }}">← Nazad na kurs</a>
</x-layouts.app>
