<?php

namespace App\Livewire\Unp;

use App\Models\Unp\Curso;
use App\Models\Unp\Reeducando;
use App\Models\Unp\Formatura;
use App\Models\Unp\Instrutor;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $cursosCount;
    public $instrutoresCount;
    public $gruposCount;
    public $formaturasCount;
    public $reeducandosCount;
    public $cursos;
    public $chartCursosPorUnidade;
    public $chartFormaturasPorMes;

    public function mount()
    {
        // Contagem de Cursos
        $this->cursosCount = Curso::where('status', '!=', 'FINALIZADO')->count();

        // Contagem de Instrutores
        $this->instrutoresCount = Instrutor::count();

        // Contagem de Grupos (usando distinct 'nome' como proxy para turmas)
        $this->gruposCount = Curso::distinct('nome')->count('nome');

        // Contagem de Formaturas
        $this->formaturasCount = Formatura::count();

        // Contagem de Reeducandos
        $this->reeducandosCount = Reeducando::count();

        // Lógica para Cursos (adaptada do dashboard original)
        $this->cursos = Curso::select('nome', 'unidade', 'fim')
            ->where('status', '!=', 'FINALIZADO')
            ->orderBy('fim')
            ->get()
            ->map(function ($curso) {
                $fim = Carbon::parse($curso->fim);
                $now = Carbon::now();
                $startOfMonth = $now->copy()->startOfMonth();
                $endOfMonth = $now->copy()->endOfMonth();
                $startOfNextMonth = $now->copy()->addMonth()->startOfMonth();
                $endOfNextMonth = $now->copy()->addMonth()->endOfMonth();

                $alertColor = null;
                if ($fim->between($startOfMonth, $endOfMonth)) {
                    $alertColor = 'red';
                } elseif ($fim->between($startOfNextMonth, $endOfNextMonth)) {
                    $alertColor = 'green';
                }

                return [
                    'nome' => $curso->nome,
                    'unidade' => $curso->unidade,
                    'fim' => $fim->format('d/m/Y'),
                    'alert_color' => $alertColor,
                ];
            })
            ->toArray();

        // Dados para Gráfico de Cursos por Unidade
        $cursosPorUnidade = Curso::select('unidade')
            ->selectRaw('COUNT(*) as total_cursos')
            ->groupBy('unidade')
            ->orderBy('unidade')
            ->get()
            ->pluck('total_cursos', 'unidade')
            ->toArray();

        $this->chartCursosPorUnidade = [
            'labels' => array_keys($cursosPorUnidade),
            'data' => array_values($cursosPorUnidade),
        ];

        // Dados para Gráfico de Formaturas por Mês
        $formaturasPorMes = Formatura::selectRaw('DATE_FORMAT(data, "%Y-%m") as mes, COUNT(*) as total_formaturas')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->pluck('total_formaturas', 'mes')
            ->toArray();

        $this->chartFormaturasPorMes = [
            'labels' => array_keys($formaturasPorMes),
            'data' => array_values($formaturasPorMes),
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
        return view('livewire.unp.dashboard');
    }
}