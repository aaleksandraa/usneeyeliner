<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function create(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->string('email')->toString(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $status = PasswordBroker::reset(
            $credentials,
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== PasswordBroker::PASSWORD_RESET) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => $status === PasswordBroker::INVALID_TOKEN
                    ? 'Link za reset lozinke je neispravan ili je istekao.'
                    : 'Nije moguće resetovati lozinku za navedenu email adresu.',
            ]);
        }

        return redirect()->route('login')->with('status', 'Lozinka je uspješno promijenjena. Sada se možete prijaviti.');
    }
}
