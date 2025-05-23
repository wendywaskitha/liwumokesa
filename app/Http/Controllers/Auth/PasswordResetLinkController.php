<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate email
            $request->validate([
                'email' => ['required', 'email', 'exists:users'],
            ], [
                'email.exists' => 'Email tidak ditemukan dalam sistem kami.'
            ]);

            // Send password reset link
            $status = Password::sendResetLink(
                $request->only('email')
            );

            // Log the attempt
            if ($status === Password::RESET_LINK_SENT) {
                activity()
                    ->withProperties(['email' => $request->email])
                    ->log('Password reset link requested');

                return back()->with([
                    'status' => __($status),
                    'message' => 'Link reset password telah dikirim ke email Anda.'
                ]);
            }

            // Handle failed attempt
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Password reset link request failed: ' . $e->getMessage());

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Terjadi kesalahan saat mengirim link reset password.']);
        }
    }
}
