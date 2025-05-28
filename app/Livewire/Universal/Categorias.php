<?php

namespace App\Livewire\Universal;

use App\Models\Universal\Categoria;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Categorias extends Component
{
    public $categorias, $nome, $descricao, $categoria_id;
    public $isOpen = false;
    public $confirmDeleteId = null;

    public function render()
    {
        Log::info('Render method called');
        $this->categorias = Categoria::all();
        return view('livewire.universal.categorias');
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
    }

    private function resetInputFields()
    {
        $this->nome = '';
        $this->descricao = '';
        $this->categoria_id = '';
    }

    public function store()
    {
        Log::info('Store method called', ['categoria_id' => $this->categoria_id]);
        $this->validate([
            'nome' => 'required|string|min:3|max:250',
            'descricao' => 'required|string|min:3|max:6000',
        ]);

        Categoria::updateOrCreate(['id' => $this->categoria_id], [
            'nome' => $this->nome,
            'descricao' => $this->descricao
        ]);

        session()->flash(
            'message',
            $this->categoria_id ? 'Categoria atualizada com sucesso.' : 'Categoria criada com sucesso.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        Log::info('Edit method called', ['id' => $id]);
        $categoria = Categoria::findOrFail($id);
        $this->categoria_id = $id;
        $this->nome = $categoria->nome;
        $this->descricao = $categoria->descricao;
        $this->isOpen = true;
    }

    public function confirmDelete($id)
    {
        Log::info('ConfirmDelete method called', ['id' => $id]);
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        Log::info('Delete method called', ['confirmDeleteId' => $this->confirmDeleteId]);
        if ($this->confirmDeleteId) {
            Categoria::find($this->confirmDeleteId)->delete();
            session()->flash('message', 'Categoria deletada com sucesso.');
            $this->confirmDeleteId = null;
        }
    }
}
