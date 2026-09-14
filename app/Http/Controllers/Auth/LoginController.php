<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserLoginLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string'], 'remember' => ['nullable', 'boolean']]);
        $key = Str::lower($credentials['email']).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['email' => 'Previše pokušaja. Pokušajte ponovo za '.RateLimiter::availableIn($key).' sekundi.'])->onlyInput('email');
        }

        if (! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'status' => 'active'], (bool) ($credentials['remember'] ?? false))) {
            RateLimiter::hit($key, 60);

            return back()->withErrors(['email' => 'Podaci za prijavu nisu ispravni ili je nalog deaktiviran.'])->onlyInput('email');
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();
        $user = $request->user();
        $ip = $request->ip() ?: 'unknown';
        $agent = (string) $request->userAgent();
        $primaryLanguage = Str::before((string) $request->header('Accept-Language'), ',');
        $user->forceFill(['last_login_at' => now(), 'last_login_ip' => $ip])->save();

        if ($user->isStudent()) {
            UserLoginLog::create([
                'user_id' => $user->id,
                'ip_address' => $ip,
                'user_agent' => $agent ?: 'Unknown',
                'device_hash' => hash_hmac('sha256', ($agent ?: 'unknown').'|'.$primaryLanguage, (string) config('app.key')),
            ]);
        }

        return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
