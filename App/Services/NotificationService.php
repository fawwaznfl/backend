<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationTarget;

class NotificationService
{
    public static function create(array $payload, array $targets)
    {
        $notification = Notification::create([
            'type'       => $payload['type'],
            'title'      => $payload['title'],
            'message'    => $payload['message'],
            'data'       => $payload['data'] ?? null,
            'company_id' => $payload['company_id'] ?? null,
            'created_by' => auth()->id() ?? null,
        ]);

        foreach ($targets as $target) {
            NotificationTarget::create(array_merge(
                ['notification_id' => $notification->id],
                $target
            ));
        }

        return $notification;
    }
}
