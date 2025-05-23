<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // Cek apakah email sudah diverifikasi
        if ($request->user()->hasVerifiedEmail()) {
            // Redirect berdasarkan role pengguna
            if ($request->user()->isWisatawan()) {
                return redirect()->intended(route('tourist.dashboard', absolute: false));
            }

            // Redirect default
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Tampilkan view untuk verifikasi email
        return view('auth.verify-email');
    }
}
