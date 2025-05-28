<?php

namespace App\Livewire\Unp;

use App\Models\Unp\Grupo;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Grupos extends Component
{
    public $grupos, $nome, $descricao, $grupo_id;
    public $isOpen = false;
    public $confirmDeleteId = null;


    public function render()
    {
        Log::info('Render method called');
        $this->grupos = Grupo::all();
        return view('livewire.unp.grupos');
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
        $this->grupo_id = '';
    }

    public function store()
    {
        Log::info('Store method called', ['grupo_id' => $this->grupo_id]);
        $this->validate([
            'nome' => 'required|string|min:3|max:250',
            'descricao' => 'required|string|min:3|max:6000',
        ]);

        Grupo::updateOrCreate(['id' => $this->grupo_id], [
            'nome' => $this->nome,
            'descricao' => $this->descricao
        ]);

        session()->flash(
            'message',
            $this->grupo_id ? 'Grupo atualizado com sucesso.' : 'Grupo criado com sucesso.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        Log::info('Edit method called', ['id' => $id]);
        $grupo = Grupo::findOrFail($id);
        $this->grupo_id = $id;
        $this->nome = $grupo->nome;
        $this->descricao = $grupo->descricao;
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
            Grupo::find($this->confirmDeleteId)->delete();
            session()->flash('message', 'Grupo deletado com sucesso.');
            $this->confirmDeleteId = null;
        }
    }
}
