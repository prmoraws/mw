<?php

namespace App\Livewire\Evento;

use App\Models\Evento\Instituicao;
use App\Models\Universal\Bloco;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class Instituicoes extends Component
{
    use WithPagination;

    public $nome, $contato, $bairro, $convidados, $onibus, $bloco, $iurd, $pastor, $telefone, $endereco, $localização, $instituicao_id;
    public $isOpen = false;
    public $isViewOpen = false;
    public $confirmDeleteId = null;
    public $searchTerm = '';
    public $blocoOptions = [];
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $errorMessage = '';
    public $selectedInstituicao;

    public function mount()
    {
        $this->blocoOptions = Bloco::orderBy('nome')
            ->pluck('nome')
            ->map(function ($nome) {
                return strtoupper($nome);
            })
            ->toArray();
        Log::info("Inicializando opções de bloco", ['blocoOptions' => $this->blocoOptions]);
    }

    public function render()
    {
        $query = Instituicao::query();

        if ($this->searchTerm !== '') {
            $query->where('nome', 'like', '%' . $this->searchTerm . '%');
        }

        if ($this->sortField) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $results = $query->paginate(20);

        return view('livewire.evento.instituicoes', [
            'results' => $results,
            'blocoOptions' => $this->blocoOptions,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            if ($this->sortDirection === 'asc') {
                $this->sortDirection = 'desc';
            } elseif ($this->sortDirection === 'desc') {
                $this->sortField = 'created_at';
                $this->sortDirection = 'desc';
            }
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
        Log::info("Ordenação aplicada", ['field' => $this->sortField, 'direction' => $this->sortDirection]);
    }

    public function search()
    {
        Log::info("Pesquisa realizada", ['searchTerm' => $this->searchTerm]);
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->errorMessage = '';
        $this->openModal();
        Log::info("Abrindo modal de criação", ['bloco' => $this->bloco]);
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
        $this->selectedInstituicao = null;
    }

    private function resetInputFields()
    {
        $this->nome = '';
        $this->contato = '';
        $this->bairro = '';
        $this->convidados = '';
        $this->onibus = '';
        $this->bloco = '';
        $this->iurd = '';
        $this->pastor = '';
        $this->telefone = '';
        $this->endereco = '';
        $this->localização = '';
        $this->instituicao_id = '';
        $this->errorMessage = '';
        Log::info("Campos resetados", ['bloco' => $this->bloco]);
    }

    public function store()
    {
        try {
            $this->validate([
                'nome' => 'required|string|max:255',
                'contato' => 'required|string|max:255',
                'bairro' => 'required|string|max:255',
                'convidados' => 'required|string|max:255',
                'onibus' => 'required|string|max:255',
                'bloco' => 'required|in:' . implode(',', $this->blocoOptions),
                'iurd' => 'required|string|max:255',
                'pastor' => 'required|string|max:255',
                'telefone' => 'required|string|max:255',
                'endereco' => 'required|string|max:255',
                'localização' => 'required|string|max:255',
            ], [
                'nome.required' => 'O campo Nome é obrigatório.',
                'contato.required' => 'O campo Contato é obrigatório.',
                'bairro.required' => 'O campo Bairro é obrigatório.',
                'convidados.required' => 'O campo Convidados é obrigatório.',
                'onibus.required' => 'O campo Ônibus é obrigatório.',
                'bloco.required' => 'O campo Bloco é obrigatório.',
                'bloco.in' => 'O bloco selecionado é inválido.',
                'iurd.required' => 'O campo IURD é obrigatório.',
                'pastor.required' => 'O campo Pastor é obrigatório.',
                'telefone.required' => 'O campo Telefone é obrigatório.',
                'endereco.required' => 'O campo Endereço é obrigatório.',
                'localização.required' => 'O campo Localização é obrigatório.',
            ]);

            Log::info("Salvando instituição", ['instituicao_id' => $this->instituicao_id, 'bloco' => $this->bloco]);

            Instituicao::updateOrCreate(
                ['id' => $this->instituicao_id],
                [
                    'nome' => $this->nome,
                    'contato' => $this->contato,
                    'bairro' => $this->bairro,
                    'convidados' => $this->convidados,
                    'onibus' => $this->onibus,
                    'bloco' => $this->bloco,
                    'iurd' => $this->iurd,
                    'pastor' => $this->pastor,
                    'telefone' => $this->telefone,
                    'endereco' => $this->endereco,
                    'localização' => $this->localização,
                ]
            );

            session()->flash('message', $this->instituicao_id ? 'Instituição atualizada com sucesso!' : 'Instituição criada com sucesso!');
            Log::info("Instituição salva", ['id' => $this->instituicao_id ?: 'novo', 'bloco' => $this->bloco]);
            $this->closeModal();
            $this->search();
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $this->errorMessage = implode(' ', $errors);
            Log::error("Erro de validação ao salvar instituição", ['errors' => $errors, 'bloco' => $this->bloco]);
        } catch (\Illuminate\Database\QueryException $e) {
            $message = $e->getMessage();
            if (str_contains($message, 'Duplicate entry')) {
                $this->errorMessage = 'Já existe um registro com este telefone ou outro campo único.';
            } elseif (str_contains($message, 'Data too long')) {
                $this->errorMessage = 'Um dos campos excede o tamanho máximo permitido.';
            } elseif (str_contains($message, 'Unknown column')) {
                $this->errorMessage = 'Um campo não foi encontrado no banco de dados.';
            } elseif (str_contains($message, 'Connection refused')) {
                $this->errorMessage = 'Não foi possível conectar ao banco de dados. Verifique a configuração.';
            } else {
                $this->errorMessage = 'Erro ao salvar a instituição: ' . $message;
            }
            Log::error("Erro do MySQL ao salvar instituição", ['exception' => message]);
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro inesperado: ' . $e->getMessage();
            Log::error("Erro inesperado ao salvar instituição", ['exception' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        Log::info("Evento 'edit' disparado para instituição com ID: {$id}");
        try {
            $instituicao = Instituicao::findOrFail($id);
            Log::info("Editando instituição ID: {$id}", $instituicao->toArray());

            $this->instituicao_id = $id;
            $this->nome = $instituicao->nome;
            $this->contato = $instituicao->contato;
            $this->bairro = $instituicao->bairro;
            $this->convidados = $instituicao->convidados;
            $this->onibus = $instituicao->onibus;
            $this->bloco = in_array($instituicao->bloco, $this->blocoOptions) ? $instituicao->bloco : '';
            $this->iurd = $instituicao->iurd;
            $this->pastor = $instituicao->pastor;
            $this->telefone = $instituicao->telefone;
            $this->endereco = $instituicao->endereco;
            $this->localização = $instituicao->localização;

            $this->errorMessage = '';
            $this->openModal();
            Log::info("Modal de edição aberto", ['bloco' => $this->bloco]);
        } catch (\Exception $e) {
            Log::error("Erro ao editar instituição ID: {$id}", ['exception' => $e->getMessage()]);
            session()->flash('error', 'Não foi possível carregar a instituição para edição.');
        }
    }

    public function view($id)
    {
        Log::info("Evento 'view' disparado para instituição com ID: {$id}");
        try {
            $this->selectedInstituicao = Instituicao::findOrFail($id);
            $this->openViewModal();
        } catch (\Exception $e) {
            Log::error("Erro ao visualizar instituição ID: {$id}", ['exception' => $e->getMessage()]);
            session()->flash('error', 'Não foi possível carregar os dados da instituição.');
        }
    }

    public function confirmDelete($id)
    {
        Log::info("Confirmando exclusão da instituição ID: {$id}");
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmDeleteId) {
            Log::info("Evento 'delete' disparado para instituição com ID: {$this->confirmDeleteId}");
            try {
                $instituicao = Instituicao::findOrFail($this->confirmDeleteId);
                $instituicao->delete();
                session()->flash('message', 'Instituição deletada com sucesso!');
                Log::info("Instituição deletada", ['id' => $this->confirmDeleteId]);
            } catch (\Exception $e) {
                Log::error("Erro ao deletar instituição ID: {$this->confirmDeleteId}", ['exception' => $e->getMessage()]);
                session()->flash('error', 'Erro ao deletar a instituição.');
            }
            $this->confirmDeleteId = null;
            $this->search();
        }
    }
}
