<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiFace extends Model
{
    protected $fillable = ['pegawai_id', 'embedding'];
    protected $casts = ['embedding' => 'array'];
}

