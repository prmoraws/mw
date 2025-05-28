<?php

   namespace App\Listeners;

   use Illuminate\Auth\Events\Login;
   use Illuminate\Http\RedirectResponse;

   class RedirectAfterLogin
   {
       public function handle(Login $event): ?RedirectResponse
       {
           $user = $event->user;

           \Illuminate\Support\Facades\Log::info('Login Event', [
               'user_id' => $user->id,
               'email' => $user->email,
               'roles' => $user->getRoleNames()->toArray(),
           ]);

           if ($user->hasRole('adm')) {
               return redirect()->route('dashboard');
           } elseif ($user->hasRole('unp')) {
               return redirect()->route('dashboard.unp');
           } elseif ($user->hasRole('evento')) {
               return redirect()->route('dashboard.ev');
           } elseif ($user->hasRole('universal')) {
               return redirect()->route('dashboard.uni');
           }

           return redirect()->route('dashboard');
       }
   }