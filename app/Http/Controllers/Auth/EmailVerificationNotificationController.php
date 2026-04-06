<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;


class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        // Email admin yang akan menerima notifikasi
        // $adminEmail = 'dandy.taufiqurrochman@gmail.com'; // ganti dengan email admin kamu

        // Notifikasi verifikasi bawaan Laravel
        // $notification = new VerifyEmail;

        // Kirim notifikasi ke admin
        // Notification::route('mail', $adminEmail)->notify($notification->toMail($request->user()));

        return back()->with('status', 'verification-link-sent');
    }

    public function verifyAdmin(Request $request, $id, $hash)
    {
        // Validasi tanda tangan URL agar aman
        if (!$request->hasValidSignature()) {
            abort(403, 'Link verifikasi tidak valid atau sudah kedaluwarsa.');
        }

        $user = User::findOrFail($id);

        if (sha1($user->email) !== $hash) {
            abort(403, 'Hash tidak cocok.');
        }

        // Tandai email sudah diverifikasi
        $user->email_verified_at = now();
        $user->save();

        return 'Akun ' . $user->email . ' berhasil diverifikasi oleh admin.';
    }

    public function sendVerification(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        // Buat link verifikasi khusus admin
        $verificationUrl = URL::temporarySignedRoute(
            'verify.admin',
            Carbon::now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // Kirim email ke admin
        $adminEmail = 'dandy.taufiqurrochman@gmail.com';
        Mail::raw("Ada pengguna baru: {$user->email}\n\nKlik link berikut untuk verifikasi:\n{$verificationUrl}", function ($message) use ($adminEmail, $user) {
            $message->to($adminEmail)
                ->replyTo($user->email, $user->name)
                ->subject('Permintaan Verifikasi Akun Baru');
        });

        return back()->with('status', 'Permintaan verifikasi telah dikirim ke admin.');
    }

    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User tidak ditemukan.']);
        }

        if ($user->email_verified_at) {
            return back()->with('status', 'Akun ini sudah diverifikasi.');
        }

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
            "Permintaan verifikasi ulang dari: {$user->email}\n\nKlik link berikut untuk verifikasi:\n{$verificationUrl}",
            function ($message) use ($adminEmail, $user) {
                $message->to($adminEmail)
                    ->replyTo($user->email, $user->name)
                    ->subject('Permintaan Verifikasi Ulang Akun Pengguna');
            }
        );

        return back()->with('status', 'Email verifikasi ulang telah dikirim ke admin.');
    }
}
