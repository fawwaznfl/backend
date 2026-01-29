<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
    {
        protected $fillable = [
            'type',
            'title',
            'message',
            'data',
            'company_id',
            'created_by'
        ];

        protected $casts = [
            'data' => 'array',
        ];

        public function targets()
        {
            return $this->hasMany(NotificationTarget::class);
        }
    }
