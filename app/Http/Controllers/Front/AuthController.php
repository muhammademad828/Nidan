<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('front.auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            if (!$user->is_active) {
                Auth::logout();
                return back()->with('error', 'Account is deactivated.');
            }

            // Redirect based on role
            if (in_array($user->role, ['admin', 'staff'])) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('home', ['locale' => app()->getLocale()]));
        }

        return back()->with('error', 'Invalid credentials.')->onlyInput('email');
    }

    public function showRegister(): View
    {
        return view('front.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required|in:male,female',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('home', ['locale' => app()->getLocale()]);
    }

    public function account(): View
    {
        return view('front.auth.account', [
            'user' => Auth::user()
        ]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', app()->getLocale() == 'ar' ? 'تم تغيير كلمة السر بنجاح' : 'Password updated successfully.');
    }

    public function showForgotPassword(): View
    {
        return view('front.auth.forgot-password');
    }

    public function verifyForgotPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        $user = User::where('email', $request->email)
                    ->where('phone', $request->phone)
                    ->first();

        if (!$user) {
            return back()->with('error', app()->getLocale() == 'ar' ? 'البيانات غير متطابقة' : 'Information does not match our records.')->withInput();
        }

        // Store user ID in session temporarily to allow reset
        session(['reset_user_id' => $user->id]);

        return redirect()->route('password.reset.form', ['locale' => app()->getLocale()]);
    }

    public function showResetPassword(): View
    {
        if (!session('reset_user_id')) {
            return redirect()->route('password.request', ['locale' => app()->getLocale()]);
        }

        return view('front.auth.reset-password');
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $userId = session('reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request', ['locale' => app()->getLocale()]);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($userId);
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        session()->forget('reset_user_id');

        return redirect()->route('login', ['locale' => app()->getLocale()])
                         ->with('success', app()->getLocale() == 'ar' ? 'تم إعادة تعيين كلمة السر بنجاح، يمكنك تسجيل الدخول الآن' : 'Password reset successfully. You can login now.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home', ['locale' => app()->getLocale()]);
    }
}
