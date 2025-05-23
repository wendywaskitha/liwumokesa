<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate the request
            $request->validate([
                'token' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Attempt to reset the password
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user) use ($request) {
                    // Update user password
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    // Log the password reset activity
                    activity()
                        ->performedOn($user)
                        ->withProperties(['ip' => $request->ip()])
                        ->log('Password reset completed');

                    // Fire password reset event
                    event(new PasswordReset($user));
                }
            );

            // Handle the response
            if ($status == Password::PASSWORD_RESET) {
                return redirect()->route('login')->with([
                    'status' => __($status),
                    'message' => 'Password berhasil direset. Silakan login dengan password baru Anda.'
                ]);
            }

            // If reset failed, return with error
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Password reset failed: ' . $e->getMessage());

            // Return with generic error message
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Terjadi kesalahan saat mereset password. Silakan coba lagi.']);
        }
    }
}
