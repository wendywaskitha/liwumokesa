<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate request
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone_number' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        try {
            // Create user with role 'wisatawan'
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'role' => 'wisatawan', // Set default role as wisatawan
            ]);

            // Log the registration activity
            activity()
                ->performedOn($user)
                ->withProperties(['role' => 'wisatawan'])
                ->log('User registered');

            event(new Registered($user));

            Auth::login($user);

            // Redirect to tourist dashboard as per your route
            return redirect()->route('tourist.dashboard');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.']);
        }
    }
}
