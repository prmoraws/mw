<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteTempPdfsOnLogout
{
    public function handle(Logout $event)
    {
        Log::info('Evento Logout disparado', [
            'user_id' => $event->user->id,
            'temp_pdfs' => session()->get('temp_pdfs', [])
        ]);

        // Excluir PDFs listados na sessão
        $tempPdfs = session()->get('temp_pdfs', []);
        foreach ($tempPdfs as $pdf) {
            if (Storage::disk('public')->exists($pdf)) {
                Storage::disk('public')->delete($pdf);
                Log::info('PDF temporário excluído', ['file' => $pdf]);
            }
        }

        // Excluir todos os PDFs em temp/, exceto thumbnails/
        $allPdfs = Storage::disk('public')->files('temp');
        foreach ($allPdfs as $pdf) {
            if (preg_match('/\.pdf$/', $pdf) && !str_contains($pdf, 'thumbnails/')) {
                Storage::disk('public')->delete($pdf);
                Log::info('PDF adicional excluído', ['file' => $pdf]);
            }
        }

        // Limpar a sessão
        session()->forget('temp_pdfs');
    }
}
