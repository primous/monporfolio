<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthentication;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Compte admin. Double authentification (application d'authentification + codes de secours) :
 * clé et codes chiffrés en base (casts « encrypted » ajoutés par les traits Filament), jamais sérialisés.
 */
class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery
{
    use InteractsWithAppAuthentication, InteractsWithAppAuthenticationRecovery;

    /**
     * Accès au panel Filament : uniquement l'e-mail défini dans config('site.admin_email')
     * (comparaison insensible à la casse). Valeur vide => personne n'a accès.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        $adminEmail = trim((string) config('site.admin_email'));

        if ($adminEmail === '') {
            return false;
        }

        return strcasecmp(trim((string) $this->email), $adminEmail) === 0;
    }

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
