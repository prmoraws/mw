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
        \Log::info('exportData chamado', ['user_id' => auth()->id()]);
        try {
            ini_set('memory_limit', '512M');
            set_time_limit(120);
            $cestas = Cesta::orderBy('cestas', 'desc')->orderBy('nome', 'asc')->get();

            Storage::disk('public')->makeDirectory('temp/thumbnails');

            if (!class_exists('Intervention\Image\Facades\Image')) {
                throw new \Exception('Pacote Intervention Image não está configurado corretamente');
            }

            $cestas = $cestas->map(function ($cesta) {
                if (!$cesta->foto) {
                    $cesta->foto = null;
                    return $cesta;
                }

                $filename = str_replace('uploads/', '', $cesta->foto);
                $fotoPath = base_path('uploads/' . $filename);
                $thumbnailPath = storage_path('app/public/temp/thumbnails/' . $filename);

                if (file_exists($thumbnailPath) && is_readable($thumbnailPath)) {
                    $cesta->foto = 'temp/thumbnails/' . $filename;
                } elseif (file_exists($fotoPath) && is_readable($fotoPath)) {
                    try {
                        Image::make($fotoPath)
                            ->resize(200, 150, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })
                            ->save($thumbnailPath, 40);
                        $cesta->foto = 'temp/thumbnails/' . $filename;
                        if (!file_exists($thumbnailPath)) {
                            $cesta->foto = null;
                        }
                    } catch (\Exception $e) {
                        $cesta->foto = null;
                    }
                } else {
                    $cesta->foto = null;
                }

                return $cesta;
            });

            $pdf = Pdf::loadView('livewire.evento.relatorio-cestas-pdf', [
                'cestas' => $cestas,
                'printDate' => now()->format('d/m/Y H:i:s')
            ])->setOptions([
                'isRemoteEnabled' => true,
                'dpi' => 72,
                'enable_local_file_access' => true,
                'chroot' => public_path('storage'),
                'enable_javascript' => true,
                'isPhpEnabled' => true
            ]);

            $pdfContent = $pdf->output();
            $filename = 'relatorio-cestas-' . now()->format('YmdHis') . '.pdf';

            // Salvar no disco public
            Storage::disk('public')->put('temp/' . $filename, $pdfContent);
            $storagePath = storage_path('app/public/temp/' . $filename);
            if (file_exists($storagePath)) {
                chmod($storagePath, 0644);
            } else {
                \Log::error('Falha ao salvar PDF em storage', ['path' => $storagePath]);
                throw new \Exception('Falha ao salvar PDF');
            }

            // Copiar para public/storage
            $publicPath = public_path('storage/temp/' . $filename);
            if (!file_exists(dirname($publicPath))) {
                mkdir(dirname($publicPath), 0755, true);
            }
            if (copy($storagePath, $publicPath)) {
                chmod($publicPath, 0644);
            } else {
                \Log::error('Falha ao copiar PDF para public/storage', ['from' => $storagePath, 'to' => $publicPath]);
                throw new \Exception('Falha ao copiar PDF');
            }

            $tempPdfs = session()->get('public_pdfs', []);
            $tempPdfs[] = 'temp/' . $filename;
            session()->put('public_pdfs', $tempPdfs);

            $url = url('/download-pdf/' . $filename);
            \Log::info('PDF gerado', ['url' => $url]);

            $this->message = 'PDF gerado com sucesso!';
            $this->dispatch('exportDataCompleted', url: $url);
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF', ['error' => $e->getMessage()]);
            $this->message = 'Erro ao gerar o PDF: ' . $e->getMessage();
            $this->dispatch('exportDataCompleted', error: 'Erro ao gerar o PDF: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.evento.dashboard')->layout('layouts.app', [
            'title' => 'Dashboard de Eventos'
        ]);
    }
}
