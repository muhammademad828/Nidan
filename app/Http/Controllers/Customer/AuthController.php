<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\EgyptianPhone;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', new EgyptianPhone, 'unique:users,phone'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $this->normalizePhone($validated['phone']),
            'password' => $validated['password'],
            'is_admin' => false,
        ]);

        Auth::login($user);

        app(CartService::class)->mergeGuestCart($user->id);

        $request->session()->flash('success', 'تم إنشاء حسابك بنجاح!');

        return Inertia::location(route('home'));
    }

    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->is_admin) {
                return Inertia::location(route('admin.dashboard'));
            }

            app(CartService::class)->mergeGuestCart(Auth::id());

            $request->session()->flash('success', 'مرحبًا بعودتك!');

            return Inertia::location($this->safeCustomerIntendedUrl($request, route('home')));
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Full browser navigation (not an Inertia XHR visit) so Blade storefront
     * is not rendered inside the Inertia root.
     */
    private function safeCustomerIntendedUrl(Request $request, string $fallback): string
    {
        $intended = $request->session()->pull('url.intended', $fallback);

        if (! is_string($intended) || $intended === '') {
            return $fallback;
        }

        if (str_starts_with($intended, '/')) {
            if (str_starts_with($intended, '//')) {
                return $fallback;
            }

            return $request->getSchemeAndHttpHost().$intended;
        }

        $host = parse_url($intended, PHP_URL_HOST);
        $appHost = parse_url($request->root(), PHP_URL_HOST);

        if (! $host || ! $appHost || strcasecmp((string) $host, (string) $appHost) !== 0) {
            return $fallback;
        }

        return $intended;
    }

    private function normalizePhone(string $phone): string
    {
        $cleaned = preg_replace('/[\s\-\(\)]+/', '', $phone);

        if (str_starts_with($cleaned, '+2')) {
            $cleaned = substr($cleaned, 2);
        } elseif (str_starts_with($cleaned, '002')) {
            $cleaned = substr($cleaned, 3);
        }

        return $cleaned;
    }
}
