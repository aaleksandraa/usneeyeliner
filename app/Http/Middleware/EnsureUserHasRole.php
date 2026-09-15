<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Vaša prijava više nije aktivna. Prijavite se ponovo.',
            ]);
        }

        if ($user->role !== $role) {
            if ($request->isMethodSafe()) {
                return $this->redirectToDashboard($user->isAdmin());
            }

            abort(403);
        }

        return $next($request);
    }

    private function redirectToDashboard(bool $isAdmin): RedirectResponse
    {
        return redirect()->route($isAdmin ? 'admin.dashboard' : 'dashboard');
    }
}
