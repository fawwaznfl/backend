<?php

namespace App\Scope;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Ambil user dari sanctum
        $pegawai = Auth::guard('sanctum')->user();

        // Jika belum login → JANGAN filter apa pun
        if (!$pegawai) {
            return;
        }

        // Jika superadmin → JANGAN filter
        if ($pegawai->dashboard_type === 'superadmin') {
            return;
        }

        // Jika model TIDAK punya kolom 'company_id' → JANGAN filter
        if (!in_array('company_id', $model->getFillable())) {
            return;
        }
        

        // Baru filter
        $builder->where($model->getTable() . '.company_id', $pegawai->company_id);
    }
}
