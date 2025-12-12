<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $loginInput = $request->login;
        $password = $request->password;

        // Determine if login is email or phone/whatsapp
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Try to find user by email or phone
        $user = User::where($field, $loginInput)->first();

        // If not found by phone field, try whatsapp field as alternative
        if (!$user && $field === 'phone') {
            // Normalize phone number (remove spaces, dashes)
            $normalizedPhone = preg_replace('/[^0-9+]/', '', $loginInput);
            
            // Check in users table (phone field)
            $user = User::where('phone', $normalizedPhone)
                ->orWhere('phone', ltrim($normalizedPhone, '0'))
                ->orWhere('phone', '0' . ltrim($normalizedPhone, '+62'))
                ->orWhere('phone', '+62' . ltrim($normalizedPhone, '0'))
                ->first();
        }

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->remember);
            $request->session()->regenerate();

            if ($user->isStaff()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('resident.dashboard'));
        }

        return back()->withErrors([
            'login' => 'No. WhatsApp/Email atau password salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Check for duplicate phone number first
        $existingUserByPhone = User::where('phone', $request->phone)->first();
        if ($existingUserByPhone) {
            return back()->withErrors([
                'phone' => 'No. WhatsApp ini sudah terdaftar. Silakan gunakan nomor lain atau login dengan akun yang sudah ada.',
            ])->withInput($request->except('password', 'password_confirmation'));
        }

        // Check for duplicate email if provided
        if ($request->email) {
            $existingUserByEmail = User::where('email', $request->email)->first();
            if ($existingUserByEmail) {
                return back()->withErrors([
                    'email' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
                ])->withInput($request->except('password', 'password_confirmation'));
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'resident',
        ]);

        Auth::login($user);

        return redirect()->route('resident.dashboard')
            ->with('info', 'Akun berhasil dibuat! Silakan hubungi admin untuk menghubungkan akun dengan data warga Anda.');
    }

    public function showProfile()
    {
        return view('auth.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Check for duplicate email
        $existingUser = User::where('email', $request->email)->where('id', '!=', $user->id)->first();
        if ($existingUser) {
            return back()->withErrors([
                'email' => 'Email ini sudah digunakan oleh pengguna lain.',
            ])->withInput();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
