@csrf
@if(isset($lesson)) @method('PUT') @endif

<div class="space-y-5">
    <div><label for="title">Naziv lekcije</label><input id="title" name="title" value="{{ old('title', $lesson->title ?? '') }}" required>@error('title')<p class="field-error">{{ $message }}</p>@enderror</div>
    <div><label for="description">Opis <span class="font-normal text-slate-400">(opcionalno)</span></label><textarea id="description" name="description" rows="4">{{ old('description', $lesson->description ?? '') }}</textarea></div>
    <div>
        <label for="vimeo_url">Vimeo URL</label>
        <input id="vimeo_url" type="url" name="vimeo_url" value="{{ old('vimeo_url', $lesson->vimeo_url ?? '') }}" placeholder="https://vimeo.com/123456789" required>
        @error('vimeo_url')<p class="field-error">{{ $message }}</p>@enderror
        <p class="mt-1 text-xs text-slate-400">Podržani su standardni, player, channel, showcase i unlisted Vimeo URL-ovi.</p>
    </div>
    <div><label for="sort_order">Redoslijed</label><input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $lesson->sort_order ?? 0) }}" required>@error('sort_order')<p class="field-error">{{ $message }}</p>@enderror</div>
</div>

<div class="mt-7 flex gap-3">
    <button class="btn-primary" type="submit">Sačuvaj lekciju</button>
    <a class="btn-secondary" href="{{ route('admin.courses.show', $course) }}">Odustani</a>
</div>
