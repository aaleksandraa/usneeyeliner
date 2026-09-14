<x-layouts.app title="Profil" heading="Moj profil" subtitle="Uredite lične podatke i sigurnost naloga.">
    <div class="grid gap-6 xl:grid-cols-2">
        <section class="card p-6 sm:p-8">
            <h2 class="text-lg font-bold text-slate-950">Lični podaci</h2>
            <form class="mt-6 space-y-5" method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="grid gap-5 sm:grid-cols-2">
                    <div><label for="first_name">Ime</label><input id="first_name" name="first_name" value="{{ old('first_name', $student->first_name) }}" required>@error('first_name')<p class="field-error">{{ $message }}</p>@enderror</div>
                    <div><label for="last_name">Prezime</label><input id="last_name" name="last_name" value="{{ old('last_name', $student->last_name) }}" required>@error('last_name')<p class="field-error">{{ $message }}</p>@enderror</div>
                </div>
                <div><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email', $student->email) }}" required>@error('email')<p class="field-error">{{ $message }}</p>@enderror</div>
                <button class="btn-primary" type="submit">Sačuvaj profil</button>
            </form>
        </section>

        <section class="card p-6 sm:p-8">
            <h2 class="text-lg font-bold text-slate-950">Promjena lozinke</h2>
            <form class="mt-6 space-y-5" method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')
                <div><label for="current_password">Trenutna lozinka</label><input id="current_password" type="password" name="current_password" required autocomplete="current-password">@error('current_password')<p class="field-error">{{ $message }}</p>@enderror</div>
                <div><label for="password">Nova lozinka</label><input id="password" type="password" name="password" required autocomplete="new-password">@error('password')<p class="field-error">{{ $message }}</p>@enderror</div>
                <div><label for="password_confirmation">Potvrda nove lozinke</label><input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"></div>
                <button class="btn-primary" type="submit">Promijeni lozinku</button>
            </form>
        </section>
    </div>
</x-layouts.app>
