<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

class QueryScopeHelper
{
    
    public static function apply(Builder $query, User $user): Builder
    {
        // PEGAWAI
        if ($user->level === 'pegawai') {
            return $query->where('pegawai_id', $user->pegawai_id);
        }

        // ADMIN
        if ($user->level === 'admin') {
            return $query->where('company_id', $user->company_id);
        }

        // SUPERADMIN → TANPA FILTER
        return $query;
    }
}
