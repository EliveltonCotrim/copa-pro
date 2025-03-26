<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Organizer;
use App\RoleEnum;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {

    //     if ($data['roles'] !== RoleEnum::ADMIN->value) {
    //         $data['userable_type'] = 'App\Models\Organizer';
    //         $data['userable_id'] = auth()->user()->userable->id;
    //     }

    //     $data['user_id'] = auth()->id();
    //     $data['password'] = Hash::make($data['password']);
    //     $data['userable_type'] = 'App\Models\Organizer';

    //     return $data;
    // }

    // protected function handleRecordCreation(array $data): Model
    // {
    //     $user = DB::transaction(function () use ($data) {

    //         if ($data['roles'] !== RoleEnum::ADMIN->value) {
    //             $organizer = Organizer::create([
    //                 'name' => $data['name'],
    //                 'phone' => $data['phone'],
    //             ]);

    //             $user = static::getModel()::create(array_merge($data, [
    //                 'userable_type' => 'App\Models\Organizer',
    //                 'userable_id' => $organizer->id,
    //             ]));
    //         } else {
    //             $user = static::getModel()::create($data);
    //         }

    //         return $user;
    //     });

    //     $user->assignRole($data['roles']);

    //     return $user;
    // }

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
