@csrf
@if(isset($course)) @method('PUT') @endif

<div class="space-y-5">
    <div>
        <label for="title">Naziv kursa</label>
        <input id="title" name="title" value="{{ old('title', $course->title ?? '') }}" required>
        @error('title')<p class="field-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="description">Opis</label>
        <textarea id="description" name="description" rows="6" required>{{ old('description', $course->description ?? '') }}</textarea>
        @error('description')<p class="field-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="vimeo_url">Vimeo video <span class="font-normal text-slate-400">(opcionalno)</span></label>
        <textarea
            id="vimeo_url"
            name="vimeo_url"
            rows="3"
            placeholder="Zalijepite Vimeo URL ili kompletan iframe embed kod"
            aria-describedby="vimeo-help"
        >{{ old('vimeo_url', $course->vimeo_url ?? '') }}</textarea>
        <p id="vimeo-help" class="mt-2 text-sm leading-6 text-slate-500">Podržani su javni i privatni Vimeo linkovi, player URL i Vimeo iframe kod. Čuvamo samo siguran URL videa.</p>
        <div class="mt-3 flex items-start gap-3 rounded-xl border border-brand-200 bg-brand-50 px-4 py-3 text-sm leading-6 text-brand-800">
            <x-icon name="sparkles" class="mt-0.5 h-4 w-4 shrink-0" />
            <p>Naslovna slika kursa automatski se preuzima iz Vimeo videa. Ručni upload slike nije potreban.</p>
        </div>
        @error('vimeo_url')<p class="field-error">{{ $message }}</p>@enderror

        <div data-vimeo-preview class="{{ isset($course) && $course->vimeo_embed_url ? '' : 'hidden' }} mt-4">
            <div class="mb-2 flex items-center justify-between gap-3">
                <span class="text-sm font-semibold text-slate-700">Pregled videa</span>
                <span class="badge-active">Vimeo</span>
            </div>
            <div class="overflow-hidden rounded-2xl bg-black shadow-lg ring-1 ring-slate-900/10">
                <div class="aspect-video">
                    <iframe
                        data-vimeo-frame
                        class="h-full w-full"
                        src="{{ isset($course) ? $course->vimeo_embed_url : '' }}"
                        title="Vimeo pregled kursa"
                        allow="autoplay; fullscreen; picture-in-picture"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="strict-origin-when-cross-origin"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
    <div>
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="active" @selected(old('status', $course->status ?? 'active') === 'active')>Aktivan</option>
            <option value="inactive" @selected(old('status', $course->status ?? 'active') === 'inactive')>Neaktivan</option>
        </select>
    </div>
</div>

<script>
(() => {
    const input = document.querySelector('#vimeo_url');
    const preview = document.querySelector('[data-vimeo-preview]');
    const frame = document.querySelector('[data-vimeo-frame]');
    if (!input || !preview || !frame) return;

    const embedUrl = (inputValue) => {
        let value = inputValue.trim();
        const iframeSource = value.match(/<iframe\b[^>]*\bsrc=["']([^"']+)["']/i)?.[1];
        if (iframeSource) {
            const decoder = document.createElement('textarea');
            decoder.innerHTML = iframeSource;
            value = decoder.value;
        }

        try {
            const url = new URL(value);
            if (!['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'].includes(url.hostname.toLowerCase())) return null;
            const segments = url.pathname.split('/').filter(Boolean);
            const videoId = [...segments].reverse().find((segment) => /^\d+$/.test(segment));
            if (!videoId) return null;

            const idPosition = segments.indexOf(videoId);
            const pathHash = segments[idPosition + 1];
            const privacyHash = url.searchParams.get('h') || (/^[a-zA-Z0-9]{6,}$/.test(pathHash || '') ? pathHash : null);

            return `https://player.vimeo.com/video/${videoId}${privacyHash ? `?h=${encodeURIComponent(privacyHash)}` : ''}`;
        } catch {
            return null;
        }
    };

    input.addEventListener('input', () => {
        const url = embedUrl(input.value);
        preview.classList.toggle('hidden', !url);
        if (url && frame.src !== url) frame.src = url;
        if (!url) frame.removeAttribute('src');
    });
})();
</script>

<div class="mt-7 flex gap-3">
    <button class="btn-primary" type="submit">Sačuvaj</button>
    <a class="btn-secondary" href="{{ route('admin.courses.index') }}">Odustani</a>
</div>
