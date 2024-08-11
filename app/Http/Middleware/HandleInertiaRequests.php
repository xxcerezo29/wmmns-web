<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

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
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'flash'=> [
                'status' => fn () => $request->session()->get('status'),
                'message' => fn() => $request->session()->get('message'),
            ],
            'currentRouteName' => $this->getRouteName($request)
        ];
    }

    public function getRouteName(Request $request): string
    {
        $path = $request->path(); // Get the URL path
        $segments = explode('/', $path); // Split path into segments
        $routeName = '';

        // Check if path matches the pattern
        if (Route::currentRouteName() === 'plans.index') {
            $planName = urldecode($segments[3]); // Decode URL-encoded plan name
           
            $routeName = 'plans.index.' . $planName;      
                
        } elseif(Route::currentRouteName() === 'plans.edit' || Route::currentRouteName() === 'plans.add' || Route::currentRouteName() === 'plans.view') {
            $planName = urldecode($segments[4]); // Decode URL-encoded plan name
           
            $routeName = 'plans.index.' . $planName;  
        }else {
            $routeName = Route::currentRouteName();
        }

        return $routeName??'';
    }
}
