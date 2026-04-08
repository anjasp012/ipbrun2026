<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        // Pre-check for Participant Role
        if ($user && $user->role === 'participant') {
            $isRunning = \App\Models\Setting::where('key', 'is_running')->first()?->value === '1';
            $startTimeStr = \App\Models\Setting::where('key', 'ticket_sale_start')->first()?->value;
            $isReady = false;

            if ($isRunning && $startTimeStr) {
                $now = \Carbon\Carbon::now('Asia/Jakarta');
                $startTime = \Carbon\Carbon::parse($startTimeStr, 'Asia/Jakarta');
                if ($now->greaterThanOrEqualTo($startTime)) {
                    $isReady = true;
                }
            }

            if (!$isReady) {
                return back()->withErrors([
                    'email' => 'Maaf, pendaftaran/dashboard peserta belum dibuka saat ini.',
                ])->onlyInput('email');
            }
        }

        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
            $request->session()->regenerate();

            // Redirect to admin dashboard if staff
            if (in_array(Auth::user()->role, ['superadmin', 'admin', 'pic'])) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Informasi akun tidak ditemukan atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
