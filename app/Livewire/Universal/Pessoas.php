<?php

namespace App\Livewire\Universal;

use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Universal\Bloco;
use App\Models\Universal\Igreja;
use App\Models\Universal\Pessoa;
use App\Models\Universal\Regiao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Pessoas extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $nome, $celular, $telefone, $email, $endereco, $bairro, $cep, $cidade_id, $estado_id, $profissao, $aptidoes, $conversao, $obra, $testemunho, $pessoa_id, $bloco_id, $regiao_id, $igreja_id, $categoria_id, $cargo_id, $grupo_id, $foto;
    public $isOpen = false;
    public $isViewOpen = false;
    public $confirmDeleteId = null;
    public $searchTerm = '';
    public $trabalho = [];
    public $batismo = [];
    public $preso = [];
    public $cidades = [];
    public $regiaos = [];
    public $igrejas = [];
    public $selectedPessoa;
    public $errorMessage = '';

    public function render()
    {
        $results = $this->searchTerm !== null
            ? Pessoa::with(['bloco', 'regiao', 'igreja', 'cidade', 'estado', 'cargo', 'categoria', 'grupo'])
            ->where('nome', 'like', '%' . $this->searchTerm . '%')
            ->orderBy('nome', 'asc')
            ->paginate(20)
            : Pessoa::with(['bloco', 'regiao', 'igreja', 'cidade', 'estado', 'cargo', 'categoria', 'grupo'])
            ->orderBy('nome', 'asc')
            ->paginate(20);

        return view('livewire.universal.pessoas', [
            'results' => $results,
            'blocos' => Bloco::all(),
            'regiaos' => $this->regiaos,
            'igrejas' => $this->igrejas,
            'cidades' => $this->cidades,
            'estados' => Estado::orderBy('nome')->get(),
        ]);
    }

    public function FiterRegiaoByBlocoId()
    {
        $this->regiaos = Regiao::where('bloco_id', $this->bloco_id)->get();
        $this->regiao_id = null;
        $this->igrejas = [];
        $this->igreja_id = null;
    }

    public function FiterIgrejaByRegiaoId()
    {
        $this->igrejas = Igreja::where('regiao_id', $this->regiao_id)->get();
        $this->igreja_id = null;
    }

    public function FiterRegiaoByEstadoId()
    {
        $this->cidades = Cidade::where('estado_id', $this->estado_id)->get();
        $this->cidade_id = null;
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
            $this->selectedPessoa = Pessoa::with(['bloco', 'regiao', 'igreja', 'cidade', 'estado', 'cargo', 'categoria', 'grupo'])->findOrFail($id);

            $trabalho = is_string($this->selectedPessoa->trabalho) ? json_decode($this->selectedPessoa->trabalho, true) : ($this->selectedPessoa->trabalho ?? []);
            if (!is_array($trabalho)) {
                $trabalho = [];
            }
            $trabalhoTexto = [];
            if (in_array('externo', $trabalho)) {
                $trabalhoTexto[] = 'Externo';
            }
            if (in_array('interno', $trabalho)) {
                $trabalhoTexto[] = 'Interno';
            }
            $this->selectedPessoa->trabalhoTexto = $trabalhoTexto ? 'Trabalho: ' . implode(' | ', $trabalhoTexto) : 'Trabalho: Não informado';

            $batismo = is_string($this->selectedPessoa->batismo) ? json_decode($this->selectedPessoa->batismo, true) : ($this->selectedPessoa->batismo ?? []);
            if (!is_array($batismo)) {
                $batismo = [];
            }
            $this->selectedPessoa->batismoAguas = in_array('aguas', $batismo) ? 'Sim' : 'Não';
            $this->selectedPessoa->batismoEspirito = in_array('espirito', $batismo) ? 'Sim' : 'Não';

            $preso = is_string($this->selectedPessoa->preso) ? json_decode($this->selectedPessoa->preso, true) : ($this->selectedPessoa->preso ?? []);
            if (!is_array($preso)) {
                $preso = [];
            }
            $this->selectedPessoa->jaFoiPreso = in_array('preso', $preso) ? 'Sim' : 'Não';
            $this->selectedPessoa->familiarPreso = in_array('familiar', $preso) ? 'Sim' : 'Não';

            $this->isViewOpen = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível carregar os dados da pessoa: ' . $e->getMessage());
        }
    }

    public function closeViewModal()
    {
        $this->isViewOpen = false;
        $this->selectedPessoa = null;
    }

    private function resetInputFields()
    {
        $this->nome = '';
        $this->celular = '';
        $this->telefone = '';
        $this->email = '';
        $this->endereco = '';
        $this->bairro = '';
        $this->cep = '';
        $this->cidade_id = '';
        $this->estado_id = '';
        $this->profissao = 'não informado';
        $this->aptidoes = 'não informado';
        $this->conversao = '';
        $this->obra = '';
        $this->testemunho = '';
        $this->pessoa_id = '';
        $this->bloco_id = '';
        $this->regiao_id = '';
        $this->igreja_id = 646;
        $this->categoria_id = '';
        $this->cargo_id = '';
        $this->grupo_id = '';
        $this->foto = null;
        $this->trabalho = [];
        $this->batismo = [];
        $this->preso = [];
        $this->cidades = [];
        $this->regiaos = [];
        $this->igrejas = [];
    }

    public function store()
    {
        try {
            $this->validate([
                'nome' => 'required|string|min:3|max:250',
                'celular' => 'required',
                'email' => 'email',
                'endereco' => 'required',
                'bairro' => 'required',
                'profissao' => 'required',
                'foto' => $this->pessoa_id ? 'nullable|image|mimes:jpeg,png,jpg,gif,svg' : 'required|image|mimes:jpeg,png,jpg,gif,svg',
            ]);

            $foto_caminho = $this->pessoa_id ? Pessoa::find($this->pessoa_id)->foto : 'uploads/06e9c4a69e176a4d4d832befac40377c.jpg';
            if ($this->foto && is_object($this->foto)) {
                $foto_nome = md5($this->foto . microtime()) . '.' . $this->foto->extension();
                $foto_caminho = 'uploads/' . $foto_nome;

                if ($this->pessoa_id) {
                    $pessoa_antiga = Pessoa::find($this->pessoa_id);
                    if ($pessoa_antiga && $pessoa_antiga->foto && Storage::disk('public_uploads')->exists($pessoa_antiga->foto)) {
                        Storage::disk('public_uploads')->delete($pessoa_antiga->foto);
                    }
                }

                $path = $this->foto->storeAs('', $foto_nome, 'public_uploads');
                if (!$path) {
                    throw new \Exception('Falha ao salvar o arquivo no disco public_uploads');
                }
            }

            $this->igreja_id = $this->igreja_id ?: 646;
            $this->profissao = $this->profissao ?: 'não informado';
            $this->aptidoes = $this->aptidoes ?: 'não informado';
            $foto_caminho = $foto_caminho ?: 'uploads/06e9c4a69e176a4d4d832befac40377c.jpg';

            $trabalhoArray = is_array($this->trabalho) ? $this->trabalho : [];
            $batismoArray = is_array($this->batismo) ? $this->batismo : [];
            $presoArray = is_array($this->preso) ? $this->preso : [];

            Pessoa::updateOrCreate(['id' => $this->pessoa_id], [
                'bloco_id' => $this->bloco_id,
                'regiao_id' => $this->regiao_id,
                'igreja_id' => $this->igreja_id,
                'categoria_id' => $this->categoria_id,
                'cargo_id' => $this->cargo_id,
                'grupo_id' => $this->grupo_id,
                'cidade_id' => $this->cidade_id,
                'estado_id' => $this->estado_id,
                'foto' => $foto_caminho,
                'nome' => $this->nome,
                'celular' => $this->celular,
                'telefone' => $this->telefone,
                'email' => $this->email,
                'endereco' => $this->endereco,
                'bairro' => $this->bairro,
                'cep' => $this->cep,
                'profissao' => $this->profissao,
                'aptidoes' => $this->aptidoes,
                'conversao' => empty($this->conversao) ? null : Carbon::parse($this->conversao),
                'obra' => empty($this->obra) ? null : Carbon::parse($this->obra),
                'trabalho' => json_encode($trabalhoArray),
                'batismo' => json_encode($batismoArray),
                'preso' => json_encode($presoArray),
                'testemunho' => $this->testemunho,
            ]);

            session()->flash('message', $this->pessoa_id ? 'Pessoa atualizada com sucesso.' : 'Pessoa criada com sucesso.');
            $this->closeModal();
            $this->search();
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $this->errorMessage = implode(' ', $errors);
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro inesperado: ' . $e->getMessage();
        }
    }

    public function edit($id)
    {
        try {
            $pessoa = Pessoa::findOrFail($id);
            $this->pessoa_id = $id;
            $this->bloco_id = $pessoa->bloco_id;
            $this->regiao_id = $pessoa->regiao_id;
            $this->igreja_id = $pessoa->igreja_id;
            $this->categoria_id = $pessoa->categoria_id;
            $this->cargo_id = $pessoa->cargo_id;
            $this->grupo_id = $pessoa->grupo_id;
            $this->cidade_id = $pessoa->cidade_id;
            $this->estado_id = $pessoa->estado_id;
            $this->nome = $pessoa->nome;
            $this->celular = $pessoa->celular;
            $this->telefone = $pessoa->telefone;
            $this->email = $pessoa->email;
            $this->endereco = $pessoa->endereco;
            $this->bairro = $pessoa->bairro;
            $this->cep = $pessoa->cep;
            $this->profissao = $pessoa->profissao;
            $this->aptidoes = $pessoa->aptidoes;
            $this->conversao = $pessoa->conversao ? Carbon::parse($pessoa->conversao)->toDateString() : '';
            $this->obra = $pessoa->obra ? Carbon::parse($pessoa->obra)->toDateString() : '';

            $this->trabalho = is_string($pessoa->trabalho) ? json_decode($pessoa->trabalho, true) : ($pessoa->trabalho ?? []);
            if (!is_array($this->trabalho)) {
                $this->trabalho = [];
            }

            $this->batismo = is_string($pessoa->batismo) ? json_decode($pessoa->batismo, true) : ($pessoa->batismo ?? []);
            if (!is_array($this->batismo)) {
                $this->batismo = [];
            }

            $this->preso = is_string($pessoa->preso) ? json_decode($pessoa->preso, true) : ($pessoa->preso ?? []);
            if (!is_array($this->preso)) {
                $this->preso = [];
            }

            $this->testemunho = $pessoa->testemunho;
            $this->foto = null;

            $this->FiterRegiaoByEstadoId();
            $this->FiterRegiaoByBlocoId();
            $this->FiterIgrejaByRegiaoId();

            $this->errorMessage = '';
            $this->openModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível carregar a pessoa para edição: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        \Log::info("Confirmando exclusão da pessoa ID: {$id}");
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmDeleteId) {
            $pessoa = Pessoa::find($this->confirmDeleteId);
            if ($pessoa) {
                if ($pessoa->foto && Storage::disk('public_uploads')->exists($pessoa->foto)) {
                    Storage::disk('public_uploads')->delete($pessoa->foto);
                }
                $pessoa->delete();
                session()->flash('message', 'Pessoa deletada com sucesso.');
                \Log::info("Pessoa deletada com sucesso", ['id' => $this->confirmDeleteId]);
            } else {
                session()->flash('error', 'Pessoa não encontrada.');
                \Log::error("Pessoa não encontrada para exclusão", ['id' => $this->confirmDeleteId]);
            }
            $this->confirmDeleteId = null;
            $this->search();
        }
    }
}
