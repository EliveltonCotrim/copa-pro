<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    public function updating(User $user): void
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');

        if ($user->isDirty($avatarColumn) && $user->getOriginal($avatarColumn)) {

            $oldAvatarPath = $user->getOriginal($avatarColumn);

            if (!str_starts_with($oldAvatarPath, 'avatars/')) {
                $oldAvatarPath = 'avatars/' . ltrim($oldAvatarPath, '/');
            }

            if (Storage::disk('public')->exists($oldAvatarPath)) {
                Storage::disk('public')->delete($oldAvatarPath);
            }

        }
    }
}
