<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Responses\LoginResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\ServiceProvider;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Autenticação personalizada
        Fortify::authenticateUsing(function (Request $request) {
            $user = \App\Models\User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user, $request->has('remember'));
                $request->session()->regenerate();
                Session::save();

                Log::info('Fortify Login Attempt', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames()->toArray(),
                    'auth_check' => Auth::check(),
                    'session_id' => $request->session()->getId(),
                ]);

                return $user;
            }

            Log::warning('Fortify Login Failed', [
                'email' => $request->email,
                'reason' => 'Invalid credentials',
            ]);

            return null;
        });

        // Personalizar pipeline de autenticação
        Fortify::authenticateThrough(function (Request $request) {
            return array_filter([
                config('fortify.limiters.login') ? null : \Laravel\Fortify\Actions\EnsureLoginIsNotThrottled::class,
                \Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable::class,
                \Laravel\Fortify\Actions\AttemptToAuthenticate::class,
                function ($request, $next) {
                    $user = $request->user();

                    if ($user) {
                        Log::info('Fortify: Redirecionando após login', [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'roles' => $user->getRoleNames()->toArray(),
                        ]);

                        if ($user->hasRole('adm')) {
                            return redirect()->route('dashboard.adm');
                        } elseif ($user->hasRole('unp')) {
                            return redirect()->route('dashboard.unp');
                        } elseif ($user->hasRole('evento')) {
                            return redirect()->route('dashboard.ev');
                        } elseif ($user->hasRole('universal')) {
                            return redirect()->route('dashboard.uni');
                        }
                    }

                    return $next($request);
                },
            ]);
        });

        // Personalizar resposta de login
        $this->app->singleton(LoginResponseContract::class, function ($app) {
            return new class implements LoginResponseContract {
                public function toResponse($request)
                {
                    $user = Auth::user();

                    Log::info('Fortify LoginResponse', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'roles' => $user->getRoleNames()->toArray(),
                    ]);

                    if ($user->hasRole('adm')) {
                        return redirect()->route('dashboard.adm');
                    } elseif ($user->hasRole('unp')) {
                        return redirect()->route('dashboard.unp');
                    } elseif ($user->hasRole('evento')) {
                        return redirect()->route('dashboard.ev');
                    } elseif ($user->hasRole('universal')) {
                        return redirect()->route('dashboard.uni');
                    }

                    return redirect()->route('dashboard');
                }
            };
        });
    }
}