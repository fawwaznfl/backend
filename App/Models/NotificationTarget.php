<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTarget extends Model
    {
        protected $fillable = [
            'notification_id',
            'user_id',
            'role',
            'company_id',
            'is_read',
            'read_at'
        ];

        public function notification()
        {
            return $this->belongsTo(Notification::class);
        }
    }
