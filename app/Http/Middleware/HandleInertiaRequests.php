<?php

namespace App\Http\Middleware;

use App\Services\CartService;
use App\Services\CmsService;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Default root template (storefront). Profile & customer auth use {@see rootView()}.
     */
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Isolated shell: no store navbar/hero CMS payload; uses dashboard.blade.php + frame-bust.
     */
    public function usesDashboardShell(Request $request): bool
    {
        if (str_starts_with($request->path(), 'dashboard')) {
            return false;
        }

        $path = $request->path();
        if (str_starts_with($path, 'profile')) {
            return true;
        }

        $authPrefixes = ['login', 'register', 'forgot-password', 'reset-password'];
        foreach ($authPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return true;
            }
        }

        $name = $request->route()?->getName() ?? '';

        return str_starts_with($name, 'profile.')
            || in_array($name, [
                'login',
                'customer.register',
                'customer.register.submit',
                'password.request',
                'password.email',
                'password.verify',
                'password.update',
                'password.reset.direct',
                'password.reset.process',
            ], true);
    }

    public function rootView(Request $request): string
    {
        return $this->usesDashboardShell($request) ? 'dashboard' : 'app';
    }

    public function share(Request $request): array
    {
        $isAdmin = str_starts_with($request->path(), 'dashboard');

        $shared = array_merge(parent::share($request), [
            'locale' => App::getLocale(),
            'flash'  => [
                'success' => $request->session()->get('success'),
                'error'   => $request->session()->get('error'),
            ],
        ]);

        if ($isAdmin) {
            $shared['auth'] = fn () => [
                'user' => Auth::user() ? [
                    'id'       => Auth::user()->id,
                    'name'     => Auth::user()->name,
                    'email'    => Auth::user()->email,
                    'is_admin' => Auth::user()->is_admin,
                ] : null,
            ];
        } else {
            $cms           = app(CmsService::class);
            $location      = app(LocationService::class);
            $cart          = app(CartService::class);
            $routeName     = $request->route()?->getName() ?? 'home';
            $pageKey       = $cms->getPageKey($routeName);
            $minimalStore  = $this->usesDashboardShell($request);

            $shared['auth'] = fn () => [
                'user' => Auth::user() ? [
                    'id'       => Auth::user()->id,
                    'name'     => Auth::user()->name,
                    'email'    => Auth::user()->email,
                    'phone'    => Auth::user()->phone,
                    'is_admin' => Auth::user()->is_admin,
                ] : null,
            ];

            $shared['settings'] = fn () => $cms->getAllSettingsGrouped();

            if ($minimalStore) {
                $shared['sections'] = fn () => [];
                $shared['content']  = fn () => $cms->getGlobalContent();
                $shared['seo']      = fn () => [];
            } else {
                $shared['sections'] = fn () => $cms->getPageSections($pageKey);
                $shared['content']  = fn () => array_merge(
                    $cms->getGlobalContent(),
                    $cms->getContentBlocks($pageKey)
                );
                $shared['seo'] = fn () => $cms->getSeoMeta($pageKey);
            }

            $shared['regions']       = fn () => $location->getRegions();
            $shared['currentRegion'] = fn () => $location->getCurrentRegion()?->only(['id', 'name', 'slug', 'delivery_fee']);
            $shared['cart']          = fn () => $cart->getCartData();

            $shared['visualEditorEnabled'] = fn () => (bool) (
                $request->user()?->is_admin
                && ! str_starts_with($request->path(), 'dashboard')
            );
        }

        return $shared;
    }
}
