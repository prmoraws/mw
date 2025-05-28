<?php

namespace App\Livewire\Evento;

use App\Models\Evento\Cesta;
use App\Models\Evento\Terreiro;
use App\Models\Evento\Instituicao;
use Livewire\Component;

class Entregas extends Component
{
    public $search = '';
    public $selectedEntidade = null;

    public function render()
    {
        // Terreiros com busca e ordenação
        $terreiros = Terreiro::select('terreiros.id', 'terreiros.nome', 'terreiros.convidados')
            ->selectRaw("'terreiro' as tipo")
            ->leftJoin('cestas', 'terreiros.nome', '=', 'cestas.nome')
            ->where('terreiros.nome', 'like', '%' . $this->search . '%')
            ->orderByRaw('cestas.cestas IS NULL ASC') // Primeiro os que receberam cestas
            ->orderBy('terreiros.nome', 'asc') // Depois por nome em ordem alfabética
            ->get();

        // Instituições com busca e ordenação
        $instituicoes = Instituicao::select('instituicoes.id', 'instituicoes.nome', 'instituicoes.convidados')
            ->selectRaw("'instituicao' as tipo")
            ->leftJoin('cestas', 'instituicoes.nome', '=', 'cestas.nome')
            ->where('instituicoes.nome', 'like', '%' . $this->search . '%')
            ->orderByRaw('cestas.cestas IS NULL ASC') // Primeiro os que receberam cestas
            ->orderBy('instituicoes.nome', 'asc') // Depois por nome em ordem alfabética
            ->get();

        // Mapear terreiros com dados de cestas
        $terreirosMapped = $terreiros->map(function ($terreiro) {
            $cesta = Cesta::where('nome', $terreiro->nome)->first();
            return [
                'id' => $terreiro->id,
                'nome' => strtoupper($terreiro->nome), // Converter para caixa alta
                'tipo' => $terreiro->tipo,
                'cestas' => $cesta ? $cesta->cestas : null,
                'convidados' => $terreiro->convidados,
                'foto' => $cesta ? $cesta->foto : null,
                'observacao' => $cesta ? $cesta->observacao : null,
            ];
        });

        // Mapear instituições com dados de cestas
        $instituicoesMapped = $instituicoes->map(function ($instituicao) {
            $cesta = Cesta::where('nome', $instituicao->nome)->first();
            return [
                'id' => $instituicao->id,
                'nome' => strtoupper($instituicao->nome), // Converter para caixa alta
                'tipo' => $instituicao->tipo,
                'cestas' => $cesta ? $cesta->cestas : null,
                'convidados' => $instituicao->convidados,
                'foto' => $cesta ? $cesta->foto : null,
                'observacao' => $cesta ? $cesta->observacao : null,
            ];
        });

        return view('livewire.evento.entregas', [
            'terreiros' => $terreirosMapped,
            'instituicoes' => $instituicoesMapped,
        ]);
    }

    public function viewDetails($id, $tipo)
    {
        $entidade = $tipo === 'terreiro' ? Terreiro::findOrFail($id) : Instituicao::findOrFail($id);
        $cesta = Cesta::where('nome', $entidade->nome)->first();

        $this->selectedEntidade = [
            'nome' => strtoupper($entidade->nome), // Converter para caixa alta
            'cestas' => $cesta ? $cesta->cestas : null,
            'foto' => $cesta ? $cesta->foto : null,
            'observacao' => $cesta ? $cesta->observacao : null,
        ];
    }

    public function closeModal()
    {
        $this->selectedEntidade = null;
    }
}