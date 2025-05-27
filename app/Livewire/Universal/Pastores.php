<?php

namespace App\Livewire\Universal;

use App\Models\Universal\Pastor;
use Livewire\Component;
use Livewire\WithPagination;

class Pastores extends Component
{
    use WithPagination;

    public $isOpen = false;
    public $isViewOpen = false;
    public $confirmDeleteId = null;
    public $pastor_id;
    public $sede, $pastor, $telefone, $esposa, $tel_epos;
    public $searchTerm = '';
    public $errorMessage = '';
    public $selectedPastor;

    public function render()
    {
        $results = $this->searchTerm !== null
            ? Pastor::where('sede', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('pastor', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('telefone', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('esposa', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('tel_epos', 'like', '%' . $this->searchTerm . '%')
            ->paginate(20)
            : [];

        return view('livewire.universal.pastores', [
            'results' => $results,
        ]);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    public function openViewModal()
    {
        $this->isViewOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewOpen = false;
        $this->selectedPastor = null;
    }

    private function resetInputFields()
    {
        $this->pastor_id = null;
        $this->sede = '';
        $this->pastor = '';
        $this->telefone = '';
        $this->esposa = '';
        $this->tel_epos = '';
        $this->errorMessage = '';
    }

    public function store()
    {
        try {
            $this->validate([
                'sede' => 'required',
                'pastor' => 'required',
                'telefone' => 'required',
                'esposa' => 'required',
                'tel_epos' => 'required',
            ]);

            Pastor::updateOrCreate(['id' => $this->pastor_id], [
                'sede' => $this->sede,
                'pastor' => $this->pastor,
                'telefone' => $this->telefone,
                'esposa' => $this->esposa,
                'tel_epos' => $this->tel_epos,
            ]);

            session()->flash('message', $this->pastor_id ? 'Pastor atualizado com sucesso.' : 'Pastor criado com sucesso.');
            $this->closeModal();
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro ao salvar: ' . $e->getMessage();
        }
    }

    public function edit($id)
    {
        try {
            $pastor = Pastor::findOrFail($id);
            \Log::info("Editando pastor ID: {$id}", [
                'sede' => $pastor->sede,
                'pastor' => $pastor->pastor,
                'telefone' => $pastor->telefone,
                'esposa' => $pastor->esposa,
                'tel_epos' => $pastor->tel_epos,
            ]);

            $this->pastor_id = $id;
            $this->sede = $pastor->sede;
            $this->pastor = $pastor->pastor;
            $this->telefone = $pastor->telefone;
            $this->esposa = $pastor->esposa;
            $this->tel_epos = $pastor->tel_epos;

            $this->openModal();
        } catch (\Exception $e) {
            \Log::error("Erro ao editar pastor ID: {$id}", ['exception' => $e->getMessage()]);
            $this->errorMessage = 'Não foi possível carregar o pastor para edição.';
        }
    }

    public function view($id)
    {
        try {
            $this->selectedPastor = Pastor::findOrFail($id);
            $this->openViewModal();
        } catch (\Exception $e) {
            \Log::error("Erro ao visualizar pastor ID: {$id}", ['exception' => $e->getMessage()]);
            $this->errorMessage = 'Não foi possível carregar os dados do pastor.';
        }
    }

    public function confirmDelete($id)
    {
        \Log::info("Confirmando exclusão do pastor ID: {$id}");
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmDeleteId) {
            $pastor = Pastor::find($this->confirmDeleteId);
            if ($pastor) {
                $pastor->delete();
                session()->flash('message', 'Pastor deletado com sucesso.');
                \Log::info("Pastor deletado com sucesso", ['id' => $this->confirmDeleteId]);
            } else {
                session()->flash('error', 'Pastor não encontrado.');
                \Log::error("Pastor não encontrado para exclusão", ['id' => $this->confirmDeleteId]);
            }
            $this->confirmDeleteId = null;
            $this->search();
        }
    }
}
