<?php

namespace App\Livewire\Evento;

use App\Models\Evento\Cesta;
use App\Models\Evento\Instituicao;
use App\Models\Evento\Terreiro;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Cestas extends Component
{
    use WithFileUploads, WithPagination;

    public $nome;
    public $searchNome = '';
    public $searchTerm = '';
    public $identificado;
    public $contato;
    public $cestas;
    public $observacao;
    public $foto;
    public $terreiros = [];
    public $instituicoes = [];
    public $editId = null;
    public $fotoAtual = null;
    public $isOpen = false;
    public $isViewOpen = false;
    public $confirmDeleteId = null;
    public $selectedCesta;
    public $errorMessage = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function mount()
    {
        $this->loadNomes();
    }

    public function updatedSearchNome()
    {
        \Log::info("updatedSearchNome chamado com valor: {$this->searchNome}");
        $this->loadNomes();
    }

    public function loadNomes()
    {
        $this->terreiros = Terreiro::where('nome', 'like', '%' . $this->searchNome . '%')
            ->orderBy('nome', 'asc')
            ->pluck('nome')
            ->toArray();

        $this->instituicoes = Instituicao::where('nome', 'like', '%' . $this->searchNome . '%')
            ->orderBy('nome', 'asc')
            ->pluck('nome')
            ->toArray();

        \Log::info("Nomes carregados - Terreiros: " . json_encode($this->terreiros) . ", Instituições: " . json_encode($this->instituicoes));
    }

    public function updatedNome($value)
    {
        \Log::info("updatedNome chamado com valor: {$value}");
        $this->identificado = '';
        $this->contato = '';

        if (!empty($value)) {
            $terreiro = Terreiro::where('nome', $value)->first();
            $instituicao = Instituicao::where('nome', $value)->first();

            if ($terreiro) {
                $this->identificado = $terreiro->terreiro ?? '';
                $this->contato = $terreiro->contato ?? '';
                \Log::info("Terreiro encontrado: " . json_encode($terreiro->toArray()));
            } elseif ($instituicao) {
                $this->identificado = $instituicao->nome ?? '';
                $this->contato = $instituicao->contato ?? '';
                \Log::info("Instituição encontrada: " . json_encode($instituicao->toArray()));
            } else {
                \Log::info("Nenhum Terreiro ou Instituição encontrado para o nome: {$value}");
            }
        } else {
            \Log::info("Nome vazio, campos identificado e contato resetados.");
        }

        // Disparar evento para atualizar o frontend
        $this->dispatch('update-inputs', [
            'identificado' => $this->identificado,
            'contato' => $this->contato,
        ]);
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
        $this->selectedCesta = null;
    }

    private function resetInputFields()
    {
        $this->nome = '';
        $this->searchNome = '';
        $this->identificado = '';
        $this->contato = '';
        $this->cestas = '';
        $this->observacao = '';
        $this->foto = null;
        $this->editId = null;
        $this->fotoAtual = null;
        $this->errorMessage = '';
    }

    public function save()
    {
        try {
            $this->validate([
                'nome' => 'required|string|max:255',
                'identificado' => 'required|string|max:255',
                'contato' => 'required|string|max:255',
                'cestas' => 'required|numeric|min:1',
                'observacao' => 'nullable|string|max:255',
                'foto' => $this->editId ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240' : 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            ]);

            $foto_caminho = $this->editId ? Cesta::find($this->editId)->foto : null;
            if ($this->foto && is_object($this->foto)) {
                $foto_nome = md5($this->foto . microtime()) . '.' . $this->foto->extension();
                $foto_caminho = 'uploads/' . $foto_nome;

                if ($this->editId) {
                    $cesta_antiga = Cesta::find($this->editId);
                    if ($cesta_antiga && $cesta_antiga->foto && Storage::disk('public_uploads')->exists($cesta_antiga->foto)) {
                        Storage::disk('public_uploads')->delete($cesta_antiga->foto);
                    }
                }

                $path = $this->foto->storeAs('', $foto_nome, 'public_uploads');
                if (!$path) {
                    throw new \Exception('Falha ao salvar a foto no disco.');
                }
            }

            Cesta::updateOrCreate(
                ['id' => $this->editId],
                [
                    'nome' => $this->nome,
                    'terreiro' => $this->identificado,
                    'contato' => $this->contato,
                    'cestas' => $this->cestas,
                    'observacao' => $this->observacao,
                    'foto' => $foto_caminho,
                ]
            );

            session()->flash('message', $this->editId ? 'Cesta atualizada com sucesso!' : 'Cesta cadastrada com sucesso!');
            $this->closeModal();
            $this->search();
        } catch (ValidationException $e) {
            $this->errorMessage = implode(' ', $e->validator->errors()->all());
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro: ' . $e->getMessage();
        }
    }

    public function edit($id)
    {
        $cesta = Cesta::findOrFail($id);
        $this->editId = $cesta->id;
        $this->nome = $cesta->nome;
        $this->identificado = $cesta->terreiro;
        $this->contato = $cesta->contato;
        $this->cestas = $cesta->cestas;
        $this->observacao = $cesta->observacao;
        $this->fotoAtual = $cesta->foto;
        $this->foto = null;
        $this->errorMessage = '';
        $this->openModal();
    }

    public function view($id)
    {
        $this->selectedCesta = Cesta::findOrFail($id);
        $this->openViewModal();
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmDeleteId) {
            $cesta = Cesta::findOrFail($this->confirmDeleteId);
            if ($cesta->foto && Storage::disk('public_uploads')->exists($cesta->foto)) {
                Storage::disk('public_uploads')->delete($cesta->foto);
            }
            $cesta->delete();
            session()->flash('message', 'Cesta excluída com sucesso!');
            $this->confirmDeleteId = null;
            $this->search();
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
        $this->search();
    }

    public function search()
    {
        return Cesta::query()
            ->when($this->searchTerm, function ($query) {
                $query->where('nome', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('terreiro', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);
    }

    public function render()
    {
        return view('livewire.evento.cestas', [
            'cestasList' => $this->search(),
        ])->layout('layouts.app');
    }
}