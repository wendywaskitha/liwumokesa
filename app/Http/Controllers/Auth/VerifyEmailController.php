<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        try {
            // Check if email is already verified
            if ($request->user()->hasVerifiedEmail()) {
                // Redirect based on user role
                if ($request->user()->isWisatawan()) {
                    return redirect()->intended(route('tourist.dashboard', absolute: false).'?verified=1');
                }

                return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
            }

            // Mark email as verified
            if ($request->user()->markEmailAsVerified()) {
                // Fire verified event
                event(new Verified($request->user()));

                // Log the verification
                activity()
                    ->performedOn($request->user())
                    ->log('Email verified successfully');

                // Add success message
                session()->flash('success', 'Email Anda berhasil diverifikasi!');
            }

            // Redirect based on user role after verification
            if ($request->user()->isWisatawan()) {
                return redirect()->intended(route('tourist.dashboard', absolute: false).'?verified=1');
            }

            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');

        } catch (\Exception $e) {
            // Log the error
            Log::error('Email verification failed: ' . $e->getMessage());

            // Redirect with error message
            return redirect()->route('verification.notice')
                ->with('error', 'Terjadi kesalahan saat verifikasi email. Silakan coba lagi.');
        }
    }
}
