<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use App\Rules\EgyptianPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class OtpController extends Controller
{
    public function showForgotPassword()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function sendOtp(Request $request)
    {
        // Validate email and phone using Egyptian phone rule
        $request->validate([
            'email'  => ['required', 'email'],
            'phone'  => ['required', 'string', new EgyptianPhone()],
        ]);

        // Normalize phone number for comparison
        $phone = preg_replace('/[\s\-\(\)]+/', '', $request->input('phone'));
        if (str_starts_with($phone, '+2')) {
            $phone = substr($phone, 2);
        } elseif (str_starts_with($phone, '002')) {
            $phone = substr($phone, 3);
        }

        // Find user by BOTH email and phone (identity verification)
        $user = User::where('email', $request->input('email'))
            ->where('phone', $phone)
            ->first();

        if (! $user) {
            Log::warning('Password reset failed: invalid email/phone combination', [
                'email' => $request->input('email'),
                'phone' => $phone,
            ]);
            return back()->withErrors([
                'email' => 'البريد الإلكتروني أو رقم الهاتف غير صحيح',
            ])->withFragment('auth-error');
        }

        // Generate signed URL for direct password reset (bypass OTP email)
        $signedUrl = URL::temporarySignedRoute(
            'password.reset.direct',
            now()->addMinutes(15),
            ['email' => $user->email]
        );

        Log::info('Password reset link generated (local bypass)', [
            'email' => $user->email,
            'phone' => $phone,
        ]);

        // Redirect directly to password reset with signed token
        return redirect($signedUrl)
            ->with('success', 'تم التحقق من هويتك. قم بتعيين كلمة مرور جديدة.');
    }

    public function showVerifyOtp(Request $request)
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->query('email', ''),
        ]);
    }

    public function verifyAndReset(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email', 'exists:users,email'],
            'otp'      => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (! $user->verifyOtp($request->input('otp'))) {
            return back()->withErrors([
                'otp' => 'رمز التحقق غير صحيح أو منتهي الصلاحية.',
            ]);
        }

        $user->update(['password' => $request->input('password')]);
        $user->clearOtp();

        return redirect()->route('login')
            ->with('success', 'تم تغيير كلمة المرور بنجاح. سجل الدخول الآن.');
    }

    /**
     * Show direct password reset form (via signed URL bypass)
     */
    public function showDirectReset(Request $request)
    {
        $email = $request->route('email');

        // Verify the signed URL
        if (! $request->hasValidSignature()) {
            abort(403, 'رابط التحقق غير صالح أو منتهي الصلاحية');
        }

        // Verify user exists
        $user = User::where('email', $email)->first();
        if (! $user) {
            abort(404, 'المستخدم غير موجود');
        }

        return Inertia::render('Auth/ResetPassword', [
            'email' => $email,
            'direct' => true, // Flag to indicate direct reset mode (no OTP)
        ]);
    }

    /**
     * Process direct password reset (via signed URL bypass)
     */
    public function processDirectReset(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::where('email', $request->input('email'))->first();

        // Update password
        $user->update(['password' => $request->input('password')]);

        Log::info('Password reset completed via direct URL', ['email' => $user->email]);

        return redirect()->route('login')
            ->with('success', 'تم تغيير كلمة المرور بنجاح. سجل الدخول الآن.');
    }
}
