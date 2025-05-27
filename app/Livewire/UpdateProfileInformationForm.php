<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\Contracts\UpdatesUserProfileInformation;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public $state = [];
    public $photo;

    public function mount()
    {
        $this->state = Auth::user()->withoutRelations()->toArray();
    }

    public function updateProfileInformation(UpdatesUserProfileInformation $updater)
    {
        $this->resetErrorBag();

        try {
            if ($this->photo) {
                $this->validate([
                    'photo' => ['nullable', 'image', 'max:1024'],
                ]);

                Log::info('Iniciando upload da foto de perfil', [
                    'user_id' => Auth::id(),
                    'filename' => $this->photo->getClientOriginalName(),
                ]);

                $filename = $this->photo->hashName();
                $path = $this->photo->storeAs('profile-photos', $filename, 'public_uploads');

                if ($path) {
                    Log::info('Foto salva com sucesso', ['path' => $path]);
                    $this->state['profile_photo_path'] = 'profile-photos/' . $filename;
                } else {
                    Log::error('Falha ao salvar a foto', ['filename' => $filename]);
                    $this->addError('photo', 'Não foi possível salvar a foto.');
                    return;
                }
            }

            $updater->update(
                Auth::user(),
                $this->state
            );

            Log::info('Perfil atualizado', ['user_id' => Auth::id()]);

            $this->emit('saved');
            $this->photo = null;
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar perfil', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            $this->addError('photo', 'Ocorreu um erro ao salvar a foto.');
        }
    }

    public function deleteProfilePhoto()
    {
        try {
            if ($this->state['profile_photo_path']) {
                Storage::disk('public_uploads')->delete($this->state['profile_photo_path']);
                Auth::user()->forceFill([
                    'profile_photo_path' => null,
                ])->save();
                Log::info('Foto de perfil deletada', ['user_id' => Auth::id()]);
            }

            $this->state['profile_photo_path'] = null;
            $this->emit('saved');
        } catch (\Exception $e) {
            Log::error('Erro ao deletar foto de perfil', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            $this->addError('photo', 'Ocorreu um erro ao deletar a foto.');
        }
    }

    public function sendEmailVerification()
    {
        Auth::user()->sendEmailVerificationNotification();
        $this->emit('verificationLinkSent');
    }

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function render()
    {
        return view('profile.update-profile-information-form');
    }
}
