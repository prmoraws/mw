<?php

namespace App\Livewire\Universal;

use App\Models\Universal\Pastor;
use App\Models\Universal\Igreja;
use App\Models\Universal\Pessoa;
use App\Models\Universal\Regiao;
use App\Models\Universal\Bloco;
use App\Models\Universal\Banner;
use App\Models\Universal\Categoria;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $pessoasCount;
    public $igrejasCount;
    public $pastoresCount;
    public $regioesCount;
    public $blocosCount;
    public $bannersCount;
    public $categoriasCount;
    public $igrejasPorRegiao;
    public $chartPessoasPorRegiao;
    public $chartBannersPorPeriodo;

    public function mount()
    {
        // Contagem de Pessoas
        $this->pessoasCount = Pessoa::count();

        // Contagem de Igrejas
        $this->igrejasCount = Igreja::count();

        // Contagem de Pastores
        $this->pastoresCount = Pastor::count();

        // Contagem de Regiões
        $this->regioesCount = Regiao::count();

        // Contagem de Blocos
        $this->blocosCount = Bloco::count();

        // Contagem de Banners
        $this->bannersCount = Banner::count();

        // Contagem de Categorias
        $this->categoriasCount = Categoria::count();

        // Igrejas por Região (para o card Igrejas)
        $this->igrejasPorRegiao = Igreja::select('regiao_id')
            ->selectRaw('COUNT(*) as total_igrejas')
            ->groupBy('regiao_id')
            ->with(['regiao' => function ($query) {
                $query->select('id', 'nome');
            }])
            ->get()
            ->map(function ($item) {
                return [
                    'regiao' => $item->regiao ? $item->regiao->nome : 'Sem Região',
                    'total_igrejas' => $item->total_igrejas,
                ];
            })
            ->toArray();

        // Dados para Bullet Chart: Pessoas por Região
        $pessoasPorRegiao = Pessoa::select('regiao_id')
            ->selectRaw('COUNT(*) as total_pessoas')
            ->groupBy('regiao_id')
            ->with(['regiao' => function ($query) {
                $query->select('id', 'nome');
            }])
            ->get()
            ->map(function ($item) {
                return [
                    'regiao' => $item->regiao ? $item->regiao->nome : 'Sem Região',
                    'total_pessoas' => $item->total_pessoas,
                ];
            })
            ->toArray();

        $avgPessoas = $this->pessoasCount / max(1, $this->regioesCount); // Meta: média de pessoas por região
        $this->chartPessoasPorRegiao = array_map(function ($item) use ($avgPessoas) {
            return [
                'title' => $item['regiao'],
                'ranges' => [$avgPessoas * 0.5, $avgPessoas, $avgPessoas * 1.5], // Faixas: 50%, 100%, 150% da média
                'measures' => [$item['total_pessoas']],
                'markers' => [$avgPessoas],
            ];
        }, $pessoasPorRegiao);

        // Dados para Bar Chart: Banners por Período (mês/ano)
        $bannersPorPeriodo = Banner::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as periodo')
            ->selectRaw('COUNT(*) as total_banners')
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get()
            ->pluck('total_banners', 'periodo')
            ->toArray();

        $this->chartBannersPorPeriodo = [
            'labels' => array_keys($bannersPorPeriodo),
            'data' => array_values($bannersPorPeriodo),
        ];
    }

    public function redirectTo($route)
    {
        return redirect()->route($route);
    }

    public function exportData()
    {
        // Lógica para exportação de dados (exemplo: CSV)
        // Implementar conforme necessidade
    }

    public function render()
    {
        return view('livewire.universal.dashboard');
    }
}
