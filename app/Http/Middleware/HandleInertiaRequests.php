<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Spatie\Permission\Models\Permission;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = auth()->user();

        $data = [];
        if ($user) {

            $can = [];
            $permissions = Permission::get();
            foreach ($permissions as $permission) {
                $can[$permission->name] = $user->can($permission->name);
            }

            $data = [
                'auth' => [
                    'user' => $request->user(),
                ],
                'flash' => [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                    'data' => $request->session()->get('data'),
                ],
                'can' => $can,
                'ziggy' => function () use ($request) {
                    return array_merge((new Ziggy)->toArray(), [
                        'location' => $request->url(),
                    ]);
                }
            ];
        }

        return array_merge(parent::share($request), $data);
    }
}
