@csrf
@if(isset($course)) @method('PUT') @endif

<div class="space-y-5">
    <div>
        <label for="title">Naziv kursa</label>
        <input id="title" name="title" value="{{ old('title', $course->title ?? '') }}" placeholder="Unesite naziv kursa" required autofocus>
        @error('title')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    @if(isset($course))
        <div>
            <label for="description">Kratki opis <span class="font-normal text-slate-400">(opcionalno)</span></label>
            <textarea id="description" name="description" rows="4" placeholder="Po želji dodajte kratki opis kursa">{{ old('description', $course->description) }}</textarea>
            @error('description')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="active" @selected(old('status', $course->status) === 'active')>Aktivan</option>
                <option value="inactive" @selected(old('status', $course->status) === 'inactive')>Neaktivan</option>
            </select>
        </div>
    @else
        <div class="flex items-start gap-3 rounded-xl border border-brand-200 bg-brand-50 px-4 py-3 text-sm leading-6 text-brand-800">
            <x-icon name="sparkles" class="mt-0.5 h-4 w-4 shrink-0" />
            <p>Kurs će odmah biti aktivan. Nakon spremanja dodajte Vimeo video kao prvu lekciju.</p>
        </div>
    @endif
</div>

<div class="mt-7 flex flex-wrap gap-3">
    <button class="btn-primary" type="submit">{{ isset($course) ? 'Sačuvaj izmjene' : 'Kreiraj kurs' }}</button>
    <a class="btn-secondary" href="{{ route('admin.courses.index') }}">Odustani</a>
</div>
