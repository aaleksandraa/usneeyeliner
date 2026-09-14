<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email:rfc', 'max:255']]);
        $email = mb_strtolower(trim($request->string('email')->toString()));

        if (User::query()->where('email', $email)->where('status', 'active')->exists()) {
            Password::sendResetLink(['email' => $email]);
        }

        return back()->with('status', 'Ako aktivan nalog s ovom email adresom postoji, poslali smo vam link za postavljanje nove lozinke.');
    }
}
