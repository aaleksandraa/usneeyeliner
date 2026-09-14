<x-layouts.guest>
    <section class="relative z-10 grid min-h-screen w-full overflow-hidden bg-brand-50 shadow-2xl shadow-black/40 lg:min-h-[min(760px,calc(100vh-4rem))] lg:max-w-6xl lg:grid-cols-[1.08fr_0.92fr] lg:rounded-[2rem] lg:border lg:border-white/10">
        <div class="relative hidden overflow-hidden bg-[#0d0d0c] p-10 text-white lg:flex lg:flex-col lg:justify-between xl:p-14">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_75%_20%,rgba(208,185,147,0.18),transparent_35%)]"></div>
            <div class="absolute -right-24 top-16 h-80 w-80 rounded-full border border-brand-200/15"></div>
            <div class="absolute -right-8 top-32 h-48 w-48 rounded-full border border-brand-200/10"></div>
            <div class="absolute bottom-24 left-14 h-px w-32 bg-gradient-to-r from-brand-300/70 to-transparent"></div>

            <a href="{{ url('/') }}" class="relative flex w-fit items-center gap-3" aria-label="PIUS ACADEMY početna stranica">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-200 text-xs font-black tracking-tight text-slate-950 shadow-lg shadow-black/30">PI</span>
                <span class="text-lg font-bold tracking-[0.16em]">PIUS <span class="text-brand-300">ACADEMY</span></span>
            </a>

            <div class="relative max-w-lg">
                <span class="inline-flex items-center gap-2 rounded-full border border-brand-200/15 bg-brand-100/5 px-3.5 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-brand-200">
                    <x-icon name="sparkles" class="h-3.5 w-3.5" />Privatna akademija
                </span>
                <h1 class="mt-7 text-5xl font-semibold leading-[1.05] tracking-[-0.05em] xl:text-6xl">Prostor za znanje koje <span class="font-serif italic text-brand-300">ostaje.</span></h1>
                <p class="mt-6 max-w-md text-base leading-8 text-stone-400">Vaši kursevi, video lekcije i edukativni sadržaji objedinjeni na jednom sigurnom i mirnom mjestu.</p>
            </div>

            <div class="relative flex items-center justify-between gap-6 border-t border-white/10 pt-6 text-xs text-stone-500">
                <span class="flex items-center gap-2 text-stone-400"><x-icon name="shield" class="h-4 w-4 text-brand-300" />Siguran privatni pristup</span>
                <span>© {{ date('Y') }} PIUS ACADEMY</span>
            </div>
        </div>

        <div class="relative flex min-h-screen flex-col bg-brand-50 px-5 py-6 sm:px-10 sm:py-9 lg:min-h-0 lg:justify-center lg:px-12 xl:px-16">
            <div class="absolute right-0 top-0 h-48 w-48 overflow-hidden lg:hidden">
                <div class="absolute -right-24 -top-24 h-48 w-48 rounded-full border border-brand-300/50"></div>
                <div class="absolute -right-12 -top-12 h-32 w-32 rounded-full border border-brand-300/30"></div>
            </div>

            <div class="relative mb-12 flex items-center justify-between lg:hidden">
                <a href="{{ url('/') }}" class="flex items-center gap-3" aria-label="PIUS ACADEMY početna stranica">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-950 text-xs font-black text-brand-100 shadow-lg">PI</span>
                    <span class="text-sm font-bold tracking-[0.13em] text-slate-950">PIUS <span class="text-brand-600">ACADEMY</span></span>
                </a>
                <span class="rounded-full border border-brand-200 bg-white/60 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-brand-700">Privatni pristup</span>
            </div>

            <div class="relative mx-auto w-full max-w-md">
                <div class="mb-8 sm:mb-10">
                    <p class="eyebrow">Prijava na platformu</p>
                    <h2 class="mt-3 text-3xl font-semibold tracking-[-0.04em] text-slate-950 sm:text-4xl">Dobro došli nazad.</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Unesite pristupne podatke koje ste dobili od administratora.</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-800" role="alert">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 text-xs font-bold">!</span>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if(session('status'))
                    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-brand-300 bg-brand-100 px-4 py-3.5 text-sm text-brand-800" role="status">
                        <x-icon name="shield" class="mt-0.5 h-5 w-5 shrink-0" />
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email">Email adresa</label>
                        <div class="relative">
                            <x-icon name="mail" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-brand-600" />
                            <input class="h-14 pl-12 pr-4" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="ime@primjer.com" required autofocus autocomplete="email" inputmode="email">
                        </div>
                    </div>

                    <div>
                        <div class="mb-1.5 flex items-center justify-between gap-3">
                            <label class="mb-0" for="password">Lozinka</label>
                            <a class="text-xs font-semibold text-brand-700 transition hover:text-slate-950 hover:underline" href="{{ route('password.request') }}">Zaboravili ste lozinku?</a>
                        </div>
                        <div class="relative">
                            <x-icon name="lock" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-brand-600" />
                            <input class="h-14 pl-12 pr-24" id="password" name="password" type="password" placeholder="Unesite lozinku" required autocomplete="current-password">
                            <button data-password-toggle type="button" class="absolute right-3 top-1/2 flex -translate-y-1/2 items-center gap-1.5 rounded-lg px-2 py-1.5 text-xs font-semibold text-slate-500 transition hover:bg-brand-100 hover:text-slate-950" aria-controls="password" aria-pressed="false">
                                <x-icon name="eye" class="h-4 w-4" /><span data-password-label>Prikaži</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4 pt-1">
                        <label class="mb-0 flex cursor-pointer items-center gap-2.5">
                            <input class="h-4 w-4 rounded border-brand-300 accent-slate-950 focus:ring-brand-500" type="checkbox" name="remember" value="1" @checked(old('remember'))>
                            <span class="text-sm text-slate-600">Zapamti me</span>
                        </label>
                        <span class="flex items-center gap-1.5 text-xs font-medium text-brand-700"><x-icon name="shield" class="h-3.5 w-3.5" />Sigurna prijava</span>
                    </div>

                    <button class="btn-primary group h-14 w-full rounded-2xl text-[15px]" type="submit">
                        Prijavi se
                        <x-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-1" />
                    </button>
                </form>

                <div class="mt-8 flex items-center gap-3 text-xs text-slate-400">
                    <span class="h-px flex-1 bg-brand-200"></span>
                    <span>Trebate pomoć s pristupom?</span>
                    <span class="h-px flex-1 bg-brand-200"></span>
                </div>
                <p class="mt-4 text-center text-xs leading-5 text-slate-500">Obratite se administratoru PIUS ACADEMY.</p>
            </div>

            <p class="relative mt-auto pt-10 text-center text-[11px] text-slate-400 lg:hidden">© {{ date('Y') }} PIUS ACADEMY · Sva prava zadržana</p>
        </div>
    </section>

    <script>
        const passwordInput = document.querySelector('#password');
        const passwordToggle = document.querySelector('[data-password-toggle]');
        const passwordLabel = document.querySelector('[data-password-label]');

        passwordToggle?.addEventListener('click', () => {
            const isVisible = passwordInput.type === 'text';
            passwordInput.type = isVisible ? 'password' : 'text';
            passwordToggle.setAttribute('aria-pressed', String(!isVisible));
            passwordLabel.textContent = isVisible ? 'Prikaži' : 'Sakrij';
            passwordInput.focus();
        });
    </script>
</x-layouts.guest>
