<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

/**
 * Page « Mon profil » de l'admin : nom et mot de passe modifiables
 * (le mot de passe actuel est demandé pour en changer).
 *
 * L'e-mail est en lecture seule : l'accès à l'admin est réservé à SITE_ADMIN_EMAIL,
 * le changer ici ferait perdre l'accès.
 */
class EditProfile extends BaseEditProfile
{
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::auth/pages/edit-profile.form.email.label'))
            ->email()
            ->disabled()
            ->dehydrated(false)
            ->helperText('L’accès à l’admin est réservé à cette adresse (SITE_ADMIN_EMAIL dans le fichier .env).');
    }
}
