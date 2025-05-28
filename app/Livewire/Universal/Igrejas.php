<?php

namespace App\Livewire\Universal;

use App\Models\Universal\Bloco;
use App\Models\Universal\Igreja;
use App\Models\Universal\Regiao;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class Igrejas extends Component
{
    use WithPagination;

    public $nome, $regiao_id, $bloco_id, $igreja_id;
    public $isOpen = false;
    public $confirmDeleteId = null;
    public $searchTerm = '';

    public function render()
    {
        Log::info('Render method called', ['searchTerm' => $this->searchTerm]);
        $results = $this->searchTerm !== ''
            ? Igreja::with(['regiao', 'bloco'])->where('nome', 'like', '%' . $this->searchTerm . '%')->paginate(20)
            : Igreja::with(['regiao', 'bloco'])->paginate(20);

        return view('livewire.universal.igrejas', [
            'results' => $results,
            'regiaos' => Regiao::all(),
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
        $this->regiao_id = '';
        $this->bloco_id = '';
        $this->igreja_id = '';
    }

    public function store()
    {
        Log::info('Store method called', ['igreja_id' => $this->igreja_id, 'regiao_id' => $this->regiao_id, 'bloco_id' => $this->bloco_id]);
        $this->validate([
            'nome' => 'required|string|min:3|max:250',
            'regiao_id' => 'required|exists:regiaos,id',
            'bloco_id' => 'required|exists:blocos,id',
        ]);

        Igreja::updateOrCreate(['id' => $this->igreja_id], [
            'nome' => $this->nome,
            'regiao_id' => $this->regiao_id,
            'bloco_id' => $this->bloco_id,
        ]);

        session()->flash('message', $this->igreja_id ? 'Igreja atualizada com sucesso.' : 'Igreja criada com sucesso.');
        $this->search();
        $this->closeModal();
    }

    public function edit($id)
    {
        Log::info('Edit method called', ['id' => $id, 'igreja' => Igreja::find($id)?->toArray()]);
        $igreja = Igreja::findOrFail($id);
        $this->igreja_id = $id;
        $this->nome = $igreja->nome;
        $this->regiao_id = $igreja->regiao_id;
        $this->bloco_id = $igreja->bloco_id;
        $this->isOpen = true;
    }

    public function confirmDelete($id)
    {
        Log::info('ConfirmDelete method called', ['id' => $id, 'igreja' => Igreja::find($id)?->toArray()]);
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        Log::info('Delete method called', ['confirmDeleteId' => $this->confirmDeleteId]);
        if ($this->confirmDeleteId) {
            Igreja::find($this->confirmDeleteId)->delete();
            session()->flash('message', 'Igreja deletada com sucesso.');
            $this->confirmDeleteId = null;
            $this->search();
        }
    }
}
