<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class UserPolicy
{
    use HandlesAuthorization;

    // Super admin & admin bypass — Gate::before handles this
    // but we add explicit checks here as a safety net
    public function viewAny(AuthUser $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin','client_manager'])
            || $user->can('view_any_user');
    }

    public function view(AuthUser $user, User $model): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin','client_manager'])
            || $user->can('view_user');
    }

    public function create(AuthUser $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin','client_manager'])
            || $user->can('create_user');
    }

    public function update(AuthUser $user, User $model): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin','client_manager'])
            || $user->can('update_user');
    }

    public function delete(AuthUser $user, User $model): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin','client_manager'])
            || $user->can('delete_user');
    }

    public function deleteAny(AuthUser $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin','client_manager'])
            || $user->can('delete_any_user');
    }

    public function restore(AuthUser $user, User $model): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin','client_manager'])
            || $user->can('restore_user');
    }

    public function restoreAny(AuthUser $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin','client_manager'])
            || $user->can('restore_any_user');
    }

    public function forceDelete(AuthUser $user, User $model): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin','client_manager'])
            || $user->can('force_delete_user');
    }

    public function forceDeleteAny(AuthUser $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin','client_manager'])
            || $user->can('force_delete_any_user');
    }

    public function replicate(AuthUser $user, User $model): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin','client_manager'])
            || $user->can('replicate_user');
    }

    public function reorder(AuthUser $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin','client_manager'])
            || $user->can('reorder_user');
    }
}