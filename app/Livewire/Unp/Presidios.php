<?php

namespace App\Livewire\Unp;

use App\Models\Unp\Presidio;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\ValidationException;

class Presidios extends Component
{
    use WithPagination;

    public $nome, $diretor, $contato_diretor, $adjunto, $contato_adjunto, $laborativa, $contato_laborativa, $visita, $interno;
    public $presidio_id, $searchTerm = '', $sortField = 'nome', $sortDirection = 'asc';
    public $isOpen = false, $isViewOpen = false, $confirmDeleteId = null;
    public $selectedPresidio, $errorMessage = '';

    public function render()
    {
        $query = Presidio::query()
            ->when($this->searchTerm, function ($query) {
                $query->where('nome', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('diretor', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('adjunto', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $presidios = $query->paginate(20);

        return view('livewire.unp.presidios', [
            'presidios' => $presidios,
        ])->layout('layouts.app');
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

    public function openViewModal()
    {
        $this->isViewOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewOpen = false;
        $this->selectedPresidio = null;
    }

    private function resetInputFields()
    {
        $this->nome = '';
        $this->diretor = '';
        $this->contato_diretor = '';
        $this->adjunto = '';
        $this->contato_adjunto = '';
        $this->laborativa = '';
        $this->contato_laborativa = '';
        $this->visita = '';
        $this->interno = '';
        $this->presidio_id = null;
    }

    public function save()
    {
        try {
            $this->validate([
                'nome' => 'required|string|max:255',
                'diretor' => 'required|string|max:255',
                'contato_diretor' => 'required|string|max:255',
                'adjunto' => 'nullable|string|max:255',
                'contato_adjunto' => 'nullable|string|max:255',
                'laborativa' => 'nullable|string|max:255',
                'contato_laborativa' => 'nullable|string|max:255',
                'visita' => 'nullable|string|max:1000',
                'interno' => 'nullable|string|max:1000',
            ]);

            Presidio::updateOrCreate(
                ['id' => $this->presidio_id],
                [
                    'nome' => $this->nome,
                    'diretor' => $this->diretor,
                    'contato_diretor' => $this->contato_diretor,
                    'adjunto' => $this->adjunto,
                    'contato_adjunto' => $this->contato_adjunto,
                    'laborativa' => $this->laborativa,
                    'contato_laborativa' => $this->contato_laborativa,
                    'visita' => $this->visita,
                    'interno' => $this->interno,
                ]
            );

            session()->flash('message', $this->presidio_id ? 'Presídio atualizado com sucesso!' : 'Presídio cadastrado com sucesso!');
            $this->closeModal();
        } catch (ValidationException $e) {
            $this->errorMessage = implode(' ', $e->validator->errors()->all());
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro: ' . $e->getMessage();
            Log::error('Erro ao salvar presídio: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $presidio = Presidio::findOrFail($id);
        $this->presidio_id = $id;
        $this->nome = $presidio->nome;
        $this->diretor = $presidio->diretor;
        $this->contato_diretor = $presidio->contato_diretor;
        $this->adjunto = $presidio->adjunto;
        $this->contato_adjunto = $presidio->contato_adjunto;
        $this->laborativa = $presidio->laborativa;
        $this->contato_laborativa = $presidio->contato_laborativa;
        $this->visita = $presidio->visita;
        $this->interno = $presidio->interno;
        $this->errorMessage = '';
        $this->openModal();
    }

    public function view($id)
    {
        $this->selectedPresidio = Presidio::findOrFail($id);
        $this->openViewModal();
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmDeleteId) {
            Presidio::findOrFail($this->confirmDeleteId)->delete();
            session()->flash('message', 'Presídio excluído com sucesso!');
            $this->confirmDeleteId = null;
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function search()
    {
        $this->resetPage();
    }
}