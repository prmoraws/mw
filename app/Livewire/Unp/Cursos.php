<?php

namespace App\Livewire\Unp;

use App\Models\Unp\Curso;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Cursos extends Component
{
    use WithPagination;

    public $nome, $unidade, $dia_hora, $professor, $carga, $reeducandos, $inicio, $fim, $formatura, $status, $curso_id;
    public $isOpen = false;
    public $isViewOpen = false;
    public $confirmDeleteId = null;
    public $searchTerm = '';
    public $selectedCurso;
    public $errorMessage = '';

    public function render()
    {
        $results = $this->searchTerm !== null
            ? Curso::where('nome', 'like', '%' . $this->searchTerm . '%')
            ->orderBy('nome', 'asc')
            ->paginate(20)
            : Curso::orderBy('nome', 'asc')
            ->paginate(20);

        return view('livewire.unp.cursos', [
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
            $this->selectedCurso = Curso::findOrFail($id);
            $this->isViewOpen = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível carregar os dados do curso: ' . $e->getMessage());
        }
    }

    public function closeViewModal()
    {
        $this->isViewOpen = false;
        $this->selectedCurso = null;
    }

    private function resetInputFields()
    {
        $this->nome = '';
        $this->unidade = '';
        $this->dia_hora = '';
        $this->professor = '';
        $this->carga = '';
        $this->reeducandos = 0;
        $this->inicio = '';
        $this->fim = '';
        $this->formatura = '';
        $this->status = '';
        $this->curso_id = '';
    }

    public function store()
    {
        try {
            $this->validate([
                'nome' => 'required|string|max:255',
                'unidade' => 'required|string|max:255',
                'dia_hora' => 'required|string|max:255',
                'professor' => 'required|string|max:255',
                'carga' => 'required|string|max:255',
                'reeducandos' => 'required|integer|min:0',
                'inicio' => 'required|date',
                'fim' => 'required|date|after_or_equal:inicio',
                'formatura' => 'nullable|date|after_or_equal:fim',
                'status' => 'nullable|string|max:50',
            ]);

            Curso::updateOrCreate(
                ['id' => $this->curso_id],
                [
                    'nome' => $this->nome,
                    'unidade' => $this->unidade,
                    'dia_hora' => $this->dia_hora,
                    'professor' => $this->professor,
                    'carga' => $this->carga,
                    'reeducandos' => $this->reeducandos,
                    'inicio' => Carbon::parse($this->inicio),
                    'fim' => Carbon::parse($this->fim),
                    'formatura' => $this->formatura ? Carbon::parse($this->formatura) : null,
                    'status' => $this->status,
                ]
            );

            session()->flash('message', $this->curso_id ? 'Curso atualizado com sucesso!' : 'Curso criado com sucesso!');
            $this->closeModal();
            $this->search();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $this->errorMessage = implode(' ', $errors);
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro inesperado: ' . $e->getMessage();
        }
    }

    public function edit($id)
    {
        try {
            $curso = Curso::findOrFail($id);
            $this->curso_id = $id;
            $this->nome = $curso->nome;
            $this->unidade = $curso->unidade;
            $this->dia_hora = $curso->dia_hora;
            $this->professor = $curso->professor;
            $this->carga = $curso->carga;
            $this->reeducandos = $curso->reeducandos;
            $this->inicio = $curso->inicio ? Carbon::parse($curso->inicio)->toDateString() : '';
            $this->fim = $curso->fim ? Carbon::parse($curso->fim)->toDateString() : '';
            $this->formatura = $curso->formatura ? Carbon::parse($curso->formatura)->toDateString() : '';
            $this->status = $curso->status;

            $this->errorMessage = '';
            $this->openModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível carregar o curso para edição: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        Log::info("Confirmando exclusão do curso ID: {$id}");
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmDeleteId) {
            $curso = Curso::find($this->confirmDeleteId);
            if ($curso) {
                $curso->delete();
                session()->flash('message', 'Curso deletado com sucesso!');
                Log::info("Curso deletado com sucesso", ['id' => $this->confirmDeleteId]);
            } else {
                session()->flash('error', 'Curso não encontrado.');
                Log::error("Curso não encontrado para exclusão", ['id' => $this->confirmDeleteId]);
            }
            $this->confirmDeleteId = null;
            $this->search();
        }
    }
}