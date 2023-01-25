<?php

namespace App\Services;

use Berkayk\OneSignal\OneSignalFacade;
use Exception;

class PushService
{
    public function sendToAll(string $title, string $content, string $navigateRoute,  string $schedule = null)
    {
        $data = [
            'navigateRoute' => $navigateRoute
        ];

        try {
            OneSignalFacade::sendNotificationToAll(
                $content,
                null, $data, null,
                $schedule,
                $title
            );
        } catch (Exception $e) {
            throw new Exception("Not send push notification");
        }
    }

    public function sendToExternalUser(string $title, string $content, ?string $navigateRoute, array $users, string $schedule = null)
    {
        $data = [
            'navigateRoute' => $navigateRoute
        ];

        try {
            OneSignalFacade::sendNotificationToExternalUser(
                $content,
                $users,
                null, $data, null,
                $schedule,
                $title
            );
        } catch (Exception $e) {
            throw new Exception("Not send push notification");
        }
    }
}
