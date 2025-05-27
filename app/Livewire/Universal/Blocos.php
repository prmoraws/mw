<?php

namespace App\Livewire\Universal;

use App\Models\Universal\Bloco;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class Blocos extends Component
{
    use WithPagination;

    public $nome, $bloco_id;
    public $isOpen = false;
    public $confirmDeleteId = null;
    public $searchTerm = '';

    public function render()
    {
        Log::info('Render method called', ['searchTerm' => $this->searchTerm]);
        $results = $this->searchTerm !== ''
            ? Bloco::where('nome', 'like', '%' . $this->searchTerm . '%')->paginate(20)
            : Bloco::paginate(20);

        return view('livewire.universal.blocos', [
            'results' => $results,
        ]);
    }

    public function search()
    {
        Log::info('Search method called', ['searchTerm' => $this->searchTerm]);
        $this->resetPage();
        $this->resetExcept(['searchTerm', 'results']); // Limpa estados exceto searchTerm e results
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
    }

    public function store()
    {
        Log::info('Store method called', ['bloco_id' => $this->bloco_id]);
        $this->validate([
            'nome' => 'required|string|min:3|max:250',
        ]);

        Bloco::updateOrCreate(['id' => $this->bloco_id], [
            'nome' => $this->nome,
        ]);

        session()->flash('message', $this->bloco_id ? 'Bloco atualizado com sucesso.' : 'Bloco criado com sucesso.');
        $this->search();
        $this->closeModal();
    }

    public function edit($id)
    {
        Log::info('Edit method called', ['id' => $id, 'bloco' => Bloco::find($id)?->toArray()]);
        $bloco = Bloco::findOrFail($id);
        $this->bloco_id = $id;
        $this->nome = $bloco->nome;
        $this->isOpen = true;
    }

    public function confirmDelete($id)
    {
        Log::info('ConfirmDelete method called', ['id' => $id, 'bloco' => Bloco::find($id)?->toArray()]);
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        Log::info('Delete method called', ['confirmDeleteId' => $this->confirmDeleteId]);
        if ($this->confirmDeleteId) {
            Bloco::find($this->confirmDeleteId)->delete();
            session()->flash('message', 'Bloco deletado com sucesso.');
            $this->confirmDeleteId = null;
            $this->search();
        }
    }

    public function teste($id)
    {
        Log::info('Teste method called', ['id' => $id, 'bloco' => Bloco::find($id)?->toArray()]);
        session()->flash('message', 'Teste clicado para o bloco ID: ' . $id);
    }
}
