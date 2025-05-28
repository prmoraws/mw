<?php

namespace App\Livewire\Universal;

use App\Models\Universal\Banner;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Banners extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $nome, $descricao, $foto, $banner_id;
    public $isOpen = false;
    public $isViewOpen = false;
    public $confirmDeleteId = null;
    public $searchTerm = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedBanner;

    public function render()
    {
        $query = Banner::query();

        if ($this->searchTerm !== '') {
            $query->where('nome', 'like', '%' . $this->searchTerm . '%');
        }

        if ($this->sortField) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $results = $query->paginate(20);

        return view('livewire.universal.banners', [
            'results' => $results,
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

    private function resetInputFields()
    {
        $this->nome = '';
        $this->descricao = '';
        $this->foto = null;
        $this->banner_id = '';
    }

    public function store()
    {
        $this->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'foto' => 'nullable|image|max:2048', // Máximo 2MB
        ]);

        try {
            $data = [
                'nome' => $this->nome,
                'descricao' => $this->descricao,
            ];

            if ($this->foto && is_object($this->foto)) { // Verifica se é um upload de arquivo
                $foto_nome = md5($this->foto . microtime()) . '.' . $this->foto->extension();
                $foto_caminho = 'uploads/' . $foto_nome;

                // Apagar a foto antiga ao editar
                if ($this->banner_id) {
                    $banner_antigo = Banner::find($this->banner_id);
                    if ($banner_antigo && $banner_antigo->foto && Storage::disk('public_local')->exists($banner_antigo->foto)) {
                        Storage::disk('public_local')->delete($banner_antigo->foto);
                    }
                }

                $this->foto->storeAs('uploads', $foto_nome, 'public_local');
                $data['foto'] = $foto_caminho;
            }

            Banner::updateOrCreate(['id' => $this->banner_id], $data);

            session()->flash('message', $this->banner_id ? 'Banner atualizado com sucesso!' : 'Banner criado com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar o banner: ' . $e->getMessage());
        }

        $this->search();
        $this->closeModal();
    }

    public function view($id)
    {
        try {
            $this->selectedBanner = Banner::findOrFail($id);
            $this->isViewOpen = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível carregar os dados do banner: ' . $e->getMessage());
        }
    }

    public function closeViewModal()
    {
        $this->isViewOpen = false;
        $this->selectedBanner = null;
    }

    public function edit($id)
    {
        try {
            $banner = Banner::findOrFail($id);
            $this->banner_id = $id;
            $this->nome = $banner->nome;
            $this->descricao = $banner->descricao;
            $this->foto = null; // Não recarrega a foto existente, apenas permite substituição

            $this->openModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível carregar o banner para edição: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        \Log::info("Confirmando exclusão do banner ID: {$id}");
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmDeleteId) {
            $banner = Banner::find($this->confirmDeleteId);
            if ($banner) {
                // Apagar a foto ao deletar o registro
                if ($banner->foto && Storage::disk('public_local')->exists($banner->foto)) {
                    Storage::disk('public_local')->delete($banner->foto);
                }
                $banner->delete();
                session()->flash('message', 'Banner deletado com sucesso!');
                \Log::info("Banner deletado com sucesso", ['id' => $this->confirmDeleteId]);
            } else {
                session()->flash('error', 'Banner não encontrado.');
                \Log::error("Banner não encontrado para exclusão", ['id' => $this->confirmDeleteId]);
            }
            $this->confirmDeleteId = null;
            $this->search();
        }
    }
}
