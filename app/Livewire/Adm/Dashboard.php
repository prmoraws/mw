<?php

namespace App\Livewire\Adm;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $users;
    public $usersCount;
    public $cachesCount;
    public $jobsCount;
    public $sessionsCount;
    public $sessionsDetails;
    public $chartUsersPorPeriodo;
    public $chartJobsPorStatus;
    public $sessionDateFilter = '30_days'; // Filtro de sessions (7_days, 30_days, all)

    public function mount()
    {
        // Lista de Usuários (para o card com avatares)
        $this->users = User::select('id', 'name')->take(5)->get();

        // Contagem de Usuários
        $this->usersCount = User::count();

        // Contagem de Caches
        $this->cachesCount = DB::table('cache')->count();

        // Contagem de Jobs
        $this->jobsCount = DB::table('jobs')->count();

        // Contagem de Sessions e Detalhes (com filtro de data)
        $this->updateSessionsData();

        // Dados para Bar Chart: Usuários por Período (mês/ano)
        $usersPorPeriodo = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as periodo')
            ->selectRaw('COUNT(*) as total_users')
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get()
            ->pluck('total_users', 'periodo')
            ->toArray();

        $this->chartUsersPorPeriodo = [
            'labels' => array_keys($usersPorPeriodo),
            'data' => array_values($usersPorPeriodo),
        ];

        // Dados para Bar Chart: Jobs por Status
        $jobsPorStatus = DB::table('jobs')
            ->selectRaw('CASE 
                            WHEN reserved_at IS NULL THEN "Pendente"
                            WHEN attempts > 0 THEN "Falhado"
                            ELSE "Processado"
                         END as status')
            ->selectRaw('COUNT(*) as total_jobs')
            ->groupBy('status')
            ->pluck('total_jobs', 'status')
            ->toArray();

        $this->chartJobsPorStatus = [
            'labels' => array_keys($jobsPorStatus),
            'data' => array_values($jobsPorStatus),
        ];
    }

    public function updateSessionsData()
    {
        $query = DB::table('sessions');

        if ($this->sessionDateFilter === '7_days') {
            $query->where('last_activity', '>=', now()->subDays(7)->timestamp);
        } elseif ($this->sessionDateFilter === '30_days') {
            $query->where('last_activity', '>=', now()->subDays(30)->timestamp);
        }

        $this->sessionsCount = $query->count();
        $this->sessionsDetails = $query
            ->select('ip_address', 'user_agent')
            ->orderBy('last_activity', 'desc')
            ->take(5)
            ->get()
            ->toArray();
    }

    public function exportData()
    {
        // Placeholder para exportação de dados
    }

    public function render()
    {
        return view('livewire.adm.dashboard');
    }
}