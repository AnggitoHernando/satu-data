<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JenisData;

class JenisDataPolicy
{
    /**
     * View all (index)
     */
    public function viewAny(User $user): bool
    {
        // Semua boleh lihat
        return in_array($user->role, ['super-admin', 'admin', 'operator', 'user']);
    }

    /**
     * View specific data
     */
    public function view(User $user): bool
    {
        return in_array($user->role, ['super-admin', 'admin', 'operator', 'user']);
    }

    /**
     * Create new data
     */
    public function create(User $user, $seksi): bool
    {
        if ($user->role === 'admin' || $user->role === 'super-admin') return true;

        // Operator hanya boleh Add sesuai seksi
        if ($user->role === 'operator') {
            return $user->seksi()->where('seksi_id', $seksi)->exists();
        }

        return false;
    }

    /**
     * Update data
     */
    public function update(User $user, JenisData $jenisData): bool
    {
        if ($user->role === 'admin' || $user->role === 'super-admin') return true;

        // Operator hanya boleh update sesuai seksi
        if ($user->role === 'operator') {
            return $user->seksi()->where('seksi_id', $jenisData->seksi_id)->exists();
        }

        return false;
    }

    /**
     * Delete data
     */
    public function delete(User $user, JenisData $jenisData): bool
    {
        if ($user->role === 'admin' || $user->role === 'super-admin') return true;

        if ($user->role === 'operator') {
            return $user->seksi()->where('seksi_id', $jenisData->seksi_id)->exists();
        }

        return false;
    }

    public function updateStatus(User $user, JenisData $jenisData): bool
    {
        if ($user->role === 'admin' || $user->role === 'super-admin') return true;

        if ($user->role === 'operator') {
            return $user->seksi()->where('seksi_id', $jenisData->seksi_id)->exists();
        }

        return false;
    }
}
