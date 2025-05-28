<?php

namespace App\Livewire\Evento;

use App\Models\Evento\Cesta;
use App\Models\Evento\Instituicao;
use App\Models\Evento\Terreiro;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Dashboard extends Component
{
    public $terreirosCount;
    public $convidadosCount;
    public $instituicoesCount;
    public $instituicoesConvidados;
    public $totalGeralTerreirosInstituicoes;
    public $totalConvidadosGeral;
    public $totalCestas;
    public $totalCestasTerreiros;
    public $totalCestasInstituicoes;
    public $blocosConvidados;
    public $blocosTerreirosCount;
    public $chartBlocosConvidados;
    public $chartCestasDistribuicao;
    public $message;

    public function mount()
    {
        $this->terreirosCount = Terreiro::count();
        $this->convidadosCount = Terreiro::sum('convidados');
        $this->instituicoesCount = Instituicao::count();
        $this->instituicoesConvidados = Instituicao::sum('convidados');
        $this->totalGeralTerreirosInstituicoes = $this->terreirosCount + $this->instituicoesCount;
        $this->totalConvidadosGeral = $this->convidadosCount + $this->instituicoesConvidados;
        $this->totalCestas = Cesta::sum('cestas');
        $terreirosNomes = Terreiro::pluck('nome')->toArray();
        $this->totalCestasTerreiros = Cesta::whereIn('nome', $terreirosNomes)->distinct('nome')->count();
        $instituicoesNomes = Instituicao::pluck('nome')->toArray();
        $this->totalCestasInstituicoes = Cesta::whereIn('nome', $instituicoesNomes)->distinct('nome')->count();
        $this->blocosConvidados = Terreiro::select('bloco')
            ->selectRaw('SUM(convidados) as total_convidados')
            ->groupBy('bloco')
            ->orderBy('bloco')
            ->get()
            ->pluck('total_convidados', 'bloco')
            ->toArray();
        $this->blocosTerreirosCount = Terreiro::select('bloco')
            ->selectRaw('COUNT(*) as total_terreiros')
            ->groupBy('bloco')
            ->orderBy('bloco')
            ->get()
            ->pluck('total_blocos', 'bloco')
            ->toArray();
        $this->chartBlocosConvidados = [
            'labels' => array_keys($this->blocosConvidados),
            'data' => array_values($this->blocosConvidados),
        ];
        $this->chartCestasDistribuicao = [
            'labels' => ['Terreiros', 'Instituições'],
            'data' => [$this->totalCestasTerreiros, $this->totalCestasInstituicoes],
        ];
    }

    public function redirectTo($route)
    {
        return redirect()->route($route);
    }

    public function exportData()
    {
        try {
            ini_set('memory_limit', '512M');
            set_time_limit(120);

            $cestas = Cesta::orderBy('cestas', 'desc')->orderBy('nome', 'asc')->get();

            // Define os caminhos absolutos para o InfinityFree
            $basePath = '/home/vol1_1/infinityfree.com/if0_38241904/moraw.ct.ws/htdocs';
            $thumbnailDir = $basePath . '/storage/app/public/temp/thumbnails';
            $uploadsDir = $basePath . '/uploads';

            // Cria diretórios necessários
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            $cestas = $cestas->map(function ($cesta) use ($thumbnailDir, $uploadsDir) {
                if (!$cesta->foto) {
                    return $cesta;
                }

                $filename = basename($cesta->foto);
                $originalPath = $uploadsDir . '/' . $filename;
                $thumbnailPath = $thumbnailDir . '/' . $filename;

                // Verifica se a imagem original existe
                if (!file_exists($originalPath)) {
                    \Log::error('Imagem original não encontrada', ['path' => $originalPath]);
                    $cesta->foto = null;
                    return $cesta;
                }

                // Tenta criar a thumbnail se não existir
                if (!file_exists($thumbnailPath)) {
                    try {
                        $image = Image::make($originalPath);
                        $image->resize(200, 150, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                        $image->save($thumbnailPath, 70);
                        chmod($thumbnailPath, 0644);
                    } catch (\Exception $e) {
                        \Log::error('Erro ao criar thumbnail', [
                            'error' => $e->getMessage(),
                            'path' => $originalPath
                        ]);
                        // Usa a imagem original como fallback
                        $thumbnailPath = $originalPath;
                    }
                }

                // Usa caminho relativo para o PDF
                $cesta->foto = 'storage/app/public/temp/thumbnails/' . $filename;
                return $cesta;
            });

            // Configuração do PDF
            $pdf = Pdf::loadView('livewire.evento.relatorio-cestas-pdf', [
                'cestas' => $cestas,
                'printDate' => now()->format('d/m/Y H:i:s')
            ])->setOptions([
                'isRemoteEnabled' => true,
                'dpi' => 72,
                'enable_local_file_access' => true,
                'chroot' => $basePath,
                'enable_javascript' => false,
                'isPhpEnabled' => true
            ]);

            $pdfContent = $pdf->output();
            $filename = 'relatorio-cestas-' . now()->format('YmdHis') . '.pdf';
            $pdfPath = $basePath . '/storage/temp/' . $filename;

            // Garante que o diretório existe
            if (!file_exists(dirname($pdfPath))) {
                mkdir(dirname($pdfPath), 0755, true);
            }

            file_put_contents($pdfPath, $pdfContent);
            chmod($pdfPath, 0644);

            $url = url('/storage/temp/' . $filename);
            $this->dispatch('exportDataCompleted', url: $url);
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF', ['error' => $e->getMessage()]);
            $this->dispatch('exportDataCompleted', error: $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.evento.dashboard')->layout('layouts.app', [
            'title' => 'Dashboard de Eventos'
        ]);
    }
}
