<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // Validasi input
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        try {
            // Update password
            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            // Log the password update activity
            activity()
                ->performedOn($request->user())
                ->log('Password updated successfully.');

            return back()->with('status', 'password-updated');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Password update failed: ' . $e->getMessage());

            return back()->withErrors(['current_password' => 'Terjadi kesalahan saat memperbarui password.']);
        }
    }
}
