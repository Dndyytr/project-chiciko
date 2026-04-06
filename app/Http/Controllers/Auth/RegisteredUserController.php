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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;


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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Simpan email ke session agar bisa dikirim ulang
        session(['unverified_email' => $user->email]);

        // Buat URL verifikasi untuk admin
        $verificationUrl = URL::temporarySignedRoute(
            'verify.admin',
            Carbon::now()->addHours(2),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        // Kirim email ke admin
        $adminEmail = 'dandy.taufiqurrochman@gmail.com';
        Mail::raw(
            "Ada pengguna baru mendaftar: {$user->email}\n\nKlik link berikut untuk verifikasi:\n{$verificationUrl}",
            function ($message) use ($adminEmail, $user) {
                $message->to($adminEmail)
                    ->replyTo($user->email, $user->name)
                    ->subject('Verifikasi Pendaftaran Pengguna Baru');
            }
        );


        return redirect()->route('login')->with('status_registered', 'Akun kamu menunggu verifikasi admin. Silakan cek lagi nanti.');

        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(route('dashboard', absolute: false));
    }
}
