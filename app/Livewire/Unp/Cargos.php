<?php

namespace App\Livewire\Unp;

use App\Models\Unp\Cargo;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Cargos extends Component
{
    public $cargos, $nome, $cargo_id;
    public $isOpen = false;
    public $confirmDeleteId = null;

    public function render()
    {
        Log::info('Render method called');
        $this->cargos = Cargo::all();
        return view('livewire.unp.cargos');
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
        $this->cargo_id = '';
    }

    public function store()
    {
        Log::info('Store method called', ['cargo_id' => $this->cargo_id]);
        $this->validate([
            'nome' => 'required|string|min:3|max:250',
        ]);

        Cargo::updateOrCreate(['id' => $this->cargo_id], [
            'nome' => $this->nome
        ]);

        session()->flash(
            'message',
            $this->cargo_id ? 'Cargo atualizado com sucesso.' : 'Cargo criado com sucesso.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        Log::info('Edit method called', ['id' => $id]);
        $cargo = Cargo::findOrFail($id);
        $this->cargo_id = $id;
        $this->nome = $cargo->nome;
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
            Cargo::find($this->confirmDeleteId)->delete();
            session()->flash('message', 'Cargo deletado com sucesso.');
            $this->confirmDeleteId = null;
        }
    }
}
