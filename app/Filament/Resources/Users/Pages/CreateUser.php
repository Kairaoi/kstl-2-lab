<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Notifications\WelcomeNotification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    // Store plain password before it gets hashed
    protected string $plainPassword = '';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Capture plain password before hashing
        $this->plainPassword = $data['password'] ?? '';

        return $data;
    }

    protected function afterCreate(): void
    {
        $sendWelcome = $this->data['send_welcome_email'] ?? true;

        if ($sendWelcome && $this->plainPassword) {
            $this->record->notify(
                new WelcomeNotification($this->plainPassword)
            );
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}