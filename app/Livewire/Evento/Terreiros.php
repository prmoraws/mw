<?php

namespace App\Livewire\Evento;

use App\Models\Evento\Terreiro;
use App\Models\Universal\Bloco;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class Terreiros extends Component
{
    use WithPagination;

    public $nome, $contato, $bairro, $terreiro, $convidados, $onibus, $bloco, $iurd, $pastor, $telefone, $endereco, $localização, $terreiro_id;
    public $isOpen = false;
    public $isViewOpen = false;
    public $confirmDeleteId = null;
    public $searchTerm = '';
    public $sortField = 'nome';
    public $sortDirection = 'asc';
    public $errorMessage = '';
    public $selectedTerreiro;
    public $blocoOptions = [];

    public function mount()
    {
        $this->blocoOptions = Bloco::orderBy('nome')
            ->pluck('nome')
            ->map(function ($nome) {
                return strtoupper($nome);
            })
            ->toArray();
    }

    public function render()
    {
        $results = Terreiro::query()
            ->when($this->searchTerm, function ($query) {
                $query->where('nome', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('bairro', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('terreiro', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);

        return view('livewire.evento.terreiros', [
            'results' => $results,
            'blocoOptions' => $this->blocoOptions,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
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
        $this->selectedTerreiro = null;
    }

    private function resetInputFields()
    {
        $this->nome = '';
        $this->contato = '';
        $this->bairro = '';
        $this->terreiro = '';
        $this->convidados = '';
        $this->onibus = '';
        $this->bloco = ''; // Inicializa como vazio para forçar seleção
        $this->iurd = '';
        $this->pastor = '';
        $this->telefone = '';
        $this->endereco = '';
        $this->localização = '';
        $this->terreiro_id = '';
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
                'terreiro' => 'required|string|max:255',
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
                'terreiro.required' => 'O campo Terreiro é obrigatório.',
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

            Log::info("Salvando terreiro", ['terreiro_id' => $this->terreiro_id, 'bloco' => $this->bloco]);

            Terreiro::updateOrCreate(
                ['id' => $this->terreiro_id],
                [
                    'nome' => $this->nome,
                    'contato' => $this->contato,
                    'bairro' => $this->bairro,
                    'terreiro' => $this->terreiro,
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

            session()->flash('message', $this->terreiro_id ? 'Terreiro atualizado com sucesso!' : 'Terreiro criado com sucesso!');
            Log::info("Terreiro salvo", ['id' => $this->terreiro_id ?: 'novo', 'bloco' => $this->bloco]);
            $this->closeModal();
            $this->search();
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $this->errorMessage = implode(' ', $errors);
            Log::error("Erro de validação ao salvar terreiro", ['errors' => $errors, 'bloco' => $this->bloco]);
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
                $this->errorMessage = 'Erro ao salvar o terreiro: ' . $message;
            }
            Log::error("Erro do MySQL ao salvar terreiro", ['exception' => $message]);
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro inesperado: ' . $e->getMessage();
            Log::error("Erro inesperado ao salvar terreiro", ['exception' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        Log::info("Evento 'edit' disparado para terreiro com ID: {$id}");
        try {
            $terreiro = Terreiro::findOrFail($id);
            Log::info("Editando terreiro ID: {$id}", $terreiro->toArray());

            $this->terreiro_id = $id;
            $this->nome = $terreiro->nome;
            $this->contato = $terreiro->contato;
            $this->bairro = $terreiro->bairro;
            $this->terreiro = $terreiro->terreiro;
            $this->convidados = $terreiro->convidados;
            $this->onibus = $terreiro->onibus;
            $this->bloco = in_array($terreiro->bloco, $this->blocoOptions) ? $terreiro->bloco : ''; // Garante que o bloco seja válido
            $this->iurd = $terreiro->iurd;
            $this->pastor = $terreiro->pastor;
            $this->telefone = $terreiro->telefone;
            $this->endereco = $terreiro->endereco;
            $this->localização = $terreiro->localização;

            $this->errorMessage = '';
            $this->openModal();
            Log::info("Modal de edição aberto", ['bloco' => $this->bloco]);
        } catch (\Exception $e) {
            Log::error("Erro ao editar terreiro ID: {$id}", ['exception' => $e->getMessage()]);
            session()->flash('error', 'Não foi possível carregar o terreiro para edição.');
        }
    }

    public function view($id)
    {
        Log::info("Evento 'view' disparado para terreiro com ID: {$id}");
        try {
            $this->selectedTerreiro = Terreiro::findOrFail($id);
            $this->openViewModal();
        } catch (\Exception $e) {
            Log::error("Erro ao visualizar terreiro ID: {$id}", ['exception' => $e->getMessage()]);
            session()->flash('error', 'Não foi possível carregar os dados do terreiro.');
        }
    }

    public function confirmDelete($id)
    {
        Log::info("Confirmando exclusão do terreiro ID: {$id}");
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmDeleteId) {
            Log::info("Evento 'delete' disparado para terreiro com ID: {$this->confirmDeleteId}");
            try {
                $terreiro = Terreiro::findOrFail($this->confirmDeleteId);
                $terreiro->delete();
                session()->flash('message', 'Terreiro deletado com sucesso!');
                Log::info("Terreiro deletado", ['id' => $this->confirmDeleteId]);
            } catch (\Exception $e) {
                Log::error("Erro ao deletar terreiro ID: {$this->confirmDeleteId}", ['exception' => $e->getMessage()]);
                session()->flash('error', 'Erro ao deletar o terreiro.');
            }
            $this->confirmDeleteId = null;
            $this->search();
        }
    }
}