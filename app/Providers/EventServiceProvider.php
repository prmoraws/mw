<?php

namespace App\Providers;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Logout::class => [
        function (Logout $event) {
            \Log::info('Evento logout', ['user_id' => $event->user ? $event->user->id : null]);
            $tempPdfs = Session::get('public_pdfs', []);
            \Log::info('PDFs na sessão:', ['pdfs' => $tempPdfs]);
            foreach ($tempPdfs as $pdf) {
                $storagePath = storage_path('app/public/' . $pdf);
                $publicPath = public_path('storage/' . $pdf);
                if (File::exists($storagePath)) {
                    if (@File::delete($storagePath)) {
                        \Log::info('PDF excluído', ['path' => $storagePath]);
                    } else {
                        \Log::error('Falha ao excluir PDF', ['path' => $storagePath, 'error' => error_get_last()]);
                    }
                } else {
                    \Log::warning('PDF não encontrado', ['path' => $storagePath]);
                }
                if (File::exists($publicPath)) {
                    if (@File::delete($publicPath)) {
                        \Log::info('PDF excluído', ['path' => $publicPath]);
                    } else {
                        \Log::error('Falha ao excluir PDF', ['path' => $publicPath, 'error' => error_get_last()]);
                    }
                } else {
                    \Log::warning('PDF não encontrado', ['path' => $publicPath]);
                }
            }
            Session::forget('public_pdfs');
            \Log::info('Sessão public_pdfs limpa');
        },
    ],
    ];

    public function boot()
    {
        //
    }
}
