<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        // Check if email already verified
        if ($request->user()->hasVerifiedEmail()) {
            // Redirect based on user role
            if ($request->user()->isWisatawan()) {
                return redirect()->intended(route('tourist.dashboard', absolute: false));
            }

            // Default redirect
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Send verification email
        $request->user()->sendEmailVerificationNotification();

        // Return with success message
        return back()->with('status', 'verification-link-sent')
                    ->with('message', 'Link verifikasi baru telah dikirim ke email Anda.');
    }

    /**
     * Handle verification success.
     */
    protected function handleVerificationSuccess(Request $request): RedirectResponse
    {
        // Add success message
        session()->flash('success', 'Email Anda berhasil diverifikasi!');

        // Redirect based on user role
        if ($request->user()->isWisatawan()) {
            return redirect()->route('tourist.dashboard');
        }

        // Default redirect
        return redirect()->route('dashboard');
    }
}
