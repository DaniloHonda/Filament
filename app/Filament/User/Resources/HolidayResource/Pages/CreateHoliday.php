<?php

namespace App\Filament\User\Resources\HolidayResource\Pages;

use App\Filament\User\Resources\HolidayResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirect to table view
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        $data['type'] = 'pending';
    }
}
