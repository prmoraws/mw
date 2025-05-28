<?php

namespace App\Livewire\Universal;

use App\Models\Universal\Bloco;
use App\Models\Universal\Regiao;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class Regiaos extends Component
{
    use WithPagination;

    public $nome, $bloco_id, $regiao_id;
    public $isOpen = false;
    public $confirmDeleteId = null;
    public $searchTerm = '';

    public function render()
    {
        Log::info('Render method called', ['searchTerm' => $this->searchTerm]);
        $results = $this->searchTerm !== ''
            ? Regiao::with('bloco')->where('nome', 'like', '%' . $this->searchTerm . '%')->paginate(20)
            : Regiao::with('bloco')->paginate(20);

        return view('livewire.universal.regiaos', [
            'results' => $results,
            'blocos' => Bloco::all(),
        ]);
    }

    public function search()
    {
        Log::info('Search method called', ['searchTerm' => $this->searchTerm]);
        $this->resetPage();
        $this->resetExcept(['searchTerm', 'results']);
    }

    public function create()
    {
        Log::info('Create method called');
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        Log::info('CloseModal method called');
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->nome = '';
        $this->bloco_id = '';
        $this->regiao_id = '';
    }

    public function store()
    {
        Log::info('Store method called', ['regiao_id' => $this->regiao_id, 'bloco_id' => $this->bloco_id]);
        $this->validate([
            'nome' => 'required|string|min:3|max:250',
            'bloco_id' => 'required|exists:blocos,id',
        ]);

        Regiao::updateOrCreate(['id' => $this->regiao_id], [
            'nome' => $this->nome,
            'bloco_id' => $this->bloco_id,
        ]);

        session()->flash('message', $this->regiao_id ? 'Região atualizada com sucesso.' : 'Região criada com sucesso.');
        $this->search();
        $this->closeModal();
    }

    public function edit($id)
    {
        Log::info('Edit method called', ['id' => $id, 'regiao' => Regiao::find($id)?->toArray()]);
        $regiao = Regiao::findOrFail($id);
        $this->regiao_id = $id;
        $this->nome = $regiao->nome;
        $this->bloco_id = $regiao->bloco_id;
        $this->isOpen = true;
    }

    public function confirmDelete($id)
    {
        Log::info('ConfirmDelete method called', ['id' => $id, 'regiao' => Regiao::find($id)?->toArray()]);
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        Log::info('Delete method called', ['confirmDeleteId' => $this->confirmDeleteId]);
        if ($this->confirmDeleteId) {
            Regiao::find($this->confirmDeleteId)->delete();
            session()->flash('message', 'Região deletada com sucesso.');
            $this->confirmDeleteId = null;
            $this->search();
        }
    }
}
