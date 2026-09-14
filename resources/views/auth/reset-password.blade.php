<x-layouts.guest>
    <section class="relative z-10 flex min-h-screen w-full flex-col overflow-hidden bg-brand-50 px-5 py-6 shadow-2xl shadow-black/40 sm:px-10 sm:py-10 lg:min-h-0 lg:max-w-2xl lg:rounded-[2rem] lg:border lg:border-white/10 lg:px-16 lg:py-12">
        <div class="absolute right-0 top-0 h-56 w-56 overflow-hidden">
            <div class="absolute -right-24 -top-24 h-52 w-52 rounded-full border border-brand-300/50"></div>
            <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full border border-brand-300/30"></div>
        </div>

        <a href="{{ route('login') }}" class="relative flex w-fit items-center gap-3" aria-label="PIUS ACADEMY prijava">
            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-950 text-xs font-black text-brand-100 shadow-lg">PI</span>
            <span class="text-sm font-bold tracking-[0.13em] text-slate-950">PIUS <span class="text-brand-600">ACADEMY</span></span>
        </a>

        <div class="relative my-auto py-10">
            <p class="eyebrow">Sigurnost naloga</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-[-0.04em] text-slate-950 sm:text-4xl">Postavite novu lozinku.</h1>
            <p class="mt-3 text-sm leading-6 text-slate-500">Nova lozinka mora imati najmanje 8 znakova, veliko i malo slovo te najmanje jedan broj.</p>

            @if($errors->any())
                <div class="mt-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-800" role="alert">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 text-xs font-bold">!</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="mt-7 space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email">Email adresa</label>
                    <div class="relative">
                        <x-icon name="mail" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-brand-600" />
                        <input class="h-14 pl-12 pr-4" id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="email" inputmode="email">
                    </div>
                </div>

                <div>
                    <label for="password">Nova lozinka</label>
                    <div class="relative">
                        <x-icon name="lock" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-brand-600" />
                        <input class="h-14 pl-12 pr-24" id="password" name="password" type="password" placeholder="Najmanje 8 znakova" required autofocus autocomplete="new-password">
                        <button data-password-toggle="password" type="button" class="absolute right-3 top-1/2 flex -translate-y-1/2 items-center gap-1.5 rounded-lg px-2 py-1.5 text-xs font-semibold text-slate-500 transition hover:bg-brand-100 hover:text-slate-950" aria-controls="password" aria-pressed="false"><x-icon name="eye" class="h-4 w-4" /><span>Prikaži</span></button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation">Potvrdite novu lozinku</label>
                    <div class="relative">
                        <x-icon name="lock" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-brand-600" />
                        <input class="h-14 pl-12 pr-24" id="password_confirmation" name="password_confirmation" type="password" placeholder="Ponovite novu lozinku" required autocomplete="new-password">
                        <button data-password-toggle="password_confirmation" type="button" class="absolute right-3 top-1/2 flex -translate-y-1/2 items-center gap-1.5 rounded-lg px-2 py-1.5 text-xs font-semibold text-slate-500 transition hover:bg-brand-100 hover:text-slate-950" aria-controls="password_confirmation" aria-pressed="false"><x-icon name="eye" class="h-4 w-4" /><span>Prikaži</span></button>
                    </div>
                </div>

                <button class="btn-primary group h-14 w-full rounded-2xl text-[15px]" type="submit">
                    Sačuvaj novu lozinku
                    <x-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-1" />
                </button>
            </form>

            <a class="mt-6 flex items-center justify-center gap-2 text-sm font-semibold text-brand-700 transition hover:text-slate-950" href="{{ route('login') }}">← Nazad na prijavu</a>
        </div>

        <p class="relative text-center text-[11px] text-slate-400">© {{ date('Y') }} PIUS ACADEMY · Sigurna obnova pristupa</p>
    </section>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.passwordToggle);
                const isVisible = input.type === 'text';
                input.type = isVisible ? 'password' : 'text';
                button.setAttribute('aria-pressed', String(!isVisible));
                button.querySelector('span').textContent = isVisible ? 'Prikaži' : 'Sakrij';
                input.focus();
            });
        });
    </script>
</x-layouts.guest>
