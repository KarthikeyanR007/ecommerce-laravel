<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Rh_customindividuallog;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Rh_customindividuallog::create([
            'cil_user_id' => $user->id,
            'cil_modal_path' => 'App\Models\User',
            'cil_modal_name' => 'User',
            'cil_action' => 'created',
            'cil_from_value' => null,
            'cil_to_value' => $user->toArray(),
            'cil_created_by' => auth()->id() ?? $user->id,
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        RhCustomIndividualLog::create([
            'cil_user_id' => auth('api')->id(),
            'cil_modal_path' => Post::class,
            'cil_modal_name' => 'posts',
            'cil_action' => 'update',
            'cil_from_value' => $post->getOriginal(),
            'cil_to_value' => $post->getChanges(),
            'cil_created_by' => auth('api')->id(),
        ]);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
