<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto {
        updateProfilePhoto as protected jetstreamUpdateProfilePhoto;
    }
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    public function updateProfilePhoto($photo)
    {
        if ($photo) {
            $filename = $photo->hashName();
            $path = $photo->storeAs('profile-photos', $filename, 'public_uploads');
            $this->forceFill([
                'profile_photo_path' => 'profile-photos/' . $filename,
            ])->save();
        }

        if ($this->profile_photo_path && $photo !== null) {
            Storage::disk('public_uploads')->delete($this->profile_photo_path);
        }
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? Storage::disk('public_uploads')->url($this->profile_photo_path)
            : $this->defaultProfilePhotoUrl();
    }
}