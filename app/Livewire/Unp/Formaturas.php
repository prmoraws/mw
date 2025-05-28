<?php

namespace App\Livewire\Unp;

use App\Models\Unp\Formatura;
use App\Models\Unp\Curso;
use App\Models\Unp\Instrutor;
use App\Models\Unp\Presidio;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Formaturas extends Component
{
    use WithPagination, WithFileUploads;

    public $presidio_id, $curso_id, $instrutor_id, $inicio, $fim, $formatura, $lista, $conteudo, $oficio, $formatura_id;
    public $isOpen = false;
    public $isViewOpen = false;
    public $confirmDeleteId = null;
    public $searchTerm = '';
    public $sortField = 'inicio';
    public $sortDirection = 'desc';
    public $selectedFormatura;
    public $errorMessage = '';
    public $cursos = [];
    public $instrutores = [];
    public $presidios = [];

    public function mount()
    {
        $this->cursos = Curso::all()->pluck('nome', 'id')->toArray();
        $this->instrutores = Instrutor::all()->pluck('nome', 'id')->toArray();
        $this->presidios = Presidio::all()->pluck('nome', 'id')->toArray();
    }

    public function render()
    {
        $query = Formatura::with(['presidio', 'curso', 'instrutor']);

        if ($this->searchTerm !== '') {
            $query->whereHas('presidio', function ($q) {
                $q->where('nome', 'like', '%' . $this->searchTerm . '%');
            })->orWhereHas('curso', function ($q) {
                $q->where('nome', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->sortField) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $results = $query->paginate(20);

        return view('livewire.unp.formaturas', [
            'results' => $results,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            if ($this->sortDirection === 'asc') {
                $this->sortDirection = 'desc';
            } elseif ($this->sortDirection === 'desc') {
                $this->sortField = 'inicio';
                $this->sortDirection = 'desc';
            }
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
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

    private function resetInputFields()
    {
        $this->presidio_id = '';
        $this->curso_id = '';
        $this->instrutor_id = '';
        $this->inicio = '';
        $this->fim = '';
        $this->formatura = '';
        $this->lista = null;
        $this->conteudo = null;
        $this->oficio = null;
        $this->formatura_id = '';
    }

    public function store()
    {
        try {
            $this->validate([
                'presidio_id' => 'required|exists:presidios,id',
                'curso_id' => 'required|exists:cursos,id',
                'instrutor_id' => 'required|exists:instrutores,id',
                'inicio' => 'required|date',
                'fim' => 'required|date|after:inicio',
                'formatura' => 'nullable|date|after:fim',
                'lista' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
                'conteudo' => 'nullable|file|mimes:pdf|max:10240',
                'oficio' => 'nullable|file|mimes:pdf|max:10240',
            ]);

            $data = [
                'presidio_id' => $this->presidio_id,
                'curso_id' => $this->curso_id,
                'instrutor_id' => $this->instrutor_id,
                'inicio' => $this->inicio,
                'fim' => $this->fim,
                'formatura' => $this->formatura,
            ];

            // Handle file uploads
            foreach (['lista', 'conteudo', 'oficio'] as $field) {
                if ($this->$field) {
                    $fileName = md5($this->$field . microtime()) . '.' . $this->$field->extension();
                    $path = $this->$field->storeAs('formaturas', $fileName, 'public_uploads');
                    $data[$field] = 'formaturas/' . $fileName;
                } elseif ($this->formatura_id && $this->$field === null) {
                    // Preserve existing file if no new file is uploaded during update
                    $existing = Formatura::find($this->formatura_id);
                    $data[$field] = $existing->$field;
                }
            }

            Formatura::updateOrCreate(
                ['id' => $this->formatura_id],
                $data
            );

            session()->flash('message', $this->formatura_id ? 'Formatura atualizada com sucesso!' : 'Formatura criada com sucesso!');
            $this->closeModal();
            $this->search();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $this->errorMessage = implode(' ', $errors);
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro inesperado: ' . $e->getMessage();
        }
    }

    public function view($id)
    {
        try {
            $this->selectedFormatura = Formatura::with(['presidio', 'curso', 'instrutor'])->findOrFail($id);
            $this->isViewOpen = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível carregar os dados da formatura: ' . $e->getMessage());
        }
    }

    public function closeViewModal()
    {
        $this->isViewOpen = false;
        $this->selectedFormatura = null;
    }

    public function edit($id)
    {
        try {
            $formatura = Formatura::findOrFail($id);
            $this->formatura_id = $id;
            $this->presidio_id = $formatura->presidio_id;
            $this->curso_id = $formatura->curso_id;
            $this->instrutor_id = $formatura->instrutor_id;
            $this->inicio = $formatura->inicio ? \Carbon\Carbon::parse($formatura->inicio)->toDateString() : '';
            $this->fim = $formatura->fim ? \Carbon\Carbon::parse($formatura->fim)->toDateString() : '';
            $this->formatura = $formatura->formatura ? \Carbon\Carbon::parse($formatura->formatura)->toDateString() : '';
            $this->lista = null; // File input starts empty
            $this->conteudo = null;
            $this->oficio = null;

            $this->errorMessage = '';
            $this->openModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível carregar a formatura para edição: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        Log::info("Confirmando exclusão da formatura ID: {$id}");
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmDeleteId) {
            $formatura = Formatura::find($this->confirmDeleteId);
            if ($formatura) {
                // Delete associated files
                foreach (['lista', 'conteudo', 'oficio'] as $field) {
                    if ($formatura->$field && Storage::disk('public_uploads')->exists($formatura->$field)) {
                        Storage::disk('public_Uploads')->delete($formatura->$field);
                    }
                }
                $formatura->delete();
                session()->flash('message', 'Formatura deletada com sucesso!');
                Log::info("Formatura deletada com sucesso", ['id' => $this->confirmDeleteId]);
            } else {
                session()->flash('error', 'Formatura não encontrada.');
                Log::error("Formatura não encontrada para exclusão", ['id' => $this->confirmDeleteId]);
            }
            $this->confirmDeleteId = null;
            $this->search();
        }
    }
}