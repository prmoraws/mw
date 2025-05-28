<?php

namespace App\Livewire\Unp;

use App\Models\Unp\Curso;
use App\Models\Unp\Reeducando;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class Reeducandos extends Component
{
    use WithPagination;

    public $nome, $curso_id, $documento, $carga, $observacoes, $reeducando_id;
    public $isOpen = false;
    public $isViewOpen = false;
    public $confirmDeleteId = null;
    public $searchTerm = '';
    public $errorMessage = '';
    public $selectedReeducando;
    public $cursos = [];

    public function mount()
    {
        $this->cursos = Curso::pluck('nome', 'id')->toArray();
    }

    public function render()
    {
        $results = Reeducando::with('curso')
            ->when($this->searchTerm, function ($query) {
                $query->where('nome', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('nome', 'asc')
            ->paginate(20);

        return view('livewire.unp.reeducandos', [
            'results' => $results,
            'cursos' => $this->cursos,
        ]);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->errorMessage = '';
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
        $this->errorMessage = '';
    }

    public function view($id)
    {
        try {
            $this->selectedReeducando = Reeducando::with('curso')->findOrFail($id);
            $this->isViewOpen = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível carregar os dados do reeducando: ' . $e->getMessage());
        }
    }

    public function closeViewModal()
    {
        $this->isViewOpen = false;
        $this->selectedReeducando = null;
    }

    private function resetInputFields()
    {
        $this->nome = '';
        $this->curso_id = '';
        $this->documento = '';
        $this->carga = '';
        $this->observacoes = '';
        $this->reeducando_id = '';
        $this->errorMessage = '';
    }

    public function store()
    {
        try {
            $this->validate([
                'nome' => 'required|string|max:255',
                'curso_id' => 'required|exists:cursos,id',
                'documento' => 'required|string|max:255',
                'carga' => 'required|string|max:255',
                'observacoes' => 'nullable|string',
            ]);

            Reeducando::updateOrCreate(
                ['id' => $this->reeducando_id],
                [
                    'nome' => $this->nome,
                    'curso_id' => $this->curso_id,
                    'documento' => $this->documento,
                    'carga' => $this->carga,
                    'observacoes' => $this->observacoes,
                ]
            );

            session()->flash('message', $this->reeducando_id ? 'Reeducando atualizado com sucesso!' : 'Reeducando criado com sucesso!');
            $this->closeModal();
            $this->search();
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $this->errorMessage = implode(' ', $errors);
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro ao salvar: ' . $e->getMessage();
        }
    }

    public function edit($id)
    {
        try {
            $reeducando = Reeducando::findOrFail($id);
            $this->reeducando_id = $id;
            $this->nome = $reeducando->nome;
            $this->curso_id = $reeducando->curso_id;
            $this->documento = $reeducando->documento;
            $this->carga = $reeducando->carga;
            $this->observacoes = $reeducando->observacoes;
            $this->errorMessage = '';

            $this->openModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível carregar o reeducando para edição: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        Log::info("Confirmando exclusão do reeducando ID: {$id}");
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmDeleteId) {
            $reeducando = Reeducando::find($this->confirmDeleteId);
            if ($reeducando) {
                $reeducando->delete();
                session()->flash('message', 'Reeducando deletado com sucesso!');
                Log::info("Reeducando deletado com sucesso", ['id' => $this->confirmDeleteId]);
            } else {
                session()->flash('error', 'Reeducando não encontrado.');
                Log::error("Reeducando não encontrado para exclusão", ['id' => $this->confirmDeleteId]);
            }
            $this->confirmDeleteId = null;
            $this->search();
        }
    }
}
