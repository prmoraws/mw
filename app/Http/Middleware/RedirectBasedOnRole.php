<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    public function handle(Request $request, Closure $next): Response
    {
        \Illuminate\Support\Facades\Log::debug('RedirectBasedOnRole: Middleware executado', [
            'url' => $request->url(),
            'method' => $request->method(),
            'route_name' => $request->route() ? $request->route()->getName() : 'none',
            'user_id' => $request->user() ? $request->user()->id : null,
            'user_email' => $request->user() ? $request->user()->email : null,
            'intended_url' => \Illuminate\Support\Facades\Redirect::intended()->getTargetUrl(),
        ]);

        $response = $next($request);

        // Verifica se o usuário está autenticado e está tentando acessar /dashboard
        if ($request->user() && $request->is('dashboard')) {
            $user = $request->user();

            \Illuminate\Support\Facades\Log::info('RedirectBasedOnRole: Redirecionando usuário', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                'current_route' => $request->route() ? $request->route()->getName() : 'none',
            ]);

            if ($user->hasRole('adm') && !$request->is('adm/*')) {
                return redirect()->route('dashboard.adm');
            } elseif ($user->hasRole('unp') && !$request->is('unp/*')) {
                return redirect()->route('dashboard.unp');
            } elseif ($user->hasRole('evento') && !$request->is('evento/*')) {
                return redirect()->route('dashboard.ev');
            } elseif ($user->hasRole('universal') && !$request->is('universal/*')) {
                return redirect()->route('dashboard.uni');
            }
        }

        return $response;
    }
}
