@csrf
@if(isset($student)) @method('PUT') @endif

<div class="grid gap-5 sm:grid-cols-2">
    <div><label for="first_name">Ime</label><input id="first_name" name="first_name" value="{{ old('first_name', $student->first_name ?? '') }}" required>@error('first_name')<p class="field-error">{{ $message }}</p>@enderror</div>
    <div><label for="last_name">Prezime</label><input id="last_name" name="last_name" value="{{ old('last_name', $student->last_name ?? '') }}" required>@error('last_name')<p class="field-error">{{ $message }}</p>@enderror</div>
    <div class="sm:col-span-2"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email', $student->email ?? '') }}" required autocomplete="email">@error('email')<p class="field-error">{{ $message }}</p>@enderror</div>
    @if(isset($student))
        <div class="sm:col-span-2">
            <label for="status">Status</label>
            <select id="status" name="status"><option value="active" @selected(old('status', $student->status) === 'active')>Aktivan</option><option value="inactive" @selected(old('status', $student->status) === 'inactive')>Neaktivan</option></select>
        </div>
    @else
        <div><label for="password">Početna lozinka</label><input id="password" type="password" name="password" required autocomplete="new-password">@error('password')<p class="field-error">{{ $message }}</p>@enderror</div>
        <div><label for="password_confirmation">Potvrda lozinke</label><input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"></div>
    @endif
</div>

<div class="mt-7 flex gap-3"><button class="btn-primary" type="submit">Sačuvaj</button><a class="btn-secondary" href="{{ route('admin.students.index') }}">Odustani</a></div>
