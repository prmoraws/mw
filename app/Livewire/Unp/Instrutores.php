<?php

namespace App\Livewire\Unp;

use App\Models\Universal\Bloco;
use App\Models\Universal\Categoria;
use App\Models\Unp\Instrutor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Instrutores extends Component
{
    use WithPagination, WithFileUploads;

    public $instrutor_id, $bloco_id, $categoria_id, $foto, $nome, $telefone, $igreja, $profissao, $batismo = [], $testemunho, $carga, $certificado = false, $inscricao = false;
    public $isOpen = false;
    public $isViewOpen = false;
    public $confirmDeleteId = null;
    public $searchTerm = '';
    public $selectedInstrutor;
    public $errorMessage = '';
    public $blocoOptions = [];
    public $categoriaOptions = [];

    public function mount()
    {
        $this->blocoOptions = Bloco::pluck('nome', 'id')->toArray();
        $this->categoriaOptions = Categoria::pluck('nome', 'id')->toArray();
    }

    public function render()
    {
        $query = Instrutor::query()->with(['bloco', 'categoria']);

        if ($this->searchTerm !== '') {
            $query->where('nome', 'like', '%' . $this->searchTerm . '%');
        }

        $results = $query->paginate(20);

        return view('livewire.unp.instrutores', [
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

    public function openViewModal()
    {
        $this->isViewOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewOpen = false;
        $this->selectedInstrutor = null;
    }

    private function resetInputFields()
    {
        $this->instrutor_id = null;
        $this->bloco_id = '';
        $this->categoria_id = '';
        $this->foto = null;
        $this->nome = '';
        $this->telefone = '';
        $this->igreja = '';
        $this->profissao = '';
        $this->batismo = [];
        $this->testemunho = '';
        $this->carga = '';
        $this->certificado = false;
        $this->inscricao = false;
        $this->errorMessage = '';
    }

    public function store()
    {
        try {
            $this->validate([
                'bloco_id' => 'required|exists:blocos,id',
                'categoria_id' => 'required|exists:categorias,id',
                'foto' => $this->instrutor_id ? 'nullable|image|mimes:jpeg,png,jpg,gif,svg' : 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'nome' => 'required|string|max:255',
                'telefone' => 'required|string|max:255',
                'igreja' => 'required|string|max:255',
                'profissao' => 'required|string|max:255',
                'testemunho' => 'nullable|string',
                'carga' => 'nullable|string|max:255',
                'certificado' => 'boolean',
                'inscricao' => 'boolean',
            ]);

            $foto_caminho = $this->instrutor_id ? Instrutor::find($this->instrutor_id)->foto : 'Uploads/06e9c4a69e176a4d4d832befac40377c.jpg';
            if ($this->foto && is_object($this->foto)) {
                $foto_nome = md5($this->foto . microtime()) . '.' . $this->foto->extension();
                $foto_caminho = 'Uploads/' . $foto_nome;

                if ($this->instrutor_id) {
                    $instrutor_antigo = Instrutor::find($this->instrutor_id);
                    if ($instrutor_antigo && $instrutor_antigo->foto && Storage::disk('public_uploads')->exists($instrutor_antigo->foto)) {
                        Storage::disk('public_uploads')->delete($instrutor_antigo->foto);
                    }
                }

                $path = $this->foto->storeAs('', $foto_nome, 'public_Uploads');
                if (!$path) {
                    throw new \Exception('Falha ao salvar o arquivo no disco public_uploads');
                }
            }

            $batismoArray = is_array($this->batismo) ? $this->batismo : [];

            Instrutor::updateOrCreate(
                ['id' => $this->instrutor_id],
                [
                    'bloco_id' => $this->bloco_id,
                    'categoria_id' => $this->categoria_id,
                    'foto' => $foto_caminho,
                    'nome' => $this->nome,
                    'telefone' => $this->telefone,
                    'igreja' => $this->igreja,
                    'profissao' => $this->profissao,
                    'batismo' => json_encode($batismoArray),
                    'testemunho' => $this->testemunho,
                    'carga' => $this->carga,
                    'certificado' => $this->certificado,
                    'inscricao' => $this->inscricao,
                ]
            );

            session()->flash('message', $this->instrutor_id ? 'Instrutor atualizado com sucesso.' : 'Instrutor criado com sucesso.');
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
            $instrutor = Instrutor::findOrFail($id);

            $this->instrutor_id = $id;
            $this->bloco_id = $instrutor->bloco_id;
            $this->categoria_id = $instrutor->categoria_id;
            $this->foto = null;
            $this->nome = $instrutor->nome;
            $this->telefone = $instrutor->telefone;
            $this->igreja = $instrutor->igreja;
            $this->profissao = $instrutor->profissao;
            $this->batismo = is_string($instrutor->batismo) ? json_decode($instrutor->batismo, true) : ($instrutor->batismo ?? []);
            if (!is_array($this->batismo)) {
                $this->batismo = [];
            }
            $this->testemunho = $instrutor->testemunho;
            $this->carga = $instrutor->carga;
            $this->certificado = $instrutor->certificado;
            $this->inscricao = $instrutor->inscricao;
            $this->errorMessage = '';

            $this->openModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível carregar o instrutor para edição: ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        try {
            $this->selectedInstrutor = Instrutor::with(['bloco', 'categoria'])->findOrFail($id);

            $batismo = is_string($this->selectedInstrutor->batismo) ? json_decode($this->selectedInstrutor->batismo, true) : ($this->selectedInstrutor->batismo ?? []);
            if (!is_array($batismo)) {
                $batismo = [];
            }
            $this->selectedInstrutor->batismoAguas = in_array('aguas', $batismo) ? 'Sim' : 'Não';
            $this->selectedInstrutor->batismoEspirito = in_array('espirito', $batismo) ? 'Sim' : 'Não';

            $this->openViewModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível carregar os dados do instrutor: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        Log::info("Confirmando exclusão do instrutor ID: {$id}");
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmDeleteId) {
            $instrutor = Instrutor::find($this->confirmDeleteId);
            if ($instrutor) {
                if ($instrutor->foto && Storage::disk('public_Uploads')->exists($instrutor->foto)) {
                    Storage::disk('public_uploads')->delete($instrutor->foto);
                }
                $instrutor->delete();
                session()->flash('message', 'Instrutor deletado com sucesso.');
                Log::info("Instrutor deletado com sucesso", ['id' => $this->confirmDeleteId]);
            } else {
                session()->flash('error', 'Instrutor não encontrado.');
                Log::error("Instrutor não encontrado para exclusão", ['id' => $this->confirmDeleteId]);
            }
            $this->confirmDeleteId = null;
            $this->search();
        }
    }
}
