<x-layouts.guest>
    <section class="relative z-10 flex min-h-screen w-full flex-col overflow-hidden bg-brand-50 px-5 py-6 shadow-2xl shadow-black/40 sm:px-10 sm:py-10 lg:min-h-0 lg:max-w-xl lg:rounded-[2rem] lg:border lg:border-white/10 lg:px-14 lg:py-12">
        <div class="absolute right-0 top-0 h-56 w-56 overflow-hidden">
            <div class="absolute -right-24 -top-24 h-52 w-52 rounded-full border border-brand-300/50"></div>
            <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full border border-brand-300/30"></div>
        </div>

        <a href="{{ route('login') }}" class="relative flex w-fit items-center gap-3" aria-label="PIUS ACADEMY prijava">
            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-950 text-xs font-black text-brand-100 shadow-lg">PI</span>
            <span class="text-sm font-bold tracking-[0.13em] text-slate-950">PIUS <span class="text-brand-600">ACADEMY</span></span>
        </a>

        <div class="relative my-auto py-12">
            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-100 text-brand-700 ring-1 ring-brand-200">
                <x-icon name="mail" class="h-6 w-6" />
            </span>
            <p class="eyebrow mt-7">Obnova pristupa</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-[-0.04em] text-slate-950 sm:text-4xl">Zaboravili ste lozinku?</h1>
            <p class="mt-3 text-sm leading-6 text-slate-500">Unesite email adresu svog naloga. Ako je nalog aktivan, poslat ćemo vam siguran link za postavljanje nove lozinke.</p>

            @if(session('status'))
                <div class="mt-6 flex items-start gap-3 rounded-2xl border border-brand-300 bg-brand-100 px-4 py-3.5 text-sm leading-6 text-brand-800" role="status">
                    <x-icon name="shield" class="mt-0.5 h-5 w-5 shrink-0" />
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="mt-7 space-y-5">
                @csrf
                <div>
                    <label for="email">Email adresa</label>
                    <div class="relative">
                        <x-icon name="mail" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-brand-600" />
                        <input class="h-14 pl-12 pr-4" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="ime@primjer.com" required autofocus autocomplete="email" inputmode="email" aria-describedby="email-error">
                    </div>
                    @error('email')<p id="email-error" class="field-error">{{ $message }}</p>@enderror
                </div>

                <button class="btn-primary group h-14 w-full rounded-2xl text-[15px]" type="submit">
                    Pošalji reset link
                    <x-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-1" />
                </button>
            </form>

            <a class="mt-6 flex items-center justify-center gap-2 text-sm font-semibold text-brand-700 transition hover:text-slate-950" href="{{ route('login') }}">← Nazad na prijavu</a>
        </div>

        <p class="relative text-center text-[11px] text-slate-400">© {{ date('Y') }} PIUS ACADEMY · Sigurna obnova pristupa</p>
    </section>
</x-layouts.guest>
