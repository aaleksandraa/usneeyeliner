<x-layouts.app title="Detalji učenika" :heading="$student->full_name" subtitle="Profil, dodijeljeni kursevi i sigurnosna aktivnost.">
    <x-slot:actions>
        <div class="flex flex-wrap gap-2">
            <a class="btn-secondary" href="{{ route('admin.students.edit', $student) }}">Uredi</a>
            <form method="POST" action="{{ route('admin.students.status', $student) }}">
                @csrf
                @method('PATCH')
                <button class="btn-secondary" type="submit">{{ $student->status === 'active' ? 'Deaktiviraj' : 'Aktiviraj' }}</button>
            </form>
        </div>
    </x-slot:actions>

    <div class="grid gap-6 xl:grid-cols-3">
        <section class="card p-6 xl:col-span-2">
            <h2 class="text-lg font-bold text-slate-950">Osnovne informacije</h2>
            <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                <div><dt class="text-xs uppercase text-slate-500">Ime i prezime</dt><dd class="mt-1 font-medium">{{ $student->full_name }}</dd></div>
                <div><dt class="text-xs uppercase text-slate-500">Email</dt><dd class="mt-1 font-medium">{{ $student->email }}</dd></div>
                <div><dt class="text-xs uppercase text-slate-500">Status</dt><dd class="mt-1"><span class="{{ $student->status === 'active' ? 'badge-active' : 'badge-inactive' }}">{{ $student->status === 'active' ? 'Aktivan' : 'Neaktivan' }}</span></dd></div>
                <div><dt class="text-xs uppercase text-slate-500">Kreiran</dt><dd class="mt-1 font-medium">{{ $student->created_at->format('d.m.Y H:i') }}</dd></div>
                <div><dt class="text-xs uppercase text-slate-500">Posljednja prijava</dt><dd class="mt-1 font-medium">{{ $student->last_login_at?->format('d.m.Y H:i') ?? 'Nikada' }}</dd></div>
                <div><dt class="text-xs uppercase text-slate-500">Posljednja IP</dt><dd class="mt-1 font-mono text-sm">{{ $student->last_login_ip ?? '—' }}</dd></div>
            </dl>
        </section>

        <section class="card p-6">
            <h2 class="text-lg font-bold text-slate-950">Reset lozinke</h2>
            <form class="mt-5 space-y-4" method="POST" action="{{ route('admin.students.password', $student) }}">
                @csrf
                @method('PUT')
                <div><label for="password">Nova lozinka</label><input id="password" type="password" name="password" required autocomplete="new-password">@error('password')<p class="field-error">{{ $message }}</p>@enderror</div>
                <div><label for="password_confirmation">Potvrda lozinke</label><input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"></div>
                <button class="btn-primary w-full" type="submit">Promijeni lozinku</button>
            </form>
        </section>
    </div>

    <section class="card mt-6 p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-slate-950">Dodijeljeni kursevi</h2>
            @if($availableCourses->isNotEmpty())
                <form method="POST" action="{{ route('admin.students.courses.store', $student) }}" class="flex w-full gap-2 sm:w-auto sm:min-w-72">
                    @csrf
                    <select name="course_id" required><option value="">Izaberi kurs…</option>@foreach($availableCourses as $course)<option value="{{ $course->id }}">{{ $course->title }}</option>@endforeach</select>
                    <button class="btn-primary" type="submit">Dodijeli</button>
                </form>
            @endif
        </div>
        <div class="mt-5 divide-y divide-slate-100">
            @forelse($student->courses as $course)
                <div class="flex items-center justify-between gap-4 py-3">
                    <a class="font-semibold text-slate-900 hover:text-brand-700" href="{{ route('admin.courses.show', $course) }}">{{ $course->title }}</a>
                    <form method="POST" action="{{ route('admin.students.courses.destroy', [$student, $course]) }}" onsubmit="return confirm('Ukloniti ovaj kurs učeniku?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-sm font-semibold text-red-600" type="submit">Ukloni</button>
                    </form>
                </div>
            @empty
                <p class="py-5 text-sm text-slate-500">Nema dodijeljenih kurseva.</p>
            @endforelse
        </div>
    </section>

    <section class="card mt-6 overflow-hidden">
        <div class="border-b border-slate-100 p-6">
            <h2 class="text-lg font-bold text-slate-950">Aktivnost prijava</h2>
            <p class="mt-1 text-sm text-slate-500">Posljednjih 50 uspješnih prijava. Oznake su informativne i ne blokiraju pristup.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr><th class="px-5 py-4">Datum i vrijeme</th><th class="px-5 py-4">IP adresa</th><th class="px-5 py-4">Browser / uređaj</th><th class="px-5 py-4">Oznake</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($activity as $log)
                        <tr>
                            <td class="whitespace-nowrap px-5 py-4">{{ $log->created_at->format('d.m.Y H:i:s') }}</td>
                            <td class="px-5 py-4 font-mono text-xs">{{ $log->ip_address }}</td>
                            <td class="max-w-md px-5 py-4"><div class="font-medium text-slate-800">{{ $log->device_description }}</div><details class="mt-1 text-xs text-slate-400"><summary class="cursor-pointer">Detalji</summary><p class="mt-1 break-all">{{ $log->user_agent }}</p></details></td>
                            <td class="whitespace-nowrap px-5 py-4">@if($log->is_new_ip)<span class="mr-1 inline-flex rounded-full bg-brand-100 px-2 py-1 text-xs font-semibold text-brand-800 ring-1 ring-inset ring-brand-200">Nova IP</span>@endif @if($log->is_new_device)<span class="inline-flex rounded-full bg-slate-950 px-2 py-1 text-xs font-semibold text-brand-100">Novi uređaj</span>@endif</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-slate-500">Nema evidentiranih prijava.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="mt-8 border-t border-slate-200 pt-6">
        <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Trajno obrisati učenika i sve povezane podatke?')">
            @csrf
            @method('DELETE')
            <button class="btn-danger" type="submit">Obriši učenika</button>
        </form>
    </section>
</x-layouts.app>
