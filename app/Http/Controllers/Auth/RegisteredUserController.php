<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Notifications\WelcomeRegistrationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming vendor registration request.
     *
     * @throws ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'vendor',
            'status' => 'pending',
        ]);

        $user->vendorProfile()->create([
            'business_name' => $validated['business_name'],
        ]);

        event(new Registered($user));

        $user->notify(new WelcomeRegistrationNotification($user));

        Auth::login($user);

        return redirect()->route('vendor.dashboard')
            ->with('info', 'Registration successful! Your vendor account is currently pending admin approval.');
    }
}
